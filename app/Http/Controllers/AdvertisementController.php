<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    private const IMAGE_PATH = 'public/images';

    public function index(Request $request)
    {
        $advertisements = Advertisement::sortable($request)
            ->when($request->filled('price_range'), function ($query) use ($request) {
                $this->applyPriceFilter($query, $request->input('price_range'));
            })
            ->paginate(6);

        return view('advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        return view('advertisements.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateAdvertisement($request);
            $validated['image_url'] = $this->handleImageUpload($request);

            Advertisement::create($validated);

            return redirect()
                ->route('advertisements.index')
                ->with('success', 'Advertisement created successfully.');

        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }

    public function show($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        return view('advertisements.show', compact('advertisement'));
    }

    private function validateAdvertisement(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg,bmp|max:5120',
        ]);
    }

    private function handleImageUpload(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($image->getClientOriginalName());
            $image->storeAs('public/images', $filename);
            return 'storage/images/' . $filename;
        }
        return null;
    }

    private function handleError(Exception $e)
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
