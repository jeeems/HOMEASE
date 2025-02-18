<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Check if the worker is authorized
        if (Auth::user()->id !== $booking->worker_id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Update the booking status
        $booking->status = $request->status;
        $booking->save();

        // If the worker accepts a booking, remove conflicting pending bookings
        if ($request->status == 'ongoing') {
            Booking::where('worker_id', Auth::user()->id)
                ->where('status', 'pending')
                ->where('scheduled_date', $booking->scheduled_date)
                ->delete();
        }

        return redirect()->back()->with('success', 'Booking updated successfully.');
    }
}
