<?php
//
//namespace App\Http\Services\Seller;
//
//use App\Models\Advertisement;
//use Carbon\Carbon;
//use Illuminate\Support\Collection;
//
//class AdvertisementAgendaService
//{
//    public function getCalendarData(string $month = null): array
//    {
//        $date = $month ? Carbon::parse($month) : now();
//        $startDate = $date->copy()->startOfMonth();
//        $endDate = $date->copy()->endOfMonth();
//
//        $startDate = $startDate->copy()->startOfWeek(Carbon::MONDAY);
//        $endDate = $endDate->copy()->endOfWeek(Carbon::SUNDAY);
//
//        $advertisements = $this->getAdvertisementsForDateRange($startDate, $endDate);
//        $calendar = $this->buildCalendarDays($startDate, $endDate, $advertisements);
//
//        return [
//            'calendar' => $calendar,
//            'currentMonth' => $date->format('F Y'),
//            'previousMonth' => $date->copy()->subMonth()->format('Y-m'),
//            'nextMonth' => $date->copy()->addMonth()->format('Y-m'),
//            'upcomingEndDates' => $this->getUpcomingEndDates(),
//            'expiringCount' => $this->getExpiringCount(),
//            'totalActive' => $this->getTotalActiveCount()
//        ];
//    }
//
//    private function getAdvertisementsForDateRange(Carbon $startDate, Carbon $endDate): Collection
//    {
//        if (!auth()->user() || !auth()->user()->business) {
//            return collect();
//        }
//
//        return Advertisement::where('business_id', auth()->user()->business->id)
//            ->where('expiry_date', '>=', now())
//            ->whereBetween('expiry_date', [
//                $startDate->startOfDay(),
//                $endDate->endOfDay()
//            ])
//            ->with(['business'])
//            ->orderBy('expiry_date')
//            ->get();
//    }
//
//    private function buildCalendarDays(Carbon $startDate, Carbon $endDate, Collection $advertisements): Collection
//    {
//        $calendar = collect();
//        $currentDate = $startDate->copy();
//
//        while ($currentDate <= $endDate) {
//            $dateKey = $currentDate->format('Y-m-d');
//            $dayAdvertisements = $advertisements->filter(function ($ad) use ($currentDate) {
//                return $ad->expiry_date->startOfDay()->equalTo($currentDate->startOfDay());
//            });
//
//            $calendar[$dateKey] = [
//                'date' => $currentDate->copy(),
//                'isCurrentMonth' => $currentDate->format('m') === now()->format('m'),
//                'isToday' => $currentDate->isToday(),
//                'isWeekend' => $currentDate->isWeekend(),
//                'advertisements' => $dayAdvertisements->map(function ($ad) {
//                    return [
//                        'id' => $ad->id,
//                        'title' => $ad->title,
//                        'expiry_date' => $ad->expiry_date->format('Y-m-d H:i:s'),
//                        'type' => $ad->type,
//                        'business_name' => $ad->business->name ?? null,
//                        'days_until_expiry' => $ad->daysUntilExpiry(),
//                        'is_expiring_soon' => $ad->daysUntilExpiry() <= 5
//                    ];
//                })
//            ];
//
//            $currentDate->addDay();
//        }
//
//        return $calendar;
//    }
//
//    private function getUpcomingEndDates(): Collection
//    {
//        if (!auth()->user() || !auth()->user()->business) {
//            return collect();
//        }
//
//        return Advertisement::where('business_id', auth()->user()->business->id)
//            ->where('expiry_date', '>=', now())
//            ->where('expiry_date', '<=', now()->addDays(7))
//            ->orderBy('expiry_date')
//            ->limit(5)
//            ->get()
//            ->map(function ($ad) {
//                return [
//                    'id' => $ad->id,
//                    'title' => $ad->title,
//                    'expiry_date' => $ad->expiry_date->format('Y-m-d H:i:s'),
//                    'type' => $ad->type,
//                    'days_until_expiry' => $ad->daysUntilExpiry()
//                ];
//            });
//    }
//
//    private function getExpiringCount(): int
//    {
//        if (!auth()->user() || !auth()->user()->business) {
//            return 0;
//        }
//
//        return Advertisement::where('business_id', auth()->user()->business->id)
//            ->where('expiry_date', '>=', now())
//            ->where('expiry_date', '<=', now()->addDays(5))
//            ->count();
//    }
//
//    private function getTotalActiveCount(): int
//    {
//        if (!auth()->user() || !auth()->user()->business) {
//            return 0;
//        }
//
//        return Advertisement::where('business_id', auth()->user()->business->id)
//            ->where('expiry_date', '>=', now())
//            ->count();
//    }
//}
