<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\HospitalUnit;
use Carbon\Carbon;

class QueueController extends Controller
{
    /**
     * Display the queue screen for a specific unit.
     */
    public function index($unit_id)
    {
        $unit = HospitalUnit::findOrFail($unit_id);
        $date = Carbon::tomorrow()->toDateString();

        // Get all tomorrow's bookings for this unit
        $bookings = Booking::with('user')
            ->where('unit_id', $unit_id)
            ->whereDate('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('token_number', 'asc')
            ->get();

        // Find the first active token (Current Token)
        $currentToken = $bookings->where('status', 'active')->first();
        
        // Upcoming tokens
        $upcoming = $bookings->where('status', 'active')->skip($currentToken ? 1 : 0);

        return view('queue.index', compact('unit', 'date', 'currentToken', 'upcoming', 'bookings'));
    }

    /**
     * AJAX: Call the next patient in the queue.
     */
    public function callNext(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:hospital_units,id'
        ]);

        $date = Carbon::tomorrow()->toDateString();

        // 1. Find the current active token
        $currentToken = Booking::where('unit_id', $request->unit_id)
            ->whereDate('booking_date', $date)
            ->where('status', 'active')
            ->orderBy('token_number', 'asc')
            ->first();

        if ($currentToken) {
            // 2. Mark as completed
            $currentToken->update(['status' => 'completed']);
        }

        // 3. Find the NEW current token (next one)
        $nextToken = Booking::with('user')
            ->where('unit_id', $request->unit_id)
            ->whereDate('booking_date', $date)
            ->where('status', 'active')
            ->orderBy('token_number', 'asc')
            ->first();

        // 4. Get updated upcoming list
        $upcoming = Booking::with('user')
            ->where('unit_id', $request->unit_id)
            ->whereDate('booking_date', $date)
            ->where('status', 'active')
            ->where('id', '!=', $nextToken ? $nextToken->id : 0)
            ->orderBy('token_number', 'asc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'currentToken' => $nextToken ? [
                'token_number' => $nextToken->token_number,
                'patient_name' => $nextToken->user->name,
                'type' => $nextToken->type,
                'slot_time' => $nextToken->slot_time
            ] : null,
            'upcoming' => $upcoming->map(function($b) {
                return [
                    'token_number' => $b->token_number,
                    'patient_name' => $b->user->name,
                    'slot_time' => $b->slot_time,
                    'type' => $b->type
                ];
            })
        ]);
    }
}
