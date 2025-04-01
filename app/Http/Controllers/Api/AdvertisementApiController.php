<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertisementApiController extends Controller
{
    /**
     * Return a listing of advertisements.
     */
    public function index(Request $request): JsonResponse
    {
        $advertisements = Advertisement::query()
            ->when($request->filled('price_range'), function ($query) use ($request) {
                $this->applyPriceFilter($query, $request->input('price_range'));
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $advertisements
        ]);
    }

    /**
     * Return a specific advertisement.
     */
    public function show($id): JsonResponse
    {
        $advertisement = Advertisement::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $advertisement
        ]);
    }

    /**
     * Apply price range filter.
     */
    private function applyPriceFilter($query, $priceRange)
    {
        $range = explode('-', $priceRange);

        if (count($range) === 2) {
            return $query->whereBetween('price', [$range[0], $range[1]]);
        }

        if ($range[0] === '100plus') {
            return $query->where('price', '>', 100);
        }
    }
}
