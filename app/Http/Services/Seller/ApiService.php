<?php

namespace App\Http\Services\Seller;

use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class ApiService
{
    public function getData(): array
    {
        $business = Business::where("user_id", auth()->id())->firstOrFail();

        return [
            'business' => $business,
        ];
    }
}
