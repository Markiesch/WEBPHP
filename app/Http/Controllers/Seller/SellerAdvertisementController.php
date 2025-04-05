<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Services\Seller\SellerAdvertisementService;
use App\Models\Advertisement;
use App\Models\Business;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerAdvertisementController extends Controller
{
    private SellerAdvertisementService $advertisementService;

    public function __construct(SellerAdvertisementService $advertisementService)
    {
        $this->advertisementService = $advertisementService;
    }

    public function index(Request $request): View
    {
        $business = Business::where('user_id', Auth::id())->firstOrFail();
        $advertisements = $this->advertisementService->getAdvertisements($business, $request->all());

        return view('advertisements.index', [
            'advertisements' => $advertisements,
            'types' => Advertisement::getTypes()
        ]);
    }

    public function create(): View
    {
        return view('advertisements.create', ['types' => Advertisement::getTypes()]);
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $this->validateAdvertisement($request);
            $business = Business::where('user_id', Auth::id())->firstOrFail();

            $this->advertisementService->createAdvertisement($validated, $business);

            return redirect()
                ->route('advertisements.index')
                ->with('success', 'Advertisement created successfully.');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Advertisement $advertisement): View
    {
        return view('advertisements.show', compact('advertisement'));
    }

    public function edit(Advertisement $advertisement): View
    {
        $this->authorize('update', $advertisement);

        return view('advertisements.edit', [
            'advertisement' => $advertisement,
            'types' => Advertisement::getTypes()
        ]);
    }

    public function update(Request $request, Advertisement $advertisement): RedirectResponse
    {
        try {
            $this->authorize('update', $advertisement);

            $validated = $this->validateAdvertisement($request);
            $this->advertisementService->updateAdvertisement($advertisement, $validated);

            return redirect()
                ->route('advertisements.index')
                ->with('success', 'Advertisement updated successfully.');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function uploadCsv(): View
    {
        return view('advertisements.upload-csv');
    }

    public function processCsv(Request $request): RedirectResponse
    {
        try {
            $request->validate(['csv_file' => 'required|file|mimes:csv,txt|max:2048']);
            $business = Business::where('user_id', Auth::id())->firstOrFail();

            $successCount = $this->advertisementService->importFromCsv(
                $request->file('csv_file'),
                $business
            );

            return redirect()
                ->route('advertisements.index')
                ->with('success', "{$successCount} advertisements imported successfully.");
        } catch (Exception $e) {
            return redirect()
                ->route('advertisements.index')
                ->withErrors(['error' => 'Failed to import advertisements: ' . $e->getMessage()]);
        }
    }

    private function validateAdvertisement(Request $request): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'wear_percentage' => 'required|integer|min:0|max:100',
            'type' => 'required|in:' . implode(',', [
                    Advertisement::TYPE_SALE,
                    Advertisement::TYPE_RENTAL,
                    Advertisement::TYPE_AUCTION
                ]),
            'image' => $request->isMethod('post') ? 'required' : 'nullable' . '|image|mimes:jpeg,png,jpg,gif,webp,svg,bmp|max:5120'
        ];

        if ($request->input('type') === Advertisement::TYPE_SALE) {
            $rules['price'] = 'required|numeric|min:0';
        }

        if ($request->input('type') === Advertisement::TYPE_RENTAL) {
            $rules['price'] = 'required|numeric|min:0';
            $rules['rental_start_date'] = 'required|date|after_or_equal:today';
            $rules['rental_end_date'] = 'required|date|after:rental_start_date';
            $rules['wear_per_day'] = 'required|numeric|min:0|max:100';
        }

        if ($request->input('type') === Advertisement::TYPE_AUCTION) {
            $rules['starting_price'] = 'required|numeric|min:0';
            $rules['auction_end_date'] = 'required|date|after:today';
        }

        return $request->validate($rules);
    }

    protected function authorize($ability, $arguments = []): void
    {
        $business = Business::where('user_id', Auth::id())->firstOrFail();

        if ($arguments instanceof Advertisement && $arguments->business_id !== $business->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function editRelated(Advertisement $advertisement): View
    {
        $this->authorize('update', $advertisement);

        return view('advertisements.edit-related', [
            'advertisement' => $advertisement,
            'availableAdvertisements' => $this->advertisementService->getAvailableRelatedAdvertisements($advertisement),
            'selectedIds' => $advertisement->relatedAdvertisements->pluck('id')->toArray()
        ]);
    }

    public function updateRelated(Request $request, Advertisement $advertisement): RedirectResponse
    {
        try {
            $this->authorize('update', $advertisement);

            $validated = $request->validate([
                'related_advertisements' => 'array',
                'related_advertisements.*' => 'exists:advertisements,id'
            ]);

            $this->advertisementService->updateRelatedAdvertisements(
                $advertisement,
                $validated['related_advertisements'] ?? []
            );

            return redirect()
                ->route('advertisements.show', $advertisement)
                ->with('success', 'Related advertisements updated successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
