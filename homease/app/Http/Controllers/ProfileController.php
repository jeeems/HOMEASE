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

        if ($user->role == 'worker') {
            // Load reviews for this worker
            $reviews = Rating::where('worker_id', $user->id)
                ->with('client')
                ->paginate(5);

            // Calculate average rating
            $averageRating = Rating::where('worker_id', $user->id)->avg('rating') ?: 0;

            // Get client profiles for display
            $clientProfiles = [];
            foreach ($reviews as $review) {
                if ($review->client_id) {
                    $profile = Profile::where('user_id', $review->client_id)->first();
                    if ($profile) {
                        $clientProfiles[$review->client_id] = $profile;
                    }
                }
            }

            return view('profile.show', compact('reviews', 'averageRating', 'clientProfiles'));
        }

        if ($user->role == 'client') {
            // Fetch reviews where the logged-in user (client) is the one who made the booking
            $reviews = Rating::where('client_id', $user->id)
                ->with(['worker']) // Load worker profile
                ->paginate(5);

            $workerProfiles = [];
            foreach ($reviews as $review) {
                if ($review->client_id) {
                    $profile = Profile::where('user_id', $review->client_id)->first();
                    if ($profile) {
                        $workerProfiles[$review->client_id] = $profile;
                    }
                }
            }

            return view('profile.show', compact('reviews'));
        }

        return view('profile.show');
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

                        // Also delete from the second location
                        $oldAppPicturePath = storage_path('app/public/' . $profile->profile_picture);
                        if (File::exists($oldAppPicturePath)) {
                            File::delete($oldAppPicturePath);
                        }
                    }

                    $image = str_replace('data:image/png;base64,', '', $imageData);
                    $image = str_replace(' ', '+', $image);
                    $imageName = time() . '_' . $user->id . '.png';

                    // Path 1: Save to storage/app/public/profile_pictures
                    $uploadPath1 = storage_path('app/public/profile_pictures/');
                    if (!File::exists($uploadPath1)) {
                        File::makeDirectory($uploadPath1, 0755, true);
                    }
                    File::put($uploadPath1 . $imageName, base64_decode($image));

                    // Path 2: Save to public/storage/profile_pictures
                    $uploadPath2 = public_path('storage/profile_pictures/');
                    if (!File::exists($uploadPath2)) {
                        File::makeDirectory($uploadPath2, 0755, true);
                    }
                    File::put($uploadPath2 . $imageName, base64_decode($image));

                    // Update profile picture path (keep the original path format)
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

        // Fetch client ratings
        $reviews = Rating::where('worker_id', $user->id)->latest()->get();

        // Get the clients who left reviews
        $clientProfiles = [];
        foreach ($reviews as $review) {
            $clientProfiles[$review->client_id] = Profile::where('user_id', $review->client_id)->first();
        }

        // Calculate average rating
        $averageRating = $reviews->avg('rating');

        return view('profile.view-worker-profile', compact('user', 'profile', 'reviews', 'averageRating', 'clientProfiles'));
    }
}
