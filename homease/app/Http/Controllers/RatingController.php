<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'worker_id' => 'required|exists:users,id',
            'booking_id' => 'required|exists:bookings,id',
            'booking_title' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'review_photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $photos = [];
        if ($request->hasFile('review_photos')) {
            foreach ($request->file('review_photos') as $photo) {
                $path = $photo->store('reviews', 'public');
                $photos[] = $path;
            }
        }

        Rating::create([
            'worker_id' => $request->worker_id,
            'client_id' => Auth::id(),
            'booking_id' => $request->booking_id,
            'booking_title' => $request->booking_title,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'review_photos' => json_encode($photos),
        ]);

        return response()->json(['success' => true]);
    }
    public function show($id)
    {
        $rating = Rating::findOrFail($id);

        return response()->json([
            'rating' => $rating->rating,
            'comment' => $rating->comment,
            'review_photos' => json_decode($rating->review_photos, true) ?? []
        ]);
    }
}
