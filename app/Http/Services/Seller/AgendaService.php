<?php

namespace App\Http\Services\Seller;

use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class AgendaService
{

    public function getAgendaList(): array
    {
        $business = Business::where("user_id", auth()->id())->firstOrFail();

        $ads = Advertisement::query()
            ->where('business_id', $business->id)
            ->where('expiry_date', '>=', now())
            ->where('rental_start_date', '!=', null)
            ->has("transactions")
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'ads' => $ads,
            'business' => $business,
        ];
    }

}
