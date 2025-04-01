@extends('layouts.home')

@section('content')
    <x-header/>


    <h1 class="text-6xl font-bold text-center py-12">BAZAAR</h1>

    <div class="uk-container">
        {{-- SEARCH --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('home') }}" class="uk-grid-small" uk-grid>
                <div class="flex gap-4 items-end">
                    <div class="grow">
                        <label class="uk-form-label" for="search">Search by title</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Enter keywords...">
                        </div>
                    </div>

                    <div>
                        <label class="uk-form-label" for="sort">Sort by</label>
                        <div class="uk-form-controls">
                            <select class="uk-select" id="sort" name="sort">
                                <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>
                                    Newest first
                                </option>
                                <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>
                                    Oldest first
                                </option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                    Price: Low to High
                                </option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                    Price: High to Low
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="min-w-[10rem]">
                        <label class="uk-form-label">Price range:</label>
                        <div class="uk-input">
                            <uk-input-range multiple min="{{ $min_price }}" max="{{ $max_price }}" value="{{$current_min}},{{$current_max}}" name="price_range" label></uk-input-range>
                        </div>
                    </div>

                    <div class="uk-width-1-1 uk-margin-small-top uk-text-right border-s pl-4">
                        <button type="submit" class="uk-btn uk-btn-primary">Search</button>
                        <a href="{{ route('home') }}" class="uk-btn uk-btn-default">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- PRODUCT LIST --}}
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3" data-uk-grid="masonry: true">
            @forelse($advertisements as $ad)
                <div>
                    <x-home.advertisement-card :advertisement="$ad"/>
                </div>
            @empty
                <div class="uk-width-1-1">
                    <div class="uk-alert uk-alert-warning">
                        <p>No advertisements found matching your criteria.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
