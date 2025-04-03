<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Services\Public\AdvertisementFavoriteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdvertisementFavoriteController extends Controller
{
    private AdvertisementFavoriteService $favoriteService;

    public function __construct(AdvertisementFavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function store(Request $request, $id): RedirectResponse
    {
        $value = $request->input('value', false);

        $this->favoriteService->toggleFavorite($id, $value);

        return redirect()->back()->with('success', 'Favorite status updated successfully.');
    }
}
