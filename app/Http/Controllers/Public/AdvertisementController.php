<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Services\Public\AdvertisementService;
use App\Models\Advertisement;
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

    public function purchase(Request $request, $id): View
    {
        $result = $this->service->purchase($request, $id);

        if (isset($result['error'])) {
            return $this->advertisement($request, $id)->with('error', $result['error']);
        }

        return $this->advertisement($request, $id)->with('success', $result['success']);
    }

    public function placeBid(Request $request, $id)
    {
        $advertisement = Advertisement::findOrFail($id);

        $request->validate([
            'bid_amount' => ['required', 'numeric', 'min:' . ($advertisement->current_bid + Advertisement::MIN_BID_INCREMENT)],
        ]);

        if ($advertisement->placeBid($request->bid_amount)) {
            return redirect()->back()->with('success', __('advertisement.bid_placed'));
        }

        return redirect()->back()->with('error', __('advertisement.bid_failed'));
    }


    public function purchases(Request $request): View {
        $data = $this->service->purchases($request);
        return view("public/purchases", $data);
    }
}
