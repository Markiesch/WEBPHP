<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BusinessReviewController extends Controller
{
    public function store(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000'
        ]);

        BusinessReview::create([
            'business_id' => $business->id,
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'content' => $validated['content']
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function delete(Business $business, BusinessReview $review): RedirectResponse
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}
