<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;

class AdvertisementController extends Controller
{
    public function create()
    {
        return view('advertisements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
        ]);

        Advertisement::create($request->all());

        return redirect()->route('advertisements.create')->with('status', 'Advertisement created successfully!');
    }

    public function calendar()
    {
        $events = Advertisement::all()->map(function ($advertisement) {
            return [
                'title' => $advertisement->title,
                'start' => $advertisement->rental_start_date,
                'end' => $advertisement->rental_end_date,
            ];
        });

        return view('advertisements.calendar', ['events' => $events]);
    }

    public function expiryCalendar()
    {
        $expiryEvents = Advertisement::all()->map(function ($advertisement) {
            return [
                'title' => $advertisement->title,
                'start' => $advertisement->expiry_date,
            ];
        });

        return view('advertisements.expiry-calendar', ['expiryEvents' => $expiryEvents]);
    }
}
