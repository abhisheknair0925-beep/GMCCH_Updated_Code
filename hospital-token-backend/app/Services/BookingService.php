<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Hospital;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingService
{
    // ── Token pool constants ────────────────────────────────────────────────
    const MAX_TOKENS      = 150; // Total tokens per type per unit per day
    const OFFLINE_STEP    = 5;   // Offline tokens: every multiple of 5
    const MAX_OFFLINE     = 30;  // 5,10,...,150  → 150 / 5 = 30 slots
    const MAX_ONLINE      = 120; // 150 - 30 = 120 online slots

    /**
     * Create a booking token (online or offline).
     *
     * @param int    $userId   The patient's user ID
     * @param int    $unitId   The unit being booked
     * @param string $type     'chemo' or 'followup'
     * @param string $source   'online' (app) or 'offline' (admin walk-in)
     *
     * @throws Exception
     */
    public function createToken(int $userId, int $unitId, string $type, string $source = 'online'): Booking
    {
        // ── Date rule ────────────────────────────────────────────────────────
        // Online  (app)   → always books for TOMORROW (prev-day booking)
        // Offline (admin) → always books for TODAY    (walk-in patient)
        $bookingDate = $source === 'offline'
            ? Carbon::today()->toDateString()
            : Carbon::tomorrow()->toDateString();

        // ── Validate Unit Operating Day ──────────────────────────────────────
        $unit = Unit::find($unitId);
        if (!$unit) {
            throw new Exception("Selected unit does not exist.");
        }
        
        $targetDayName = Carbon::parse($bookingDate)->format('l'); // e.g. 'Monday'
        if ($unit->day && $unit->day !== $targetDayName) {
            $label = $source === 'offline' ? 'Today' : 'Tomorrow';
            throw new Exception("This unit operates on {$unit->day}. You cannot book it for {$label} ({$targetDayName}).");
        }

        // Retry up to 5 times on deadlock
        return DB::transaction(function () use ($userId, $unitId, $type, $source, $bookingDate) {

            // ── 1. Prevent duplicate: one active token per user per day ────
            if ($source === 'online') {
                $existing = Booking::where('user_id', $userId)
                    ->where('booking_date', $bookingDate)
                    ->whereIn('status', ['active', 'pending'])
                    ->first();

                if ($existing) {
                    throw new Exception('You already have a booking for tomorrow. Only one token allowed per day.');
                }
            }

            // ── 2. Lock and fetch all booked token numbers for this slot ──
            $bookedTokens = Booking::where('unit_id', $unitId)
                ->where('type', $type)
                ->where('booking_date', $bookingDate)
                ->lockForUpdate()
                ->pluck('token_number')
                ->toArray();

            // ── 3. Find next available token number ───────────────────────
            $nextToken = $this->findNextToken($source, $bookedTokens);

            if ($nextToken === null) {
                $label = $source === 'offline' ? 'Offline (walk-in)' : 'Online';
                throw new Exception("{$label} tokens are fully booked for {$type} today. No more slots available.");
            }

            // ── 4. Determine status ───────────────────────────────────────
            // Offline bookings are always immediately active (admin confirmed).
            // Online bookings check auto-approve setting.
            if ($source === 'offline') {
                $status = 'active';
            } else {
                $hospital = Hospital::first();
                $isAutoApprove = $hospital
                    && $hospital->auto_approve_bookings_until
                    && Carbon::parse($hospital->auto_approve_bookings_until)->isFuture();
                $status = $isAutoApprove ? 'active' : 'pending';
            }

            // ── 5. Create the booking ─────────────────────────────────────
            try {
                return Booking::create([
                    'user_id'      => $userId,
                    'unit_id'      => $unitId,
                    'type'         => $type,
                    'token_number' => $nextToken,
                    'booking_date' => $bookingDate,
                    'status'       => $status,
                    'source'       => $source,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000) {
                    throw new Exception('High demand detected. Please try again in a few seconds.');
                }
                throw $e;
            }
        }, 5);
    }

    /**
     * Get availability counts for a unit/date across both token types.
     * Returns remaining online and offline slots for chemo and followup.
     */
    public function getAvailability(int $unitId, string $date): array
    {
        $booked = Booking::where('unit_id', $unitId)
            ->where('booking_date', $date)
            ->whereIn('status', ['active', 'pending'])
            ->get(['type', 'token_number']);

        $result = [];
        foreach (['chemo', 'followup'] as $type) {
            $tokens = $booked->where('type', $type)->pluck('token_number')->toArray();

            $offlineCount = count(array_filter($tokens, fn($t) => $t % self::OFFLINE_STEP === 0));
            $onlineCount  = count($tokens) - $offlineCount;

            $result[$type] = [
                'online_booked'    => $onlineCount,
                'online_remaining' => self::MAX_ONLINE - $onlineCount,
                'offline_booked'   => $offlineCount,
                'offline_remaining'=> self::MAX_OFFLINE - $offlineCount,
                'total_booked'     => count($tokens),
                'total_remaining'  => self::MAX_TOKENS - count($tokens),
            ];
        }

        return $result;
    }

    // ── Private helpers ─────────────────────────────────────────────────────

    /**
     * Find the next available token number for the given source.
     *
     * Offline: multiples of 5 (5, 10, 15, …, 150)  — 30 slots
     * Online:  non-multiples of 5 (1,2,3,4,6,…)    — 120 slots
     *
     * Returns null if no slot is available.
     */
    private function findNextToken(string $source, array $bookedTokens): ?int
    {
        if ($source === 'offline') {
            for ($t = self::OFFLINE_STEP; $t <= self::MAX_TOKENS; $t += self::OFFLINE_STEP) {
                if (!in_array($t, $bookedTokens)) {
                    return $t;
                }
            }
            return null;
        }

        // Online: find lowest non-multiple-of-5 not yet booked
        for ($t = 1; $t <= self::MAX_TOKENS; $t++) {
            if ($t % self::OFFLINE_STEP !== 0 && !in_array($t, $bookedTokens)) {
                return $t;
            }
        }
        return null;
    }
}
