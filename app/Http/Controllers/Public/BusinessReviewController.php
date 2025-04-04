<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Services\Public\BusinessReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BusinessReviewController extends Controller
{
    public function __construct(private readonly BusinessReviewService $service)
    {
    }

    public function store(Request $request, $businessId): RedirectResponse
    {
        $this->service->store($request, $businessId);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function delete($businessId, $reviewId): RedirectResponse
    {
        $this->service->delete($businessId, $reviewId);

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}
