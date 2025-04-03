<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;

class AdvertisementApiController extends Controller
{
    public function index($id): JsonResponse
    {
        $advertisements = Advertisement::where('business_id', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $advertisements
        ]);
    }

    public function show($id, $adId): JsonResponse
    {
        $advertisement = Advertisement::where('business_id', $id)->findOrFail($adId);

        return response()->json([
            'success' => true,
            'data' => $advertisement
        ]);
    }
}
