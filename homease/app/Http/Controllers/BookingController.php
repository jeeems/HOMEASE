<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Events\NewBookingEvent;
use App\Models\Profile;
use App\Models\User;

class BookingController extends Controller
{

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to view your bookings.');
        }

        // Load bookings with worker details including profile picture
        $bookings = Booking::where('client_id', Auth::id())
            ->with(['worker.profile'])
            ->orderBy('scheduled_date', 'desc')
            ->get();

        return view('client.bookings.my-bookings', compact('bookings'));
    }



    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'You need to be logged in to update the booking.');
        }

        $booking = Booking::findOrFail($id);

        if (Auth::user()->id !== $booking->worker_id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Update the booking status
        $booking->status = $request->status;
        $booking->save();

        // Remove conflicting pending bookings if status becomes ongoing
        if ($request->status == 'ongoing') {
            Booking::where('worker_id', Auth::user()->id)
                ->where('status', 'pending')
                ->where('scheduled_date', $booking->scheduled_date)
                ->delete();
        }

        return redirect()->back()->with('success', 'Booking updated successfully.');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'You need to be logged in to make a booking.');
        }

        $request->validate([
            'worker_id' => 'required|exists:users,id',
            'service_type' => 'required|string|in:Carpentry,Plumbing,Home Cleaning,Electrical',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $client = Auth::user();

        // Format date and time
        $bookingDate = $request->input('booking_date');
        $hour = $request->input('hour');
        $minute = $request->input('minute');
        $ampm = $request->input('ampm');

        // Convert to 24-hour format
        if ($ampm == 'PM' && $hour < 12) {
            $hour = $hour + 12;
        } else if ($ampm == 'AM' && $hour == 12) {
            $hour = 0;
        }

        // Format the time and create full datetime string
        $timeStr = sprintf('%02d:%02d:00', $hour, $minute);
        $scheduledDateTime = date('Y-m-d H:i:s', strtotime("$bookingDate $timeStr"));


        // Create booking
        $booking = Booking::create([
            'worker_id' => $request->worker_id,
            'client_id' => $client->id,
            'service_type' => $request->service_type,
            'scheduled_date' => $scheduledDateTime,
            'status' => 'pending',
            'client_address' => $request->address,
            'booking_title' => $request->title,
            'notes' => $request->description,
        ]);

        // Trigger real-time notification event
        event(new NewBookingEvent($booking));

        return redirect()->route('bookings.index')->with('success', 'Booking request sent successfully!');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking has been cancelled successfully.');
    }
}
