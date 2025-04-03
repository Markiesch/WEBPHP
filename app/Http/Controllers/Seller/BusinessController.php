<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;

class BusinessController extends Controller
{
    public function index()
    {
        return view('business.index');
    }
}
