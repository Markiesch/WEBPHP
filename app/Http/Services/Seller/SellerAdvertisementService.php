<?php

namespace App\Http\Services\Seller;

use App\Models\Advertisement;
use App\Models\Business;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SellerAdvertisementService
{
    public function getAdvertisements(Business $business, array $filters = []): mixed
    {
        return Advertisement::where('business_id', $business->id)
            ->when(isset($filters['type']), function ($query) use ($filters) {
                $query->ofType($filters['type']);
            })
            ->when(isset($filters['price_range']), function ($query) use ($filters) {
                $this->applyPriceFilter($query, $filters['price_range']);
            })
            ->sortable($filters)
            ->paginate(6);
    }

    public function createAdvertisement(array $data, Business $business): Advertisement
    {
        $this->validateAdvertisementLimit($data['type'], $business);

        $data['business_id'] = $business->id;
        $data = $this->prepareAdvertisementData($data);

        return Advertisement::create($data);
    }

    public function updateAdvertisement(Advertisement $advertisement, array $data): bool
    {
        $data = $this->prepareAdvertisementData($data, $advertisement);
        return $advertisement->update($data);
    }

    public function importFromCsv(UploadedFile $file, Business $business): int
    {
        $limits = $this->getBusinessLimits($business);
        $filename = $this->storeFile($file);
        $handle = fopen(public_path('assets/csv/' . $filename), 'r');
        $header = fgetcsv($handle);

        $counts = ['success' => 0, 'sale' => 0, 'rental' => 0];

        while (($row = fgetcsv($handle)) !== false) {
            if (!$this->isValidRow($row)) continue;

            $type = strtolower(trim($row[4]));
            if (!$this->canCreateMoreAds($type, $counts, $limits)) continue;

            try {
                $this->createFromCsvRow($row, $business, $type);
                $this->updateCounts($counts, $type);
            } catch (Exception $e) {
                Log::error('CSV import failed: ' . $e->getMessage(), ['row' => $row]);
            }
        }

        fclose($handle);
        return $counts['success'];
    }

    private function validateAdvertisementLimit(string $type, Business $business): void
    {
        $adsCount = Advertisement::where('business_id', $business->id)
            ->ofType($type)
            ->count();

        $maxAds = match($type) {
            Advertisement::TYPE_SALE => Advertisement::MAX_SALE_ADS,
            Advertisement::TYPE_RENTAL => Advertisement::MAX_RENTAL_ADS,
            Advertisement::TYPE_AUCTION => Advertisement::MAX_AUCTION_ADS,
            default => Advertisement::MAX_SALE_ADS,
        };

        if ($adsCount >= $maxAds) {
            throw new Exception("Maximum limit of {$maxAds} advertisements reached for this type.");
        }
    }

    private function prepareAdvertisementData(array $data, ?Advertisement $advertisement = null): array
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image_url'] = $this->handleImageUpload($data['image']);
        }

        if ($data['type'] === Advertisement::TYPE_RENTAL) {
            $data = array_merge($data, $this->prepareRentalData($data));
        } elseif ($data['type'] === Advertisement::TYPE_AUCTION) {
            $data = array_merge($data, $this->prepareAuctionData($data));
        }

        return $data;
    }

    private function handleImageUpload(UploadedFile $image): string
    {
        $filename = time() . '_' . Str::slug($image->getClientOriginalName());
        $image->storeAs('public/images', $filename);
        return 'storage/images/' . $filename;
    }

    private function prepareRentalData(array $data): array
    {
        return [
            'rental_start_date' => $data['rental_start_date'],
            'rental_end_date' => $data['rental_end_date'],
            'wear_per_day' => $data['wear_per_day']
        ];
    }

    private function prepareAuctionData(array $data): array
    {
        return [
            'starting_price' => $data['starting_price'],
            'current_bid' => $data['starting_price'],
            'auction_end_date' => $data['auction_end_date'],
            'price' => null
        ];
    }

    private function applyPriceFilter($query, string $priceRange): void
    {
        $range = explode('-', $priceRange);

        if (count($range) === 2) {
            $query->whereBetween('price', [$range[0], $range[1]]);
        } elseif ($range[0] === '100plus') {
            $query->where('price', '>', 100);
        }
    }

    private function getBusinessLimits(Business $business): array
    {
        return [
            'sale' => [
                'max' => Advertisement::MAX_SALE_ADS,
                'current' => Advertisement::where('business_id', $business->id)
                    ->where('type', Advertisement::TYPE_SALE)
                    ->count()
            ],
            'rental' => [
                'max' => Advertisement::MAX_RENTAL_ADS,
                'current' => Advertisement::where('business_id', $business->id)
                    ->where('type', Advertisement::TYPE_RENTAL)
                    ->count()
            ]
        ];
    }

    private function isValidRow(array $row): bool
    {
        return count($row) >= 5;
    }

    private function canCreateMoreAds(string $type, array $counts, array $limits): bool
    {
        if ($type === Advertisement::TYPE_SALE) {
            return ($counts['sale'] + $limits['sale']['current']) < $limits['sale']['max'];
        }
        if ($type === Advertisement::TYPE_RENTAL) {
            return ($counts['rental'] + $limits['rental']['current']) < $limits['rental']['max'];
        }
        return false;
    }

    private function storeFile(UploadedFile $file): string
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('assets/csv'), $filename);
        return $filename;
    }

    private function updateCounts(array &$counts, string $type): void
    {
        $counts['success']++;
        if ($type === Advertisement::TYPE_SALE) {
            $counts['sale']++;
        } elseif ($type === Advertisement::TYPE_RENTAL) {
            $counts['rental']++;
        }
    }
}
