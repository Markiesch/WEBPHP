<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class BusinessController extends Controller
{
    public function index(Request $request): View
    {
        $business = Business::where('url', $request->route('url'))->firstOrFail();

        return view('public/business', [
            'business' => $business,
            'advertisements' => $business->advertisements,
            'blocks' => $business->activeBlocks
        ]);
    }
}
