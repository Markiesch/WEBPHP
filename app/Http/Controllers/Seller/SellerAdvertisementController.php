<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Business;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SellerAdvertisementController extends Controller
{
    public function index(Request $request): View
    {
        $business = Business::where('user_id', Auth::id())->firstOrFail();

        $advertisements = Advertisement::where('business_id', $business->id)
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
            $business = Business::where('user_id', Auth::id())->firstOrFail();

            $adsCount = Advertisement::where('business_id', $business->id)
                ->ofType($request->input('type'))
                ->count();

            $maxAds = match($request->input('type')) {
                Advertisement::TYPE_SALE => Advertisement::MAX_SALE_ADS,
                Advertisement::TYPE_RENTAL => Advertisement::MAX_RENTAL_ADS,
                Advertisement::TYPE_AUCTION => Advertisement::MAX_AUCTION_ADS,
                default => Advertisement::MAX_SALE_ADS,
            };

            if ($adsCount >= $maxAds) {
                return back()
                    ->withInput()
                    ->withErrors(['type' => "You have reached the maximum limit of {$maxAds} advertisements for this type."]);
            }

            $validated['image_url'] = $this->handleImageUpload($request);
            $validated['business_id'] = $business->id;
            $validated['wear_percentage'] = $request->input('wear_percentage');

            if ($request->input('type') === Advertisement::TYPE_RENTAL) {
                $validated['rental_start_date'] = $request->input('rental_start_date');
                $validated['rental_end_date'] = $request->input('rental_end_date');
                $validated['wear_per_day'] = $request->input('wear_per_day');
            }

            if ($request->input('type') === Advertisement::TYPE_AUCTION) {
                $validated['starting_price'] = $request->input('starting_price');
                $validated['current_bid'] = $request->input('starting_price');
                $validated['auction_end_date'] = $request->input('auction_end_date');
                $validated['price'] = null;
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
        $business = Business::where('user_id', Auth::id())->firstOrFail();

        if ($advertisement->business_id !== $business->id) {
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
            $business = Business::where('user_id', Auth::id())->firstOrFail();

            if ($advertisement->business_id !== $business->id) {
                abort(403);
            }

            $validated = $this->validateAdvertisement($request);

            if ($request->hasFile('image')) {
                $validated['image_url'] = $this->handleImageUpload($request);
            }

            $validated['business_id'] = $business->id;

            if ($request->input('type') === Advertisement::TYPE_RENTAL) {
                $validated['rental_start_date'] = $request->input('rental_start_date');
                $validated['rental_end_date'] = $request->input('rental_end_date');
                $validated['wear_per_day'] = $request->input('wear_per_day');
            } else {
                $validated['rental_start_date'] = null;
                $validated['rental_end_date'] = null;
                $validated['wear_per_day'] = null;
            }

            if ($request->input('type') === Advertisement::TYPE_AUCTION) {
                $validated['starting_price'] = $request->input('starting_price');
                $validated['auction_end_date'] = $request->input('auction_end_date');
                $validated['price'] = null;
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
            'wear_percentage' => 'required|integer|min:0|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg,bmp|max:5120',
            'type' => 'required|in:' . implode(',', [
                    Advertisement::TYPE_SALE,
                    Advertisement::TYPE_RENTAL,
                    Advertisement::TYPE_AUCTION
                ]),
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

    public function uploadCsv(): View
    {
        return view('advertisements.upload-csv');
    }

    public function processCsv(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:2048'
            ]);

            $business = Business::where('user_id', Auth::id())->firstOrFail();

            // Check existing advertisement counts
            $existingSaleCount = Advertisement::where('business_id', $business->id)
                ->where('type', Advertisement::TYPE_SALE)
                ->count();
            $existingRentalCount = Advertisement::where('business_id', $business->id)
                ->where('type', Advertisement::TYPE_RENTAL)
                ->count();

            $file = $request->file('csv_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/csv'), $filename);

            $handle = fopen(public_path('assets/csv/' . $filename), 'r');
            $header = fgetcsv($handle);

            $successCount = 0;
            $saleCount = 0;
            $rentalCount = 0;

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 5) continue;

                $type = strtolower(trim($row[4]));

                // Check limits based on type
                if ($type === Advertisement::TYPE_SALE && ($saleCount + $existingSaleCount) >= 4) {
                    continue;
                }
                if ($type === Advertisement::TYPE_RENTAL && ($rentalCount + $existingRentalCount) >= 4) {
                    continue;
                }

                try {
                    Advertisement::create([
                        'business_id' => $business->id,
                        'title' => trim($row[0]),
                        'description' => trim($row[1]),
                        'price' => (float) trim($row[2]),
                        'image_url' => trim($row[3]),
                        'type' => $type,
                        'wear_percentage' => trim($row[5] ?? 0),
                        'wear_per_day' => $type === Advertisement::TYPE_RENTAL ? trim($row[6] ?? 0) : null,
                        'rental_start_date' => $type === Advertisement::TYPE_RENTAL ? trim($row[7] ?? null) : null,
                        'rental_end_date' => $type === Advertisement::TYPE_RENTAL ? trim($row[8] ?? null) : null
                    ]);

                    $successCount++;
                    if ($type === Advertisement::TYPE_SALE) {
                        $saleCount++;
                    } else {
                        $rentalCount++;
                    }
                } catch (Exception $e) {
                    Log::error('Failed to import row: ' . implode(',', $row) . ' Error: ' . $e->getMessage());
                }
            }

            fclose($handle);

            return redirect()->route('advertisements.index')
                ->with('success', "{$successCount} advertisements imported successfully.");

        } catch (Exception $e) {
            return redirect()->route('advertisements.index')
                ->withErrors(['error' => 'Failed to import advertisements: ' . $e->getMessage()]);
        }
    }
}
