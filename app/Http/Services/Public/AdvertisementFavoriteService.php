<?php

namespace App\Http\Services\Public;

use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use Illuminate\Support\Facades\Auth;

class AdvertisementFavoriteService
{
    /**
     * Toggle the favorite status of an advertisement
     */
    public function toggleFavorite(int $advertisementId, ?bool $value = null): bool
    {
        $advertisement = Advertisement::findOrFail($advertisementId);
        $userId = Auth::id();

        // If value is null, toggle the current state
        if ($value === null) {
            $isFavorited = $this->isAdvertisementFavorited($advertisementId, $userId);
            return $isFavorited ?
                $this->removeFromFavorites($advertisementId, $userId) :
                $this->addToFavorites($advertisementId, $userId);
        }

        // Otherwise use the explicitly provided value
        return $value ?
            $this->addToFavorites($advertisement->id, $userId) :
            $this->removeFromFavorites($advertisement->id, $userId);
    }

    /**
     * Add an advertisement to user favorites
     */
    private function addToFavorites(int $advertisementId, int $userId): bool
    {
        // Prevent duplicate favorites
        $exists = AdvertisementFavorite::where('user_id', $userId)
            ->where('advertisement_id', $advertisementId)
            ->exists();

        if ($exists) {
            return false;
        }

        AdvertisementFavorite::create([
            'user_id' => $userId,
            'advertisement_id' => $advertisementId,
        ]);

        return true;
    }

    /**
     * Remove an advertisement from user favorites
     */
    private function removeFromFavorites(int $advertisementId, int $userId): bool
    {
        $result = AdvertisementFavorite::where('user_id', $userId)
            ->where('advertisement_id', $advertisementId)
            ->delete();

        return $result > 0;
    }

    public function isAdvertisementFavorited(int $advertisementId, ?int $userId = null): bool
    {
        if (!$userId) {
            $userId = Auth::id();
        }

        if (!$userId) {
            return false;
        }

        return AdvertisementFavorite::where('user_id', $userId)
            ->where('advertisement_id', $advertisementId)
            ->exists();
    }
}
