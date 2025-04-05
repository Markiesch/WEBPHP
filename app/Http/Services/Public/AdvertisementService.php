<?php

namespace App\Http\Services\Public;

use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use App\Models\AdvertisementTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementService
{

    public function __construct(private readonly AdvertisementFavoriteService $favoriteService)
    {
    }

    public function getAdvertisementsList(Request $request): array
    {
        $query = Advertisement::query();
        $userId = Auth::id();

        $this->applyFilters($query, $request, $userId);
        $this->applySorting($query, $request);

        $paginatedAds = $query->paginate(6)->withQueryString();
        $this->markFavorites($paginatedAds, $userId);

        $priceRange = $this->getPriceRange($request);

        return [
            'advertisements' => $paginatedAds,
            ...$priceRange,
        ];
    }

    public function getAdvertisement(Request $request, $id): array
    {
        $advertisement = Advertisement::with(['business', 'bids.user'])
            ->findOrFail($id);

        $userId = Auth::id();
        $advertisement->is_favorited = $this->favoriteService->isAdvertisementFavorited($advertisement->id, $userId);

        $reviewsQuery = $advertisement->reviews()->with('user');

        if ($request->has('rating')) {
            $reviewsQuery->where('rating', $request->get('rating'));
        }

        $sort = $request->get('sort', 'date_desc');
        $this->applySortingToReviews($reviewsQuery, $sort);

        $reviews = $reviewsQuery->paginate(3)->withQueryString();
        $sellerOtherAds = $this->getSellerOtherAds($advertisement);
        $reviewsStats = $this->getReviewsStatistics($advertisement);

        $auctionData = [];
        if ($advertisement->type === 'auction') {
            $auctionData = [
                'auction_status' => [
                    'current_bid' => $advertisement->current_bid ?? $advertisement->starting_price,
                    'minimum_next_bid' => ($advertisement->current_bid ?? $advertisement->starting_price) + 1,
                    'time_remaining' => $advertisement->auction_end_date->diffForHumans(),
                    'is_ended' => $advertisement->auction_end_date < now(),
                    'total_bids' => $advertisement->bids()->count(),
                ],
                'recent_bids' => $advertisement->bids()
                    ->with('user:id,name')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function ($bid) {
                        return [
                            'amount' => $bid->amount,
                            'bidder' => $bid->user->name,
                            'placed_at' => $bid->created_at->format('Y-m-d H:i:s'),
                        ];
                    }),
                'user_highest_bid' => $userId ? $advertisement->bids()
                    ->where('user_id', $userId)
                    ->max('amount') : null,
            ];
        }

        return [
            'advertisement' => $advertisement,
            'sellerOtherAds' => $sellerOtherAds,
            'reviews' => $reviews,
            'currentSort' => $sort,
            ...$reviewsStats,
            ...$auctionData,
        ];
    }

    public function purchase(Request $request, $id): array
    {
        $advertisement = Advertisement::findOrFail($id);
        $userId = Auth::id();

        if ($advertisement->isAuction()) {
            $highestBid = $advertisement->bids()
                ->where('user_id', $userId)
                ->orderByDesc('amount')
                ->first();

            if (!$highestBid || !$advertisement->isAuctionEnded()) {
                return [
                    'error' => 'You cannot purchase this item yet. You must be the highest bidder and the auction must be ended.'
                ];
            }

            $price = $highestBid->amount;
        } else {
            $price = $advertisement->price;
        }

        if ($advertisement->isPurchased()) {
            return ['error' => 'This advertisement has already been purchased.'];
        }

        AdvertisementTransaction::create([
            'user_id' => $userId,
            'advertisement_id' => $advertisement->id,
            'price' => $price,
            'type' => $advertisement->type
        ]);

        return [
            'success' => 'Advertisement purchased successfully.',
            'advertisement' => $advertisement
        ];
    }

    public function placeBid(Request $request, $id): array
    {
        $advertisement = Advertisement::findOrFail($id);
        $bidAmount = $request->input('bid_amount');
        $userId = Auth::id();

        if ($advertisement->type !== 'auction') {
            return ['error' => 'This advertisement is not an auction.'];
        }

        if ($advertisement->auction_end_date < now()) {
            return ['error' => 'This auction has ended.'];
        }

        if ($bidAmount <= ($advertisement->current_bid ?? $advertisement->starting_price)) {
            return ['error' => 'Bid must be higher than the current bid.'];
        }

        // Create bid record
        $advertisement->bids()->create([
            'user_id' => $userId,
            'amount' => $bidAmount
        ]);

        // Update current bid
        $advertisement->update([
            'current_bid' => $bidAmount
        ]);

        return [
            'success' => 'Bid placed successfully.',
            'advertisement' => $advertisement
        ];
    }

    public function purchases(Request $request): array
    {
        $query = Advertisement::query();
        $userId = Auth::id();

        $query->whereHas('transactions', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        $this->applyFilters($query, $request, $userId);
        $this->applySorting($query, $request);

        $paginatedAds = $query->paginate(6)->withQueryString();
        $this->markFavorites($paginatedAds, $userId);

        $priceRange = $this->getPriceRange($request);

        return [
            'advertisements' => $paginatedAds,
            ...$priceRange,
        ];
    }

    private function applyFilters(Builder $query, Request $request, ?int $userId): void
    {
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('price_range')) {
            $query->whereBetween('price', [$request->price_range[0], $request->price_range[1]]);
        }

        if ($request->has('favorite') && $userId) {
            $query->whereHas('favorites', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
        }
    }

    private function applySorting(Builder $query, Request $request): void
    {
        if ($request->filled('sort')) {
            $sort = explode('_', $request->sort);
            if (count($sort) === 2 && in_array($sort[0], ['date', 'price']) && in_array($sort[1], ['asc', 'desc'])) {
                $column = $sort[0] === 'date' ? 'created_at' : 'price';
                $query->orderBy($column, $sort[1]);
                return;
            }
        }

        // Default sorting
        $query->orderBy('created_at', 'desc');
    }

    private function getPriceRange(Request $request): array
    {
        $min_price = Advertisement::min('price') ?: 0;
        $max_price = Advertisement::max('price') ?: 1000;

        return [
            'min_price' => $min_price,
            'max_price' => $max_price,
            'current_min' => ($request->price_range ? $request->price_range[0] : $min_price),
            'current_max' => ($request->price_range ? $request->price_range[1] : $max_price),
        ];
    }

    private function markFavorites(LengthAwarePaginator $advertisements, ?int $userId): void
    {
        if (!$userId) {
            foreach ($advertisements as $ad) {
                $ad->is_favorited = false;
            }
            return;
        }

        $favorites = AdvertisementFavorite::where('user_id', $userId)
            ->whereIn('advertisement_id', $advertisements->pluck('id'))
            ->pluck('advertisement_id')
            ->toArray();

        foreach ($advertisements as $ad) {
            $ad->is_favorited = in_array($ad->id, $favorites);
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
        return Advertisement::where('business_id', $advertisement->business_id)
            ->where('id', '!=', $advertisement->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    private function getReviewsStatistics(Advertisement $advertisement): array
    {
        $ratingCounts = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = $advertisement->reviews()->where('rating', $i)->count();
        }

        return [
            'mean_rating' => number_format($advertisement->reviews()->avg('rating'), 1),
            'total_reviews_count' => $advertisement->reviews()->count(),
            'reviews_count' => $ratingCounts,
        ];
    }
}
