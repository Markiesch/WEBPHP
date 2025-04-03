<?php

namespace App\Http\Services\Public;

use App\Models\Advertisement;
use App\Models\AdvertisementReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementReviewService
{
    public function store(Request $request, int $id): void
    {
        $advertisement = Advertisement::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        AdvertisementReview::create([
            'user_id' => Auth::id(),
            'advertisement_id' => $advertisement->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);
    }

    public function delete(int $advertisementId, int $reviewId): bool
    {
        $review = AdvertisementReview::where('id', $reviewId)
            ->where('advertisement_id', $advertisementId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return $review->delete();
    }
}
