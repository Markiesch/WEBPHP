<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;

class LandingPageController extends Controller
{
    public function show($url)
    {
        $data = LandingPage::where('url', $url)->firstOrFail();
        return view('landing_pages.show', compact('data'));
    }
}
