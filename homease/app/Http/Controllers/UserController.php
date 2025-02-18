<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\WorkerVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        return view('profile.show');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'service_type' => 'nullable|string',
            'experience' => 'nullable|numeric',
            'hourly_rate' => 'nullable|numeric',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Split and update name
        $nameParts = explode(' ', $request->input('full_name'));
        User::where('id', $user->id)->update([
            'first_name' => $nameParts[0],
            'middle_name' => isset($nameParts[1]) ? $nameParts[1] : null,
            'last_name' => isset($nameParts[count($nameParts) - 1]) ? $nameParts[count($nameParts) - 1] : null,
        ]);

        // Update or create worker verification
        WorkerVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'service_type' => $request->input('service_type'),
                'experience' => $request->input('experience'),
                'hourly_rate' => $request->input('hourly_rate'),
            ]
        );

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            $profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');

            Profile::updateOrCreate(
                ['user_id' => $user->id],
                ['profile_picture' => $profile_picture]
            );
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
