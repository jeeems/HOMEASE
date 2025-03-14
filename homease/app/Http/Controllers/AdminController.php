<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Booking;
use App\Models\Profile;
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
                ->limit(2)
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
                'filteredTotal',
                'topRatedWorkers',
                'recentRatings',
                'weeklyBookings'
            ));
        }

        return redirect()->route('admin.login');
    }

    public function users(Request $request)
    {
        // Get all users with their profiles, separated by role
        $clients = User::where('role', 'client')
            ->with('profile')
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10, ['*'], 'clients_page');

        $workers = User::where('role', 'worker')
            ->with('profile', 'workerVerification')
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10, ['*'], 'workers_page');

        $admins = User::where('role', 'admin')
            ->with('profile')
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10, ['*'], 'admins_page');

        return view('admin.users', compact('clients', 'workers', 'admins'));
    }

    public function showUser(User $user)
    {
        // Fetch additional user details based on role
        switch ($user->role) {
            case 'client':
                $details = $user->load('profile', 'clientBookings');
                break;
            case 'worker':
                $details = $user->load('profile', 'workerVerification', 'workerBookings', 'ratings');
                break;
            case 'admin':
                $details = $user->load('profile');
                break;
        }

        return view('admin.users.show', compact('details'));
    }

    public function bookings(Request $request)
    {
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

        // Eager load relationships and paginate
        $bookings = $query->with(['client', 'worker', 'client.profile', 'worker.profile'])
            ->latest()
            ->paginate(10);

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

    public function updateBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'service_type' => 'required',
            'status' => 'required',
            'description' => 'nullable',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'hours_worked' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'required',
            'client_address' => 'nullable',
            // Add any other fields you need to validate
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings.show', $booking->id)
            ->with('success', 'Booking updated successfully.');
    }

    public function ratings(Request $request)
    {
        // Create a query to fetch ratings with eager loading
        $query = Rating::with(['client', 'worker', 'worker.workerVerification']);

        // Filter by service type if provided
        if ($request->has('service') && $request->service !== 'all') {
            $query->whereHas('worker.workerVerification', function ($q) use ($request) {
                $q->where('service_type', $request->service);
            });
        }

        // Sorting options
        $sortBy = $request->input('sort', 'latest');
        switch ($sortBy) {
            case 'highest_rated':
                $query->orderByDesc('rating');
                break;
            case 'lowest_rated':
                $query->orderBy('rating');
                break;
            case 'oldest':
                $query->orderBy('created_at');
                break;
            default: // latest
                $query->orderByDesc('created_at');
        }

        // Paginate the results
        $ratings = $query->paginate(10);

        $defaultServiceTypes = collect(['Carpentry', 'Plumbing', 'Home Cleaning', 'Daycare', 'Electrician']);

        $serviceTypes = User::whereHas('workerVerification')
            ->with('workerVerification')
            ->get()
            ->pluck('workerVerification.service_type')
            ->unique()
            ->merge($defaultServiceTypes)
            ->unique()
            ->values();

        return view('admin.ratings', [
            'ratings' => $ratings,
            'serviceTypes' => $serviceTypes,
            'selectedService' => $request->input('service', 'all'),
            'selectedSort' => $sortBy
        ]);
    }

    public function reports()
    {
        try {
            // Calculate total revenue and growth
            $totalRevenue = Booking::where('status', 'completed')->sum('total_amount') ?? 0;
            $previousRevenue = Booking::where('status', 'completed')
                ->where('created_at', '<', now()->subDays(30))
                ->sum('total_amount') ?? 0;
            $revenueGrowth = $previousRevenue > 0
                ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100
                : 0;

            // Calculate total bookings and growth
            $totalBookings = Booking::count() ?? 0;
            $previousBookings = Booking::where('created_at', '<', now()->subDays(30))->count() ?? 0;
            $bookingsGrowth = $previousBookings > 0
                ? (($totalBookings - $previousBookings) / $previousBookings) * 100
                : 0;

            // Calculate average rating
            $totalRatings = Rating::count() ?? 0;
            $averageRating = Rating::avg('rating') ?? 0;

            // Calculate active workers and growth
            $activeWorkers = User::whereHas('workerVerification')->count() ?? 0;
            $previousWorkers = User::whereHas('workerVerification')
                ->where('created_at', '<', now()->subDays(30))
                ->count() ?? 0;
            $workersGrowth = $previousWorkers > 0
                ? (($activeWorkers - $previousWorkers) / $previousWorkers) * 100
                : 0;

            // Get revenue trend data for the last 30 days with all dates filled
            $startDate = now()->subDays(30)->startOfDay();
            $endDate = now()->endOfDay();

            // Generate date range
            $dateRange = collect();
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateRange->push(['date' => $date->format('Y-m-d'), 'amount' => 0]);
            }

            // Get actual revenue data
            $revenueData = Booking::where('status', 'completed')
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as amount')
                ->groupBy('date')
                ->get()
                ->keyBy('date')
                ->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'amount' => (float)$item->amount
                    ];
                });

            // Merge date range with actual data
            $revenueTrend = $dateRange->map(function ($item) use ($revenueData) {
                if (isset($revenueData[$item['date']])) {
                    $item['amount'] = $revenueData[$item['date']]['amount'];
                }
                return $item;
            })->values();

            // Get service distribution data with error handling
            $serviceDistribution = Booking::selectRaw('COALESCE(service_type, "Other") as type, COUNT(*) as count')
                ->groupBy('service_type')
                ->get();

            if ($serviceDistribution->isEmpty()) {
                $serviceDistribution = collect([
                    ['type' => 'No Data', 'count' => 1]
                ]);
            } else {
                $serviceDistribution = $serviceDistribution->map(function ($item) {
                    return [
                        'type' => $item->type ?: 'Other',
                        'count' => (int)$item->count
                    ];
                });
            }

            // Get booking status distribution with default values for all statuses
            $bookingStatuses = ['completed', 'ongoing', 'pending', 'cancelled'];
            $bookingStatusCounts = Booking::selectRaw('status, COUNT(*) as count')
                ->whereIn('status', $bookingStatuses)
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $bookingStatus = [];
            foreach ($bookingStatuses as $status) {
                $bookingStatus[$status] = $bookingStatusCounts[$status] ?? 0;
            }

            // If all are zero, add dummy data
            if (array_sum($bookingStatus) == 0) {
                $bookingStatus = [
                    'completed' => 1,
                    'ongoing' => 1,
                    'pending' => 1,
                    'cancelled' => 1
                ];
            }

            // Prepare validation messages
            $validationMessages = [];

            // Get top performing workers
            $topWorkers = User::whereHas('workerVerification')
                ->withCount(['workerBookings as completed_bookings_count' => function ($query) {
                    $query->where('status', 'completed');
                }])
                ->withAvg('ratings as average_rating', 'rating')
                ->withSum(['workerBookings as total_revenue' => function ($query) {
                    $query->where('status', 'completed');
                }], 'total_amount')
                ->orderByDesc('total_revenue')
                ->take(5)
                ->get();

            // Calculate customer satisfaction metrics
            $satisfactionRate = $totalRatings > 0
                ? (Rating::where('rating', '>=', 4)->count() / $totalRatings) * 100
                : 0;

            $completionRate = $totalBookings > 0
                ? (Booking::where('status', 'completed')->count() / $totalBookings) * 100
                : 0;

            // Get rating distribution
            $ratingDistribution = array_fill(1, 5, 0);
            $ratings = Rating::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->get();

            foreach ($ratings as $rating) {
                if (isset($ratingDistribution[$rating->rating])) {
                    $ratingDistribution[$rating->rating] = $rating->count;
                }
            }

            // Get service analysis
            $serviceAnalysis = Booking::selectRaw('
            COALESCE(service_type, "Other") as type,
            COUNT(*) as bookings,
            SUM(total_amount) as revenue
        ')
                ->groupBy('service_type')
                ->get()
                ->map(function ($service) {
                    $avgRating = Rating::whereHas('booking', function ($query) use ($service) {
                        $query->where('service_type', $service->type);
                    })->avg('rating') ?? 0;

                    return [
                        'type' => $service->type ?: 'Other',
                        'bookings' => (int)$service->bookings,
                        'revenue' => (float)$service->revenue,
                        'average_rating' => (float)$avgRating
                    ];
                });

            // Log processed data for debugging
            Log::info('Revenue Trend Data Count: ' . count($revenueTrend));
            Log::info('Service Distribution Data Count: ' . count($serviceDistribution));
            Log::info('Booking Status Count: ' . count($bookingStatus));

            // Return view with all calculated data
            return view('admin.reports', [
                'totalRevenue' => $totalRevenue,
                'revenueGrowth' => $revenueGrowth,
                'totalBookings' => $totalBookings,
                'bookingsGrowth' => $bookingsGrowth,
                'averageRating' => $averageRating,
                'totalRatings' => $totalRatings,
                'activeWorkers' => $activeWorkers,
                'workersGrowth' => $workersGrowth,
                'revenueTrend' => $revenueTrend,
                'serviceDistribution' => $serviceDistribution,
                'bookingStatus' => $bookingStatus,
                'topWorkers' => $topWorkers,
                'satisfactionRate' => $satisfactionRate,
                'completionRate' => $completionRate,
                'ratingDistribution' => $ratingDistribution,
                'serviceAnalysis' => $serviceAnalysis,
                'validationMessages' => $validationMessages
            ]);
        } catch (\Exception $e) {
            Log::error('Error in reports method: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return view('admin.reports', [
                'error' => $e->getMessage(),
                'validationMessages' => ['An error occurred while generating reports: ' . $e->getMessage()]
            ]);
        }
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function workers(Request $request)
    {
        $sort = $request->query('sort', 'rating_desc'); // Default to highest rating

        $query = User::whereHas('workerVerification')
            ->withCount('ratings')
            ->withAvg('ratings as average_rating', 'rating');

        switch ($sort) {
            case 'rating_asc':
                $query->orderBy('average_rating', 'asc');
                break;
            case 'rating_desc':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'reviews_asc':
                $query->orderBy('ratings_count', 'asc');
                break;
            case 'reviews_desc':
                $query->orderBy('ratings_count', 'desc');
                break;
            default:
                $query->orderByDesc('average_rating'); // Default sorting
        }

        $topRatedWorkers = $query->take(5)->get();

        return view('admin.workers', compact('topRatedWorkers'));
    }


    public function showWorker(User $worker)
    {
        $profile = Profile::where('user_id', $worker->id)->firstOrFail();

        // Fetch client ratings
        $reviews = Rating::where('worker_id', $worker->id)->latest()->get();

        // Get the clients who left reviews
        $clientProfiles = [];
        foreach ($reviews as $review) {
            $clientProfiles[$review->client_id] = Profile::where('user_id', $review->client_id)->first();
        }

        // Calculate average rating
        $averageRating = $reviews->avg('rating');

        return view('admin.workers.show', compact('worker', 'profile', 'reviews', 'clientProfiles', 'averageRating'));
    }

    public function ajaxBookings(Request $request)
    {
        $query = Booking::query();

        // Apply status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Apply search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                    ->orWhereHas('client', function ($clientQuery) use ($searchTerm) {
                        $clientQuery->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%")
                            ->orWhere('email', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere('service_type', 'like', "%{$searchTerm}%")
                    ->orWhereHas('worker', function ($workerQuery) use ($searchTerm) {
                        $workerQuery->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Eager load relationships and paginate
        $bookings = $query->with(['client:id,first_name,last_name,email', 'worker:id,first_name,last_name'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $bookings->items(),
            'current_page' => $bookings->currentPage(),
            'from' => $bookings->firstItem(),
            'to' => $bookings->lastItem(),
            'total' => $bookings->total(),
            'last_page' => $bookings->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        // Set validation rules based on role
        $rules = [
            'role' => 'required|in:client,worker,admin',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birth_month' => 'required|integer|min:1|max:12',
            'birth_day' => 'required|integer|min:1|max:31',
            'birth_year' => 'required|integer|min:1900|max:' . (date('Y') - 18),
        ];

        // Add required fields based on role
        if ($request->input('role') !== 'admin') {
            $rules = array_merge($rules, [
                'gender' => 'required|in:male,female',
                'phone' => 'required|string|max:15|unique:users',
                'street' => 'required|string|max:255',
                'barangay' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',
            ]);
        }

        // Validate request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create birthdate from components
            $birthdate = "{$request->birth_year}-{$request->birth_month}-{$request->birth_day}";

            // Prepare user data - adjust these fields based on your actual User model
            $userData = [
                'role' => $request->role,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'birthdate' => $birthdate,
            ];

            // Add email_verified_at if provided
            if ($request->has('email_verified_at')) {
                $userData['email_verified_at'] = now();
            }

            // Add additional fields for non-admin roles
            if ($request->role !== 'admin') {
                $userData = array_merge($userData, [
                    'gender' => $request->gender,
                    'phone' => $request->phone,
                ]);
            }

            // Create user
            $user = User::create($userData);

            // If user has address fields and they're not in the users table,
            // store them separately in a profile or address table if needed
            if ($request->role !== 'admin' && !in_array('street', $user->getFillable())) {
                // Assuming you have a Profile model or similar to store address
                $addressData = [
                    'user_id' => $user->id,
                    'street' => $request->street,
                    'barangay' => $request->barangay,
                    'city' => $request->city,
                    'zip_code' => $request->zip_code,
                ];

                // Create profile with address data
                // Comment this out if you don't have a Profile model
                // Profile::create($addressData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }
}
