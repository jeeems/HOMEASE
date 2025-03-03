<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Booking;
use App\Models\Rating;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Initialize the booking query
            $query = Booking::query();

            // Apply status filter if it exists in the request
            if ($request->has('status') && $request->status != 'all') {
                $query->where('status', $request->status);
            }

            // Apply search if it exists
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    // Search by booking ID
                    $q->where('id', 'like', "%{$searchTerm}%")
                        // Or join with client and search by client name or email
                        ->orWhereHas('client', function ($clientQuery) use ($searchTerm) {
                            $clientQuery->where('first_name', 'like', "%{$searchTerm}%")
                                ->orWhere('last_name', 'like', "%{$searchTerm}%")
                                ->orWhere('email', 'like', "%{$searchTerm}%");
                        })
                        // Or search by service type
                        ->orWhere('service_type', 'like', "%{$searchTerm}%")
                        // Or join with worker and search by worker name
                        ->orWhereHas('worker', function ($workerQuery) use ($searchTerm) {
                            $workerQuery->where('first_name', 'like', "%{$searchTerm}%")
                                ->orWhere('last_name', 'like', "%{$searchTerm}%");
                        });
                });
            }

            // Now get the bookings with the applied filters/search
            $recentBookings = $query->with(['client', 'worker'])->latest()->paginate(5);

            // The rest of your dashboard code remains the same
            $totalUsers = User::whereIn('role', ['client', 'worker'])->count();
            $activeBookings = Booking::where('status', 'ongoing')->count();
            $pendingRequests = Booking::where('status', 'pending')->count();
            $totalBookings = Booking::count();

            // Get the actual number of filtered bookings for the counter
            if ($request->has('status') && $request->status != 'all') {
                $filteredTotal = Booking::where('status', $request->status)->count();
            } else {
                $filteredTotal = $totalBookings;
            }

            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    // Search by booking ID
                    $q->where('id', 'like', "%{$searchTerm}%")
                        // Or join with client and search by client name or email
                        ->orWhereHas('client', function ($clientQuery) use ($searchTerm) {
                            $clientQuery->where('first_name', 'like', "%{$searchTerm}%")
                                ->orWhere('last_name', 'like', "%{$searchTerm}%")
                                ->orWhere('email', 'like', "%{$searchTerm}%");
                        })
                        // Or search by service type
                        ->orWhere('service_type', 'like', "%{$searchTerm}%")
                        // Or join with worker and search by worker name
                        ->orWhereHas('worker', function ($workerQuery) use ($searchTerm) {
                            $workerQuery->where('first_name', 'like', "%{$searchTerm}%")
                                ->orWhere('last_name', 'like', "%{$searchTerm}%");
                        });
                });
            }

            // Historical data for comparisons
            $lastMonth = now()->subMonth();
            $lastWeek = now()->subWeek();
            $yesterday = now()->subDay();

            $previousMonthUsers = User::where('created_at', '<', $lastMonth)->count();
            $previousWeekActiveBookings = Booking::where('status', 'ongoing')
                ->where('created_at', '<', $lastWeek)->count();
            $yesterdayPendingRequests = Booking::where('status', 'pending')
                ->where('created_at', '<', $yesterday)->count();

            // Revenue calculations
            $totalRevenue = Booking::where('status', 'completed')->sum('total_amount');
            $lastMonthRevenue = Booking::where('status', 'completed')
                ->where('created_at', '<', $lastMonth)->sum('total_amount');

            // Top rated workers
            $topRatedWorkers = User::whereHas('workerVerification')
                ->withCount('ratings')
                ->withAvg('ratings as average_rating', 'rating')
                ->orderByDesc('average_rating')
                ->take(5)
                ->get();

            // Recent ratings
            $recentRatings = Rating::with(['client', 'worker'])
                ->with([
                    'client:id,first_name,last_name',
                    'worker:id,first_name,last_name',
                    'worker.workerVerification:id,user_id,service_type'
                ])
                ->orderByDesc('created_at')
                ->take(3)
                ->get();

            // Weekly booking statistics for the chart
            $weeklyBookings = [
                'ongoing' => [],
                'completed' => [],
                'cancelled' => [],
                'pending' => [] // Add pending bookings
            ];

            $startOfWeek = now()->startOfWeek();

            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $nextDate = $date->copy()->addDay();

                $weeklyBookings['ongoing'][] = Booking::where('status', 'ongoing')
                    ->whereBetween('created_at', [$date, $nextDate])->count();
                $weeklyBookings['completed'][] = Booking::where('status', 'completed')
                    ->whereBetween('updated_at', [$date, $nextDate])->count();
                $weeklyBookings['cancelled'][] = Booking::where('status', 'cancelled')
                    ->whereBetween('updated_at', [$date, $nextDate])->count();
                $weeklyBookings['pending'][] = Booking::where('status', 'pending') // Add pending bookings
                    ->whereBetween('created_at', [$date, $nextDate])->count();
            }

            // Make sure to pass the filtered total to the view
            return view('admin.dashboard', compact(
                'totalUsers',
                'activeBookings',
                'pendingRequests',
                'recentBookings',
                'previousMonthUsers',
                'previousWeekActiveBookings',
                'yesterdayPendingRequests',
                'totalRevenue',
                'lastMonthRevenue',
                'totalBookings',
                'filteredTotal', // Add this for accurate counting
                'topRatedWorkers',
                'recentRatings',
                'weeklyBookings'
            ));
        }

        return redirect()->route('admin.login');
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function bookings()
    {
        $bookings = Booking::all();
        return view('admin.bookings', compact('bookings'));
    }

    public function createBooking()
    {
        return view('admin.bookings.create');
    }

    public function showBooking(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function editBooking(Booking $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }

    public function approveBooking(Booking $booking)
    {
        $booking->status = 'ongoing';
        $booking->save();

        return redirect()->route('admin.bookings')->with('success', 'Booking approved successfully.');
    }

    public function cancelBooking(Booking $booking)
    {
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('admin.bookings')->with('success', 'Booking cancelled successfully.');
    }

    // public function services()
    // {
    //     // Your commented out code can be uncommented
    //     $services = Service::all();
    //     return view('admin.services', compact('services'));
    // }

    public function ratings()
    {
        $ratings = Rating::with(['client', 'worker'])->get();
        return view('admin.ratings', compact('ratings'));
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function workers()
    {
        $workers = User::whereHas('workerVerification')->get();
        return view('admin.workers', compact('workers'));
    }

    public function showWorker(User $worker)
    {
        return view('admin.workers.show', compact('worker'));
    }
}
