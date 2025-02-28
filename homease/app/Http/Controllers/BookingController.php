<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Events\NewBookingEvent;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Rating;
use Carbon\Carbon;

class BookingController extends Controller
{

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to view your bookings.');
        }

        // Fetch bookings along with worker details including profile picture
        $bookings = Booking::where('client_id', Auth::id())
            ->with(['worker.profile'])
            ->orderBy('scheduled_date', 'desc')
            ->get();

        // Check if there are bookings before querying workers
        if ($bookings->isEmpty()) {
            return redirect()->route('client.bookings.my-bookings')->with('error', 'No bookings found.');
        }

        // Fetch workers associated with the bookings
        $workerIds = $bookings->pluck('worker_id')->unique(); // Get unique worker IDs
        $workers = User::whereIn('id', $workerIds)->get()->keyBy('id'); // Fetch workers and index by ID

        return view('client.bookings.my-bookings', compact('bookings', 'workers'));
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

        // If completed, add completion details
        if ($request->status == 'completed') {
            $booking->completion_date = Carbon::now();

            // If hours_worked and total_amount were provided from the form
            if ($request->has('hours_worked')) {
                $booking->hours_worked = $request->hours_worked;
            }

            if ($request->has('total_amount')) {
                $booking->total_amount = $request->total_amount;
            }
        }

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
        try {
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'You need to be logged in to make a booking.');
            }

            // Log the incoming request data
            Log::info('Booking request data:', $request->all());

            $validated = $request->validate([
                'worker_id' => 'required|exists:users,id',
                'service_type' => 'required|string|in:Carpentry,Plumbing,Home Cleaning,Daycare,Electrician',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'address' => 'required|string',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            // Log the validated data
            Log::info('Validated booking data:', $validated);

            $client = Auth::user();

            // Debug date formatting
            Log::info('Date inputs:', [
                'booking_date' => $request->input('booking_date'),
                'hour' => $request->input('hour'),
                'minute' => $request->input('minute'),
                'ampm' => $request->input('ampm')
            ]);

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

            Log::info('Formatted date:', ['scheduledDateTime' => $scheduledDateTime]);

            // Create booking with NULL values for completion-related fields
            $booking = Booking::create([
                'worker_id' => $request->worker_id,
                'client_id' => $client->id,
                'service_type' => $request->service_type,
                'scheduled_date' => $scheduledDateTime,
                'status' => 'pending',
                'client_address' => $request->address,
                'booking_title' => $request->title,
                'notes' => $request->description,
                'completion_date' => null,
                'hours_worked' => null,
                'total_amount' => null
            ]);

            // Trigger real-time notification event
            event(new NewBookingEvent($booking));

            return response()->json([
                'success' => true,
                'message' => 'Booking request sent successfully!',
                'redirect' => route('bookings.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Booking failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCompletionDetails($id)
    {
        $booking = Booking::findOrFail($id);

        // Check authorization
        if (Auth::user()->id !== $booking->worker_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get worker's hourly rate
        $worker = $booking->worker;
        $workerVerification = $worker->workerVerification;
        $hourlyRate = $workerVerification ? $workerVerification->hourly_rate : 0;

        // Calculate hours worked (from scheduled time until now)
        $scheduledTime = Carbon::parse($booking->scheduled_date);
        $completionTime = Carbon::now();

        // Calculate difference in hours (round to nearest 0.25)
        $hoursWorked = $scheduledTime->diffInMinutes($completionTime) / 60;
        $hoursWorked = round($hoursWorked * 4) / 4; // Round to nearest 0.25

        // Calculate total amount
        $totalAmount = $hoursWorked * $hourlyRate;

        return response()->json([
            'booking_id' => $booking->id, // Include the booking ID
            'service_type' => $booking->service_type,
            'scheduled_date' => $scheduledTime->format('F d, Y h:i A'),
            'completion_time' => $completionTime->format('F d, Y h:i A'),
            'hours_worked' => $hoursWorked,
            'hourly_rate' => number_format($hourlyRate, 2),
            'total_amount' => number_format($totalAmount, 2)
        ]);
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

    public function storeRating(Request $request)
    {
        try {
            // Log the request data
            Log::info('Rating submission', $request->all());

            // Simple validation
            $validated = $request->validate([
                'worker_id' => 'required',
                'booking_id' => 'required',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            // Create the rating
            $rating = new Rating();
            $rating->worker_id = $request->worker_id;
            $rating->client_id = Auth::id();
            $rating->booking_id = $request->booking_id;
            $rating->booking_title = $request->booking_title ?? 'Untitled';
            $rating->rating = $request->rating;
            $rating->comment = $request->comment ?? '';
            $rating->review_photos = json_encode([]);
            $rating->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Rating submission error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getRatingDetails(Booking $booking)
    {
        // Check if user is authorized to view this booking
        if ($booking->client_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Get the rating
        $rating = Rating::where('booking_id', $booking->id)
            ->where('client_id', Auth::id())
            ->first();

        if (!$rating) {
            return response()->json(['success' => false, 'message' => 'Rating not found'], 404);
        }

        // Get worker details
        $worker = $booking->worker;

        // Format the response data
        $data = [
            'success' => true,
            'booking_id' => $booking->id,
            'booking_title' => $booking->booking_title,
            'worker_name' => $worker->full_name,
            'worker_profile_picture' => asset('storage/' . $worker->profile->profile_picture),
            'service_type' => $booking->service_type,
            'rating' => $rating->rating,
            'comment' => $rating->comment,
            'photos' => []
        ];

        // Add photos if any
        if ($rating->photos) {
            foreach (json_decode($rating->photos) as $photo) {
                $data['photos'][] = asset('storage/' . $photo);
            }
        }

        return response()->json($data);
    }
}
