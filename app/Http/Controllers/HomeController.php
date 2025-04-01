<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $advertisements = Advertisement::query();

        // Search by name (title)
        if ($request->filled('search')) {
            $advertisements->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $advertisements->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Sort results
        if ($request->filled('sort')) {
            $sort = explode('_', $request->sort);
            if (count($sort) === 2 && in_array($sort[0], ['date', 'price']) && in_array($sort[1], ['asc', 'desc'])) {
                $column = $sort[0] === 'date' ? 'created_at' : 'price';
                $advertisements->orderBy($column, $sort[1]);
            }
        } else {
            // Default sorting
            $advertisements->orderBy('created_at', 'desc');
        }

        return view('home', [
            'advertisements' => $advertisements->get(),
            'min_price' => Advertisement::min('price') ?: 0,
            'max_price' => Advertisement::max('price') ?: 1000,
            'current_min' => $request->min_price ?: Advertisement::min('price') ?: 0,
            'current_max' => $request->max_price ?: Advertisement::max('price') ?: 1000,
        ]);
    }
}
