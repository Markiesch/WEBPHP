<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function getRentals()
    {
        $rentals = Rental::all();
        $events = [];

        foreach ($rentals as $rental) {
            $events[] = [
                'title' => $rental->product_name,
                'start' => $rental->start_date,
                'end' => $rental->end_date,
            ];
        }

        return response()->json($events);
    }

    public function index(Request $request)
    {
        return view('calendar');
    }

    public function getAdvertisements(Request $request)
    {
        $advertisements = Rental::all();
        return response()->json($advertisements);
    }
}
