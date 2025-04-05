<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalCalendarController extends Controller
{
    public function index()
    {
        $rentals = Advertisement::with('business')
            ->where('type', Advertisement::TYPE_RENTAL)
            ->whereHas('transactions', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get()
            ->map(function ($advertisement) {
                return (object)[
                    'advertisement' => $advertisement,
                    'rental_start' => $advertisement->rental_start_date,
                    'rental_end' => $advertisement->rental_end_date
                ];
            });

        return view('public.rental-calendar', [
            'rentals' => $rentals
        ]);

    }
}
