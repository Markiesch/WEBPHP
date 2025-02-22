<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Storage;
use Exception;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::all();
        $qrCodes = [];

        foreach ($advertisements as $advertisement) {
            $path = 'public/qrcodes/' . $advertisement->id . '.png';
            $exists = Storage::exists($path);

            if (!$exists) {
                $this->generateQrCode($advertisement);
            }

            $qrCodes[$advertisement->id] = asset('storage/qrcodes/' . $advertisement->id . '.png');
        }

        // Add debug info
        if (config('app.debug')) {
            \Log::info('QR Codes array:', $qrCodes);
        }

        return view('advertisements.index', compact('advertisements', 'qrCodes'));
    }


    public function create()
    {
        return view('advertisements.create');
    }

    private function generateQrCode($advertisement)
    {
        try {
            $url = route('advertisements.show', $advertisement->id);

            // Debug the URL
            if (config('app.debug')) {
                \Log::info('Generating QR for URL: ' . $url);
            }

            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($url)
                ->encoding(new Encoding('UTF-8'))
                ->size(300)
                ->margin(10)
                ->build();

            $path = 'public/qrcodes/' . $advertisement->id . '.png';
            Storage::put($path, $result->getString());

            if (config('app.debug')) {
                \Log::info('QR Code file exists: ' . Storage::exists($path));
                \Log::info('QR Code file size: ' . Storage::size($path));
            }

            return true;
        } catch (Exception $e) {
            \Log::error('QR Code generation failed: ' . $e->getMessage());
            return false;
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
            ]);

            $data = $request->all();

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('public/images');
                $data['image_url'] = Storage::url($imagePath);
            }

            $advertisement = Advertisement::create($data);

            // Generate QR code and log the result
            $qrGenerated = $this->generateQrCode($advertisement);

            if (config('app.debug')) {
                \Log::info('Advertisement created with ID: ' . $advertisement->id);
                \Log::info('QR Code generation success: ' . ($qrGenerated ? 'Yes' : 'No'));
            }

            return redirect()
                ->route('advertisements.index')
                ->with('success', 'Advertisement created successfully.');

        } catch (Exception $e) {
            \Log::error('Advertisement creation failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create advertisement: ' . $e->getMessage()]);
        }
    }


    public function show($id)
    {
        $url = route('advertisements.show', $id);

        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->margin(10)
            ->build();

        $qrTest = $qrCode->getDataUri();
        $advertisement = Advertisement::findOrFail($id);
        return view('advertisements.show', compact('advertisement', 'qrTest'));    }
}
