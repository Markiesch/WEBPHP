<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Services\Public\AdvertisementService;
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

    public function purchase(Request $request, $id): View {
        $this->service->purchase($request, $id);

        return $this->advertisement($request, $id);
    }

    public function purchases(Request $request): View {
        $data = $this->service->purchases($request);
        return view("public/purchases", $data);
    }
}
