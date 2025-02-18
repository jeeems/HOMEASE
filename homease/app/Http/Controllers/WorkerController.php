<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


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

        // Get workers who provide the selected service
        $workers = User::where('role', 'worker')
            ->whereHas('workerVerification', function ($query) use ($serviceType) {
                $query->where('service_type', $serviceType);
            })
            ->with('workerVerification') // Load verification details
            ->get();

        return view('client.chosen_service.list', compact('workers', 'serviceType'));
    }
}
