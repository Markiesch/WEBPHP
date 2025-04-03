<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SellerAdvertisementController extends Controller
{
    public function index(Request $request): View
    {
        $advertisements = Advertisement::where('user_id', Auth::id())
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->ofType($request->input('type'));
            })
            ->sortable($request)
            ->when($request->filled('price_range'), function ($query) use ($request) {
                $this->applyPriceFilter($query, $request->input('price_range'));
            })
            ->paginate(6);

        return view('advertisements.index', [
            'advertisements' => $advertisements,
            'types' => Advertisement::getTypes()
        ]);
    }

    public function create(): View
    {
        return view('advertisements.create', [
            'types' => Advertisement::getTypes()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $this->validateAdvertisement($request);

            $userAdsCount = Advertisement::where('user_id', Auth::id())
                ->ofType($request->input('type'))
                ->count();

            $maxAds = $request->input('type') === Advertisement::TYPE_SALE
                ? Advertisement::MAX_SALE_ADS
                : Advertisement::MAX_RENTAL_ADS;

            if ($userAdsCount >= $maxAds) {
                return back()
                    ->withInput()
                    ->withErrors(['type' => "You have reached the maximum limit of {$maxAds} advertisements for this type."]);
            }

            $validated['image_url'] = $this->handleImageUpload($request);
            $validated['user_id'] = Auth::id();
            $validated['wear_percentage'] = $request->input('wear_percentage');

            if ($request->input('type') === Advertisement::TYPE_RENTAL) {
                $validated['rental_start_date'] = $request->input('rental_start_date');
                $validated['rental_end_date'] = $request->input('rental_end_date');
            }

            Advertisement::create($validated);

            return redirect()
                ->route('advertisements.index')
                ->with('success', 'Advertisement created successfully.');

        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function show($id): View
    {
        $advertisement = Advertisement::findOrFail($id);
        return view('advertisements.show', compact('advertisement'));
    }

    public function edit(Advertisement $advertisement): View
    {
        if ($advertisement->user_id !== Auth::id()) {
            abort(403);
        }

        return view('advertisements.edit', [
            'advertisement' => $advertisement,
            'types' => Advertisement::getTypes()
        ]);
    }

    public function update(Request $request, Advertisement $advertisement): RedirectResponse
    {
        try {
            if ($advertisement->user_id !== Auth::id()) {
                abort(403);
            }

            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'wear_percentage' => 'required|integer|min:0|max:100',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg,bmp|max:5120',
                'type' => 'required|in:' . Advertisement::TYPE_SALE . ',' . Advertisement::TYPE_RENTAL,
            ];

            if ($request->input('type') === Advertisement::TYPE_RENTAL) {
                $rules['rental_start_date'] = 'required|date|after_or_equal:today';
                $rules['rental_end_date'] = 'required|date|after:rental_start_date';
            }

            $validated = $request->validate($rules);

            if ($request->hasFile('image')) {
                $validated['image_url'] = $this->handleImageUpload($request);
            }

            if ($request->input('type') === Advertisement::TYPE_RENTAL) {
                $validated['rental_start_date'] = $request->input('rental_start_date');
                $validated['rental_end_date'] = $request->input('rental_end_date');
            } else {
                $validated['rental_start_date'] = null;
                $validated['rental_end_date'] = null;
            }

            $advertisement->update($validated);

            return redirect()
                ->route('advertisements.index')
                ->with('success', 'Advertisement updated successfully.');

        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    private function validateAdvertisement(Request $request): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'wear_percentage' => 'required|integer|min:0|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg,bmp|max:5120',
            'type' => 'required|in:' . Advertisement::TYPE_SALE . ',' . Advertisement::TYPE_RENTAL,
        ];

        if ($request->input('type') === Advertisement::TYPE_RENTAL) {
            $rules['rental_start_date'] = 'required|date|after_or_equal:today';
            $rules['rental_end_date'] = 'required|date|after:rental_start_date';
        }

        return $request->validate($rules);
    }

    private function handleImageUpload(Request $request): ?string
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($image->getClientOriginalName());
            $image->storeAs('public/images', $filename);
            return 'storage/images/' . $filename;
        }
        return null;
    }

    private function handleError(Exception $e): RedirectResponse
    {
        return back()
            ->withInput()
            ->withErrors(['error' => 'Failed to create advertisement: ' . $e->getMessage()]);
    }

    private function applyPriceFilter($query, $priceRange)
    {
        $range = explode('-', $priceRange);

        if (count($range) === 2) {
            return $query->whereBetween('price', [$range[0], $range[1]]);
        }

        if ($range[0] === '100plus') {
            return $query->where('price', '>', 100);
        }
    }
}
