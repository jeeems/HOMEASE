<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Rating::create([
            'worker_id' => $request->worker_id,
            'client_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Rating submitted successfully.');
    }
}
