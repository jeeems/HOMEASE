<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\WorkerVerification;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();
        return view('profile.show', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('error', 'User not authenticated.');
        }

        DB::beginTransaction();

        try {
            // Update user details
            User::where('id', Auth::id())->update([
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
            ]);

            // Get or create profile
            $profile = Profile::firstOrCreate(['user_id' => $user->id]);

            // Handle profile picture upload
            if ($request->has('profile_picture')) {
                $imageData = $request->input('profile_picture');

                if (strpos($imageData, 'data:image') === 0) {
                    // Delete old profile picture if it exists
                    if ($profile->profile_picture) {
                        $oldPicturePath = public_path('storage/' . $profile->profile_picture);
                        if (File::exists($oldPicturePath)) {
                            File::delete($oldPicturePath);
                        }
                    }

                    $image = str_replace('data:image/png;base64,', '', $imageData);
                    $image = str_replace(' ', '+', $image);
                    $imageName = time() . '_' . $user->id . '.png';

                    // Ensure directory exists
                    $uploadPath = storage_path('app/public/profile_pictures/');
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0755, true);
                    }

                    // Save new file
                    File::put($uploadPath . $imageName, base64_decode($image));

                    // Update profile picture path
                    $profile->profile_picture = 'profile_pictures/' . $imageName;
                }
            }

            if ($user->role == 'worker') {
                WorkerVerification::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'service_type' => $request->service_type,
                        'experience' => $request->experience,
                        'hourly_rate' => $request->hourly_rate,
                    ]
                );
            }

            // Save profile changes
            $profile->save();

            DB::commit();

            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to update profile. Please try again: ' . $e->getMessage());
        }
    }
    public function viewWorkerProfile(User $user)
    {
        $profile = Profile::where('user_id', $user->id)->firstOrFail();

        // Fetch ratings and reviews for the worker
        $reviews = Rating::where('worker_id', $user->id)->latest()->get();

        return view('profile.view-worker-profile', compact('user', 'profile', 'reviews'));
    }
}
