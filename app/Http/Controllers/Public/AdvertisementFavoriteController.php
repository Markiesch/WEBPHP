<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use App\Models\AdvertisementReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdvertisementFavoriteController extends Controller
{
    public function store(Request $request, $id): RedirectResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        $userId = auth()->id();
        $value = $request->input('value') ?? false;

        if ($value) {
            AdvertisementFavorite::create([
                'user_id' => $userId,
                'advertisement_id' => $advertisement->id,
            ]);
        } else {
            AdvertisementFavorite::where('user_id', $userId)
                ->where('advertisement_id', $advertisement->id)
                ->delete();
        }

        return redirect()->back()->with('success', 'Favorite status updated successfully.');
    }
}
