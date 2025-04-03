<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Services\Public\AdvertisementReviewService;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdvertisementReviewController extends Controller
{
    public function __construct(private readonly AdvertisementReviewService $service)
    {
    }

    public function store(Request $request, $id): RedirectResponse
    {
        $this->service->store($request, $id);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function delete($advertisementId, $id): RedirectResponse
    {
        $this->service->delete($advertisementId, $id);

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}
