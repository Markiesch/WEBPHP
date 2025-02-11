<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function show($url)
    {
        $data = LandingPage::where('url', $url)->firstOrFail();
        return view('landing_pages.show', compact('data'));
    }
}
