<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\WorkerAvailability;


class WorkerController extends Controller
{
    public function home()
    {
        // Get the logged-in worker's pending and ongoing bookings
        $bookings = Booking::where('worker_id', Auth::id())
            ->whereIn('status', ['pending', 'ongoing'])
            ->get();

        return view('worker.contents.worker-home', compact('bookings'));
    }

    public function showWorkers(Request $request)
    {
        $serviceType = $request->query('service_type');

        // Get only available workers who provide the selected service
        $workers = User::where('role', 'worker')
            ->whereHas('workerVerification', function ($query) use ($serviceType) {
                $query->where('service_type', $serviceType);
            })
            ->whereHas('workerAvailability', function ($query) {
                $query->where('is_available', true);
            })
            ->with(['workerVerification', 'workerAvailability']) // Load relationships
            ->get();

        return view('client.chosen_service.list', compact('workers', 'serviceType'));
    }


    public function toggleAvailability(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'worker') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Ensure a record exists
        if (!$user->workerAvailability) {
            WorkerAvailability::create([
                'worker_id' => $user->id,
                'is_available' => $request->is_available
            ]);
        } else {
            $user->workerAvailability->update(['is_available' => $request->is_available]);
        }

        return response()->json(['success' => true, 'message' => 'Availability updated']);
    }
}
