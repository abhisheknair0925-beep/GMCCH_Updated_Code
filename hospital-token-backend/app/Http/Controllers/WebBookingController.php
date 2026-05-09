<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HospitalUnit;
use App\Models\Booking;
use App\Services\BookingService;
use App\Models\User;

class WebBookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of bookings.
     */
    public function index(Request $request)
    {
        $units = HospitalUnit::all();
        
        // Default to tomorrow if no date is provided
        $date = $request->input('date', Carbon::tomorrow()->toDateString());
        
        $query = Booking::with(['user', 'unit'])
            ->whereDate('booking_date', $date);

        // Apply filters
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $bookings = $query->orderBy('token_number', 'asc')->get();

        return view('booking.index', compact('bookings', 'units', 'date'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        $units = HospitalUnit::all();
        return view('booking.create', compact('units'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'crno' => 'required|string',
            'unit_id' => 'required|exists:hospital_units,id',
            'type' => 'required|in:chemo,normal',
        ]);

        try {
            $user = User::where('crno', $request->crno)->first();

            if (!$user) {
                return back()->withErrors(['crno' => 'User with this CR Number not found.'])->withInput();
            }

            $booking = $this->bookingService->createToken(
                $user->id, 
                $request->unit_id, 
                $request->type
            );

            return back()->with('success', "Booking successful! Token Number: {$booking->token_number}");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            if ($booking->status !== 'active') {
                return back()->withErrors(['error' => 'Only active bookings can be cancelled.']);
            }

            $booking->update(['status' => 'cancelled']);
            
            return back()->with('success', "Booking #{$booking->token_number} has been cancelled successfully.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to cancel booking.']);
        }
    }
}
