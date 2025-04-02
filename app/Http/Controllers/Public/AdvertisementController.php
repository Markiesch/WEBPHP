<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function advertisements(Request $request): View
    {
        $advertisements = Advertisement::query();

        // Search by name (title)
        if ($request->filled('search')) {
            $advertisements->where('title', 'like', '%' . $request->search . '%');
        }

        $min_price = Advertisement::min('price') ?: 0;
        $max_price = Advertisement::max('price') ?: 1000;

        // Filter by price range
        if ($request->filled('price_range')) {
            $advertisements->whereBetween('price', [$request->price_range[0], $request->price_range[1]]);
        }

        // Default sorting
        $advertisements->orderBy('created_at', 'desc');
        // Sort results
        if ($request->filled('sort')) {
            $sort = explode('_', $request->sort);
            if (count($sort) === 2 && in_array($sort[0], ['date', 'price']) && in_array($sort[1], ['asc', 'desc'])) {
                $column = $sort[0] === 'date' ? 'created_at' : 'price';
                $advertisements->orderBy($column, $sort[1]);
            }
        }

        return view('public/advertisements', [
            'advertisements' => $advertisements->paginate(9)->withQueryString(),
            'min_price' => $min_price,
            'max_price' => $max_price,
            'current_min' => ($request->price_range ? $request->price_range[0] : Advertisement::min('price')) ?: 0,
            'current_max' => ($request->price_range ? $request->price_range[1] : Advertisement::max('price')) ?: 0,
//            'current_max' => $request->price_range[1] ?: Advertisement::max('price') ?: 1000,
        ]);
    }

    public function advertisement(Request $request, $id): View {
        // Find the advertisement with related data
        $advertisement = Advertisement::with(['user'])
            ->findOrFail($id);

        // Get reviews with sorting
        $reviewsQuery = $advertisement->reviews()->with('user');

        // Apply sorting based on request
        $sort = $request->get('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $reviewsQuery->orderBy('created_at', 'asc');
                break;
            case 'rating_asc':
                $reviewsQuery->orderBy('rating', 'asc');
                break;
            case 'rating_desc':
                $reviewsQuery->orderBy('rating', 'desc');
                break;
            case 'date_desc':
            default:
                $reviewsQuery->orderBy('created_at', 'desc');
                break;
        }

        $reviews = $reviewsQuery->paginate(3)->withQueryString();

        // Get other ads from the same seller (excluding the current one)
        $sellerOtherAds = Advertisement::where('user_id', $advertisement->user_id)
            ->where('id', '!=', $advertisement->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('public/advertisement', [
            'advertisement' => $advertisement,
            'sellerOtherAds' => $sellerOtherAds,
            'reviews' => $reviews,
            'currentSort' => $sort
        ]);
    }
}

