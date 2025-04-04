@extends('layouts.app')

@section('heading')
    Agenda
@endsection

@section('content')
    <div class="uk-container">
        {{-- All Products Overview --}}
        <div class="uk-card uk-card-default uk-card-small uk-card-body uk-margin-large-bottom">
            <h3 class="text-base font-semibold mb-3">Alle Producten</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @forelse($ads as $ad)
                    <div class="p-2 bg-gray-50 rounded-md border {{ $ad->isPurchased() ? 'border-red-500' : 'border-gray-200' }}">
                        <div class="font-medium text-xs {{ $ad->isPurchased() ? 'text-red-600' : '' }}">
                            {{ $ad->title }}
                        </div>
                        <div class="text-xs text-gray-600 mt-0.5">
                            @if($ad->isRental())
                                {{ $ad->rental_start_date->format('d/m') }} - {{ $ad->rental_end_date->format('d/m/y') }}
                            @else
                                Beschikbaar tot: {{ $ad->expiry_date->format('d/m/y') }}
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Geen producten</p>
                @endforelse
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="uk-card uk-card-small uk-card-default uk-margin-medium-top">
            <div class="grid grid-cols-7 divide-x divide-y border border-gray-200 rounded-lg">
                @foreach(['Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za', 'Zo'] as $day)
                    <div class="p-1.5 text-center text-xs font-medium bg-gray-50">
                        {{ $day }}
                    </div>
                @endforeach

                @php
                    $currentDate = now();
                    $startOfMonth = $currentDate->copy()->startOfMonth();
                    $endOfMonth = $currentDate->copy()->endOfMonth();
                    $currentDay = $startOfMonth->copy()->startOfWeek();
                @endphp

                @while($currentDay <= $endOfMonth->copy()->endOfWeek())
                    <div class="bg-white p-2 min-h-[100px] relative group hover:bg-gray-50 transition-colors {{ !$currentDay->isSameMonth($startOfMonth) ? 'bg-gray-50' : '' }}">
                        <span class="inline-block px-1.5 py-0.5 rounded-full text-xs {{ !$currentDay->isSameMonth($startOfMonth) ? 'text-gray-400' : '' }}">
                            {{ $currentDay->format('j') }}
                        </span>

                        @foreach($ads as $ad)
                            @php
                                $startDate = $ad->rental_start_date ?? $ad->created_at;
                                $endDate = $ad->rental_end_date ?? $ad->expiry_date;
                                $isRental = $ad->isRental();
                                $isPurchased = $ad->isPurchased();
                            @endphp

                            {{-- Show all ads on their end date or during their rental period --}}
                            @if($currentDay->isSameDay($endDate) ||
                                ($isRental && $currentDay->between($startDate, $endDate)) ||
                                (!$isRental && $currentDay->between($ad->created_at, $endDate)))
                                <div class="mt-0.5">
                                    <div class="text-xs p-1.5 rounded border {{ $isPurchased ? 'border-red-500 bg-red-50' : 'border-gray-200 bg-gray-50' }}">
                                        <a href="{{ route('advertisements.show', $ad->id) }}" class="hover:underline block truncate {{ $isPurchased ? 'text-red-600' : 'text-gray-700' }}">
                                            <span>{{ $ad->title }}</span>
                                            <span class="text-xs text-gray-500">({{ $isRental ? 'Huur' : 'Verkoop' }})</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @php $currentDay->addDay() @endphp
                @endwhile
            </div>
        </div>

        {{-- Upcoming End Dates Sidebar --}}
        <div class="uk-margin-small-top">
            <div class="uk-card uk-card-small uk-card-default uk-card-body">
                <h3 class="text-base font-semibold mb-3">Aankomende einddatums</h3>
                <div class="space-y-2">
                    @php
                        $sortedAds = $ads->sortBy(function($ad) {
                            return $ad->rental_end_date ?? $ad->expiry_date;
                        })->take(5);
                    @endphp
                    @forelse($sortedAds as $ad)
                        <div class="p-2 rounded-md border {{ $ad->isPurchased() ? 'border-red-500 bg-red-50' : 'border-gray-200 bg-gray-50' }}">
                            <div class="text-xs {{ $ad->isPurchased() ? 'text-red-600' : 'text-gray-700' }} font-medium">
                                {{ $ad->title }}
                                <span class="text-gray-500">({{ $ad->isRental() ? 'Huur' : 'Verkoop' }})</span>
                            </div>
                            <div class="text-xs text-gray-600">
                                @if($ad->isRental())
                                    Start: {{ $ad->rental_start_date->format('d M Y') }}<br>
                                    Eindigt: {{ $ad->rental_end_date->format('d M Y') }}
                                @else
                                    Beschikbaar tot: {{ $ad->expiry_date->format('d M Y') }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Geen aankomende einddatums</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
