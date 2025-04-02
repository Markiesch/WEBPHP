<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\Public\AdvertisementService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function __construct(private readonly AdvertisementService $service) {}

    public function advertisements(Request $request): View
    {
        $data = $this->service->getAdvertisementsList($request);

        return view('public/advertisements', $data);
    }

    public function advertisement(Request $request, $id): View {
        $data = $this->service->getAdvertisement($request, $id);

        return view('public/advertisement', $data);
    }
}
