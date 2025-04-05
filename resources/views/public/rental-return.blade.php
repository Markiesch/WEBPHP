@extends('layouts.home')

@section('content')
    <x-header/>

    <h1 class="text-6xl font-bold text-center py-12">Return Rentals</h1>

    <div class="uk-container py-8">
        <div class="uk-flex uk-flex-middle uk-margin-medium-bottom">
            <div>
                <a href="{{ route('rental-calendar') }}" class="uk-btn uk-btn-default">
                    <span uk-icon="calendar" class="uk-margin-small-right"></span>
                    {{ __('Calendar View') }}
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p><span uk-icon="check" class="uk-margin-small-right"></span>{{ session('success') }}</p>
            </div>
        @endif

        <div class="uk-grid-medium uk-child-width-1-1" uk-grid>
            @forelse($activeRentals as $rental)
                <div>
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="p-6">
                            <div class="uk-grid-small" uk-grid>
                                <div class="uk-width-expand@s">
                                    <h3 class="text-xl font-bold uk-margin-remove-bottom">
                                        {{ $rental->advertisement->title }}
                                    </h3>
                                    <div class="uk-text-meta uk-margin-small-top">
                                        <span uk-icon="home" class="uk-margin-small-right"></span>
                                        {{ $rental->advertisement->business->name }}
                                    </div>
                                </div>
                                <div class="uk-width-auto@s uk-text-right@s">
                                    <span class="uk-label {{ $rental->is_overdue ? 'uk-label-danger' : 'uk-label-success' }}">
                                        <span uk-icon="{{ $rental->is_overdue ? 'warning' : 'clock' }}" class="uk-margin-small-right"></span>
                                        @if($rental->days_remaining > 0)
                                            {{ trans_choice(':count dag|:count dagen', $rental->days_remaining) }} {{ __('remaining') }}
                                        @else
                                            {{ trans_choice(':count dag|:count dagen', abs($rental->days_remaining)) }} {{ __('overdue') }}
                                        @endif
                                    </span>
                                    <div class="uk-text-meta uk-margin-small-top">
                                        <span uk-icon="calendar" class="uk-margin-small-right"></span>
                                        {{ $rental->start_date->format('d M Y') }} - {{ $rental->end_date->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-b-xl border-t uk-text-right">
                            <a href="{{ route('rental-return.create', $rental->transaction->id) }}"
                               class="uk-btn uk-btn-primary">
                                <span uk-icon="upload" class="uk-margin-small-right"></span>
                                {{ __('Return Now') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="p-6 uk-text-center">
                        <div class="uk-margin">
                            <span uk-icon="info" ratio="2"></span>
                        </div>
                        <h3 class="text-xl font-bold">{{ __('No Active Rentals') }}</h3>
                        <p class="text-gray-500">{{ __('You currently have no products to return') }}</p>
                        <div class="uk-margin-medium-top">
                            <a href="{{ route('home') }}" class="uk-btn uk-btn-primary uk-margin-small-right">
                                <span uk-icon="cart" class="uk-margin-small-right"></span>
                                {{ __('Browse Products') }}
                            </a>
                            <a href="{{ route('rental-calendar') }}" class="uk-btn uk-btn-default">
                                <span uk-icon="calendar" class="uk-margin-small-right"></span>
                                {{ __('View Calendar') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
