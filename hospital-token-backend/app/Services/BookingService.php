<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class BookingService
{
    /**
     * Create a new booking with concurrency safety and legacy rules.
     *
     * @param int $userId
     * @param int $unitId
     * @param string $type
     * @return Booking
     * @throws Exception
     */
    public function createToken(int $userId, int $unitId, string $type): Booking
    {
        $bookingDate = Carbon::tomorrow()->toDateString();

        // 3 represents the number of times to retry the transaction if a deadlock occurs
        return DB::transaction(function () use ($userId, $unitId, $type, $bookingDate) {
            // 1. Global Rule: User can only book ONCE per day (any type or unit)
            $existingBooking = Booking::where('user_id', $userId)
                ->where('booking_date', $bookingDate)
                ->whereIn('status', ['active', 'pending'])
                ->first();

            if ($existingBooking) {
                throw new Exception('Already booked. You can only hold one active token per day.');
            }

            // 2. Capacity Rule
            $maxLimit = ($type === 'chemo') ? 30 : 70;
            
            $currentCount = Booking::where('unit_id', $unitId)
                ->where('type', $type)
                ->where('booking_date', $bookingDate)
                ->lockForUpdate()
                ->count();

            if ($currentCount >= $maxLimit) {
                throw new Exception("Booking full for {$type}. Max {$maxLimit} tokens allowed per day.");
            }

            // 3. Token Start Logic
            $lastToken = Booking::where('unit_id', $unitId)
                ->where('type', $type)
                ->where('booking_date', $bookingDate)
                ->lockForUpdate()
                ->max('token_number');

            $nextTokenRaw = $lastToken ? $lastToken + 1 : (($type === 'chemo') ? 11 : 71);

            // 4. Token Slot Logic
            $nextToken = $this->calculateToken($nextTokenRaw);

            try {
                // 5. Check auto-approve setting
                $hospital = \App\Models\Hospital::first();
                $isAutoApprove = $hospital && $hospital->auto_approve_bookings_until && Carbon::parse($hospital->auto_approve_bookings_until)->isFuture();
                
                $status = $isAutoApprove ? 'active' : 'pending';

                // 6. Create Booking
                return Booking::create([
                    'user_id' => $userId,
                    'unit_id' => $unitId,
                    'type' => $type,
                    'token_number' => $nextToken,
                    'booking_date' => $bookingDate,
                    'status' => $status,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // Error code 23000 is for unique constraint violations (Duplicate Entry)
                // Even with lockForUpdate, handling the exception is the ultimate safeguard
                if ($e->getCode() == 23000) {
                    throw new Exception('High traffic detected. Please try again in a few seconds.');
                }
                throw $e;
            }
        }, 5); // Retry transaction up to 5 times for deadlocks
    }

    /**
     * The legacy token format logic. 
     * Converts sequential input into the skipped slot format.
     */
    private function calculateToken(int $input): int
    {
        $slot = (int)($input / 10);
        
        if ($slot % 2 === 0) {
            if ($input % 5 !== 0) {
                return $input + 10;
            }
            return $input;
        }
        
        return $input;
    }
}
