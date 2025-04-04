<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessController extends Controller
{
    public function index(Request $request): View
    {
        $business = Business::where('url', $request->route('url'))
            ->with(['advertisements', 'blocks', 'reviews' => function($query) {
                $query->latest()->with('user');
            }])
            ->firstOrFail();

        return view('public/business', [
            'business' => $business,
            'advertisements' => $business->advertisements,
            'blocks' => $business->blocks
        ]);
    }
}
