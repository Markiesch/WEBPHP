<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Business;
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

            $maxAds = $request->input('type') === Advertisement::TYPE_SALE
                ? Advertisement::MAX_SALE_ADS
                : Advertisement::MAX_RENTAL_ADS;

            if ($adsCount >= $maxAds) {
                return back()
                    ->withInput()
                    ->withErrors(['type' => "You have reached the maximum limit of {$maxAds} advertisements for this type."]);
            }

            $validated['image_url'] = $this->handleImageUpload($request);
            $validated['business_id'] = $business->id;
            $validated['wear_percentage'] = $request->input('wear_percentage');
            $validated['wear_per_day'] = $request->input('type') === Advertisement::TYPE_RENTAL ? $request->input('wear_per_day') : null;

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
                $rules['wear_per_day'] = 'required|numeric|min:0|max:100';
            }

            $validated = $request->validate($rules);

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
            $rules['wear_per_day'] = 'required|numeric|min:0|max:100';
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

    /**
     * Show the CSV upload form.
     */
    public function uploadCsv(): View
    {
        return view('advertisements.upload-csv');
    }

    /**
     * Process the CSV upload.
     */
    public function processCsv(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:2048'
            ]);

            $business = Business::where('user_id', Auth::id())->firstOrFail();
            $file = file($request->file('csv_file')->getPathname());

            // Skip header row
            $header = str_getcsv(array_shift($file));
            $requiredColumns = ['title', 'description', 'price', 'type', 'wear_percentage'];

            if (count(array_intersect($requiredColumns, $header)) !== count($requiredColumns)) {
                return back()->withErrors(['csv_file' => 'Invalid CSV format. Required columns missing.']);
            }

            foreach ($file as $line) {
                $data = array_combine($header, str_getcsv($line));

                // Basic validation
                if (empty($data['title']) || empty($data['description']) || !is_numeric($data['price'])) {
                    continue;
                }

                Advertisement::create([
                    'business_id' => $business->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'type' => $data['type'],
                    'wear_percentage' => $data['wear_percentage'],
                    'wear_per_day' => $data['wear_per_day'] ?? null,
                    'rental_start_date' => $data['rental_start_date'] ?? null,
                    'rental_end_date' => $data['rental_end_date'] ?? null,
                    'image_url' => null
                ]);
            }

            return redirect()
                ->route('advertisements.index')
                ->with('success', 'Advertisements imported successfully.');

        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * Download CSV template.
     */
    public function downloadTemplate(): Response
    {
        $headers = [
            'title',
            'description',
            'price',
            'type',
            'wear_percentage',
            'wear_per_day',
            'rental_start_date',
            'rental_end_date'
        ];

        $example = [
            'Example Product',
            'Product Description',
            '99.99',
            Advertisement::TYPE_SALE,
            '0',
            '',
            '',
            ''
        ];

        $output = fopen('php://temp', 'r+');
        fputcsv($output, $headers);
        fputcsv($output, $example);
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="advertisements_template.csv"',
        ]);
    }
}
