<?php

namespace App\Http\Services\Seller;

use App\Models\Advertisement;
use App\Models\Business;
use Carbon\Carbon;

class AgendaService
{
    public function getAgendaList(?string $month = null): array
    {
        $currentDate = $month ? Carbon::createFromFormat('Y-m', $month) : now();
        $business = Business::where('user_id', auth()->id())->firstOrFail();

        $ads = Advertisement::query()
            ->where('business_id', $business->id)
            ->where(function($query) use ($currentDate) {
                $query->whereMonth('rental_end_date', $currentDate->month)
                    ->orWhereMonth('expiry_date', $currentDate->month);
            })
            ->where(function($query) use ($currentDate) {
                $query->whereYear('rental_end_date', $currentDate->year)
                    ->orWhereYear('expiry_date', $currentDate->year);
            })
            ->get();

        return [
            'ads' => $ads,
            'currentDate' => $currentDate,
            'business' => $business,
        ];
    }
}
