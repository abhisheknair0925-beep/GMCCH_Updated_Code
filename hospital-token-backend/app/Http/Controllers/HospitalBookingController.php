<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Hospital;
use Carbon\Carbon;

class HospitalBookingController extends Controller
{
    private function checkAccess(Request $request)
    {
        if (!$request->user() instanceof Hospital) {
            abort(403, 'Unauthorized. Admin access only.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAccess($request);

        $bookings = Booking::with(['user', 'unit.doctor'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $this->checkAccess($request);

        $request->validate([
            'status' => 'required|in:pending,active,cancelled,completed'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'data' => $booking
        ]);
    }

    public function getSettings(Request $request)
    {
        $this->checkAccess($request);

        $hospital = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'auto_approve_bookings_until' => $hospital->auto_approve_bookings_until
            ]
        ]);
    }

    public function updateAutoApprove(Request $request)
    {
        $this->checkAccess($request);

        $request->validate([
            'hours' => 'required|integer|min:0|max:168' // 0 turns it off, max 7 days
        ]);

        $hospital = $request->user();

        if ($request->hours > 0) {
            $hospital->auto_approve_bookings_until = Carbon::now()->addHours($request->hours);
        } else {
            $hospital->auto_approve_bookings_until = null;
        }

        $hospital->save();

        return response()->json([
            'success' => true,
            'message' => 'Auto-approve settings updated successfully',
            'data' => [
                'auto_approve_bookings_until' => $hospital->auto_approve_bookings_until
            ]
        ]);
    }
}
