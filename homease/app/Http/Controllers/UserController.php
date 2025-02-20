<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\WorkerVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function profile()
    {
        return view('profile.show', [
            'user' => Auth::user()
        ]);
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        // Validate input fields
        $request->validate([
            'full_name' => 'required|string',
            'service_type' => 'nullable|string',
            'experience' => 'nullable|numeric',
            'hourly_rate' => 'nullable|numeric',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Ensure the authenticated user is retrieved correctly
        $user = User::find(Auth::id());

        if (!$user) {
            return redirect()->back()->with('error', 'User not authenticated.');
        }

        DB::beginTransaction();

        try {
            // Split full name into first, middle, and last names
            $nameParts = explode(' ', trim($request->input('full_name')));
            $firstName = $nameParts[0] ?? null;
            $middleName = count($nameParts) > 2 ? $nameParts[1] : null;
            $lastName = $nameParts[count($nameParts) - 1] ?? null;

            // Update User model
            $user->first_name = $firstName;
            $user->middle_name = $middleName;
            $user->last_name = $lastName;
            $user->save(); // Ensure the User model is saved

            // Update or create worker verification record
            WorkerVerification::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'service_type' => $request->input('service_type'),
                    'experience' => $request->input('experience'),
                    'hourly_rate' => $request->input('hourly_rate'),
                ]
            );

            // Get or create the Profile model
            $profile = Profile::firstOrCreate(['user_id' => $user->id]);

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if it exists
                if ($profile->profile_picture) {
                    $oldPicturePath = public_path(ltrim($profile->profile_picture, '/'));
                    if (File::exists($oldPicturePath)) {
                        File::delete($oldPicturePath);
                    }
                }

                // Store new profile picture
                $imagePath = $request->file('profile_picture')->store('uploads/profile_pictures', 'public');

                // Update Profile model with new image path
                $profile->profile_picture = '/storage/' . $imagePath;
                $profile->save(); // Ensure the Profile model is saved
            }

            DB::commit();

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update profile. Please try again: ' . $e->getMessage());
        }
    }
}
