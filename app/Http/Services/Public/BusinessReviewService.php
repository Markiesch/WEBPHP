<?php

namespace App\Http\Services\Public;

use App\Models\Business;
use App\Models\BusinessReview;
use Illuminate\Http\Request;

class BusinessReviewService
{
    public function store(Request $request, $businessId): void
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'content' => 'required|string|min:10|max:1000',
        ]);

        $business = Business::findOrFail($businessId);

        BusinessReview::create([
            'business_id' => $business->id,
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'content' => $validated['content'],
        ]);
    }

    public function delete($businessId, $reviewId): void
    {
        $review = BusinessReview::where('business_id', $businessId)
            ->where('id', $reviewId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $review->delete();
    }
}
