<?php

namespace App\Http\Controllers\Services\Public;

use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AdvertisementService
{
    public function getAdvertisementsList(Request $request): array
    {
        $advertisements = Advertisement::query();
        $userId = Auth::id();

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

        // Filter favorites only
        if ($request->has('favorite') && $userId) {
            $advertisements->whereHas('favorites', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
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

        $paginatedAds = $advertisements->paginate(9)->withQueryString();

        $this->markFavorites($paginatedAds, $userId);

        return [
            'advertisements' => $paginatedAds,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'current_min' => ($request->price_range ? $request->price_range[0] : $min_price),
            'current_max' => ($request->price_range ? $request->price_range[1] : $max_price),
        ];
    }

    public function getAdvertisement(Request $request, $id): array
    {
        $advertisement = Advertisement::with(['user'])
            ->findOrFail($id);

        $userId = Auth::id();
        $advertisement->is_favorited = $userId ?
            AdvertisementFavorite::where('user_id', $userId)
                ->where('advertisement_id', $advertisement->id)
                ->exists() :
            false;

        // Get reviews with sorting
        $reviewsQuery = $advertisement->reviews()->with('user');

        // Apply rating filter if present
        if ($request->has('rating')) {
            $reviewsQuery->where('rating', $request->get('rating'));
        }

        // Apply sorting based on request
        $sort = $request->get('sort', 'date_desc');
        $this->applySortingToReviews($reviewsQuery, $sort);

        $reviews = $reviewsQuery->paginate(3)->withQueryString();

        // Get other ads from the same seller (excluding the current one)
        $sellerOtherAds = $this->getSellerOtherAds($advertisement);

        return [
            'advertisement' => $advertisement,
            'sellerOtherAds' => $sellerOtherAds,
            'reviews' => $reviews,
            'mean_rating' => number_format($advertisement->reviews()->avg('rating'), 1),
            'total_reviews_count' => $advertisement->reviews()->count(),
            'reviews_count' => [
                '1' => $advertisement->reviews()->where('rating', 1)->count(),
                '2' => $advertisement->reviews()->where('rating', 2)->count(),
                '3' => $advertisement->reviews()->where('rating', 3)->count(),
                '4' => $advertisement->reviews()->where('rating', 4)->count(),
                '5' => $advertisement->reviews()->where('rating', 5)->count(),
            ],
            'currentSort' => $sort
        ];
    }

    private function markFavorites(LengthAwarePaginator $advertisements, ?int $userId): void
    {
        if ($userId) {
            $favorites = AdvertisementFavorite::where('user_id', $userId)
                ->whereIn('advertisement_id', $advertisements->pluck('id'))
                ->pluck('advertisement_id')
                ->toArray();

            foreach ($advertisements as $ad) {
                $ad->is_favorited = in_array($ad->id, $favorites);
            }
        } else {
            foreach ($advertisements as $ad) {
                $ad->is_favorited = false;
            }
        }
    }

    private function applySortingToReviews($reviewsQuery, string $sort): void
    {
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
    }

    private function getSellerOtherAds(Advertisement $advertisement)
    {
        return Advertisement::where('user_id', $advertisement->user_id)
            ->where('id', '!=', $advertisement->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
}

