<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\AdvertisementReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdvertisementReviewController extends Controller
{
    public function store(Request $request, $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        AdvertisementReview::create([
            'user_id' => auth()->id(),
            'advertisement_id' => $advertisement->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }
}
