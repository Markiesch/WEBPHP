<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Exception;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    private const QR_PATH = 'public/qrcodes';
    private const IMAGE_PATH = 'public/images';

    public function index(Request $request)
    {
        $advertisements = Advertisement::sortable($request)
            ->when($request->filled('price_range'), function ($query) use ($request) {
                $this->applyPriceFilter($query, $request->input('price_range'));
            })
            ->get();

        return view('advertisements.index', [
            'advertisements' => $advertisements,
            'qrCodes' => $this->getQrCodes($advertisements)
        ]);
    }

    public function create()
    {
        return view('advertisements.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateAdvertisement($request);

            if ($request->hasFile('image')) {
                $validated['image_url'] = $this->storeImage($request->file('image'));
            }

            $advertisement = Advertisement::create($validated);
            $this->generateQrCode($advertisement);

            return redirect()
                ->route('advertisements.index')
                ->with('success', 'Advertisement created successfully.');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create advertisement: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $advertisement = Advertisement::findOrFail($id);

        return view('advertisements.show', [
            'advertisement' => $advertisement,
            'qrTest' => $this->generateQrCodeData($id)
        ]);
    }

    private function validateAdvertisement(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    private function storeImage($image)
    {
        $path = $image->store(self::IMAGE_PATH);
        return Storage::url($path);
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

    private function generateQrCode($advertisement)
    {
        try {
            $result = $this->createQrCodeBuilder($advertisement->id)->build();
            Storage::put($this->getQrCodePath($advertisement->id), $result->getString());
            return true;
        } catch (Exception $e) {
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            return false;
        }
    }

    private function generateQrCodeData($id)
    {
        return $this->createQrCodeBuilder($id)
            ->build()
            ->getDataUri();
    }

    private function createQrCodeBuilder($id)
    {
        return Builder::create()
            ->writer(new PngWriter())
            ->data(route('advertisements.show', $id))
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->margin(10);
    }

    private function getQrCodes($advertisements)
    {
        $qrCodes = [];
        foreach ($advertisements as $advertisement) {
            $path = $this->getQrCodePath($advertisement->id);
            if (!Storage::exists($path)) {
                $this->generateQrCode($advertisement);
            }
            $qrCodes[$advertisement->id] = asset('storage/qrcodes/' . $advertisement->id . '.png');
        }
        return $qrCodes;
    }

    private function getQrCodePath($id)
    {
        return self::QR_PATH . '/' . $id . '.png';
    }
}
