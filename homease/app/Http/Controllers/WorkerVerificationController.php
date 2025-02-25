<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkerAvailability;
use App\Models\WorkerVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WorkerVerificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'emergency_name' => 'required|string|max:255',
            'emergency_relationship' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'service_type' => 'required|string',
            'hourly_rate' => 'required|numeric',
            'experience' => 'required|integer',
            'specialization' => 'nullable|string|max:255',
            'work_locations' => 'required|string',
            'reference' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'gov_id' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'clearance' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'transportation' => 'required|in:Yes,No',
            'tools' => 'required|in:Yes,No',
            'agreed_terms' => 'required|accepted',
            'agreed_privacy_policy' => 'required|accepted',
        ]);

        $photoPath = $request->file('photo')->store('worker_photos', 'public');
        $govIdPath = $request->file('gov_id')->store('worker_gov_ids', 'public');
        $clearancePath = $request->file('clearance')->store('worker_clearances', 'public');

        WorkerVerification::create([
            'user_id' => Auth::id(),
            'emergency_name' => $request->emergency_name,
            'emergency_relationship' => $request->emergency_relationship,
            'emergency_phone' => $request->emergency_phone,
            'service_type' => $request->service_type,
            'hourly_rate' => $request->hourly_rate,
            'experience' => $request->experience,
            'specialization' => $request->specialization,
            'work_locations' => $request->work_locations,
            'reference' => $request->reference,
            'photo' => $photoPath,
            'gov_id' => $govIdPath,
            'clearance' => $clearancePath,
            'transportation' => $request->transportation,
            'tools' => $request->tools,
            'agreed_terms' => true,
            'agreed_privacy_policy' => true,
        ]);

        // Automatically create WorkerAvailability record with 'is_available' set to 'on'
        WorkerAvailability::updateOrCreate(
            ['worker_id' => Auth::id()], // If exists, update; if not, create
            ['is_available' => true] // Default availability to 'on'
        );

        return redirect()->route('worker.home')->with('success', 'Your form has been submitted successfully!');
    }
    public function showSecondVerification()
    {
        return view('worker.verifications.second-verification');
    }
}
