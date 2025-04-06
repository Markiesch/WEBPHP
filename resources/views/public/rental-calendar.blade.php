@extends('layouts.home')

@section('content')
    <x-header/>

    <h1 class="text-6xl font-bold text-center py-12">{{ __('rental.calendar.title') }}</h1>

    <div class="uk-container">
        {{-- Rented Products Overview --}}
        <div class="uk-card uk-card-default uk-card-body uk-margin-large-bottom shadow-sm rounded-lg">
            <h3 class="text-2xl font-semibold mb-6">{{ __('rental.calendar.my_rented_products') }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($rentals as $rental)
                    <div class="p-4 bg-white rounded-lg border-2 {{ $rental->return_date ? 'border-green-200' : 'border-blue-200' }} hover:border-opacity-75 transition-colors">
                        <div class="font-medium {{ $rental->return_date ? 'text-green-800' : 'text-blue-800' }} text-sm mb-2">
                            {{ $rental->advertisement->title }}
                        </div>
                        <div class="text-sm text-gray-600 flex flex-col gap-1">
                            <div class="flex justify-between items-center">
                                <span class="bg-blue-50 px-2 py-1 rounded">{{ $rental->rental_start->format('d/m') }}</span>
                                <span class="text-gray-400">{{ __('rental.calendar.to') }}</span>
                                <span class="{{ $rental->return_date ? 'bg-green-50' : 'bg-red-50' }} px-2 py-1 rounded">{{ $rental->rental_end->format('d/m/y') }}</span>
                            </div>
                            @if($rental->return_date)
                                <div class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded text-center mt-1">
                                    {{ __('rental.calendar.returned_on') }}: {{ $rental->return_date->format('d/m/y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">{{ __('rental.calendar.no_rented_products') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="uk-card uk-card-default shadow-sm rounded-lg overflow-hidden">
            <div class="grid grid-cols-7 divide-x divide-y border border-gray-200">
                @foreach([__('rental.calendar.mon'), __('rental.calendar.tue'), __('rental.calendar.wed'), __('rental.calendar.thu'), __('rental.calendar.fri'), __('rental.calendar.sat'), __('rental.calendar.sun')] as $day)
                    <div class="p-3 text-center text-sm font-semibold bg-blue-50 border-b text-blue-800">
                        {{ $day }}
                    </div>
                @endforeach

                @php
                    $currentDate = now();
                    $startOfMonth = $currentDate->copy()->startOfMonth();
                    $endOfMonth = $currentDate->copy()->endOfMonth();
                    $currentDay = $startOfMonth->copy()->startOfWeek();
                    $activeRentals = $rentals->filter(fn($rental) => !$rental->return_date);
                @endphp

                @while($currentDay <= $endOfMonth->copy()->endOfWeek())
                    <div class="bg-white p-3 min-h-[120px] relative group hover:bg-gray-50 transition-all {{ !$currentDay->isSameMonth($startOfMonth) ? 'bg-gray-50' : '' }}">
                        <span class="inline-block px-2 py-1 rounded-full text-sm {{ !$currentDay->isSameMonth($startOfMonth) ? 'text-gray-400' : 'text-gray-700' }} {{ $currentDay->isToday() ? 'bg-blue-100 font-bold' : '' }}">
                            {{ $currentDay->format('j') }}
                        </span>

                        <div class="space-y-1 mt-1">
                            @foreach($activeRentals as $rental)
                                @if($currentDay->between($rental->rental_start, $rental->rental_end))
                                    <div class="p-1.5 rounded border {{ $currentDay->isSameDay($rental->rental_start) ? 'border-l-4 border-l-green-500' : ($currentDay->isSameDay($rental->rental_end) ? 'border-r-4 border-r-red-500' : 'border-blue-200') }} bg-white hover:bg-blue-50 transition-colors shadow-sm">
                                        <a href="{{ route('advertisement', $rental->advertisement->id) }}" class="block truncate text-sm hover:text-blue-700">
                                            <span class="text-gray-700">{{ $rental->advertisement->title }}</span>
                                            @if($currentDay->isSameDay($rental->rental_end))
                                                <span class="text-xs text-red-500 font-medium block">({{ __('rental.calendar.return') }})</span>
                                            @endif
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @php $currentDay->addDay() @endphp
                @endwhile
            </div>
        </div>

        {{-- Upcoming Return Dates --}}
        <div class="uk-margin-large-top uk-margin-large-bottom">
            <div class="uk-card uk-card-default uk-card-body shadow-sm rounded-lg">
                <h3 class="text-2xl font-semibold mb-6 text-blue-800">{{ __('rental.calendar.upcoming_returns') }}</h3>
                <div class="grid gap-3">
                    @php
                        $upcomingReturns = $rentals->filter(fn($rental) => !$rental->return_date)
                            ->sortBy('rental_end')
                            ->take(5);
                    @endphp
                    @forelse($upcomingReturns as $rental)
                        <div class="p-4 rounded-lg border-2 border-blue-200 bg-gradient-to-r from-blue-50 to-white hover:border-blue-400 transition-colors">
                            <div class="text-lg text-blue-700 font-medium mb-2">
                                {{ $rental->advertisement->title }}
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <div class="bg-blue-50 px-3 py-1.5 rounded-full">
                                    {{ __('rental.calendar.start') }}: {{ $rental->rental_start->format('d M Y') }}
                                </div>
                                <div class="bg-red-50 px-3 py-1.5 rounded-full font-medium text-red-600">
                                    {{ __('rental.calendar.return_on') }}: {{ $rental->rental_end->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">{{ __('rental.calendar.no_upcoming_returns') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
