<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Services\Seller\ApiService;
use Illuminate\View\View;

class SellerAPIController extends Controller
{
    public function __construct(private readonly ApiService $service) {}

    public function index(): View
    {
        $data = $this->service->getData();
        return view('seller/api', $data);
    }
}
