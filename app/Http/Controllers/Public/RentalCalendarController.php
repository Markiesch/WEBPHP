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
        $rentals = Advertisement::with(['business', 'transactions' => function($query) {
            $query->where('user_id', Auth::id());
        }])
            ->where('type', Advertisement::TYPE_RENTAL)
            ->whereHas('transactions', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get()
            ->map(function ($advertisement) {
                $transaction = $advertisement->transactions->first();
                return (object)[
                    'advertisement' => $advertisement,
                    'rental_start' => $advertisement->rental_start_date,
                    'rental_end' => $advertisement->rental_end_date,
                    'return_date' => $transaction->return_date ?? null,
                    'rental_days' => $transaction->rental_days ?? 7,
                    'created_at' => $transaction->created_at,
                    'transaction' => $transaction
                ];
            });

        return view('public.rental-calendar', [
            'rentals' => $rentals
        ]);
    }
}
