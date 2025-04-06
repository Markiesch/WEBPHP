@extends('layouts.home')

@section('content')
    <x-header/>

    <h1 class="text-6xl font-bold text-center py-12">{{ __('bazaar') }}</h1>

    <div class="uk-container">
        {{-- SEARCH --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('home') }}" class="uk-grid-small" uk-grid>
                <div class="flex gap-4 items-end">
                    <div class="grow">
                        <label class="uk-form-label" for="search">{{ __('search.by_title') }}</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="{{ __('search.enter_keywords') }}">
                        </div>
                    </div>

                    <div>
                        <label class="uk-form-label" for="sort">{{ __('search.sort_by') }}</label>
                        <div class="uk-form-controls">
                            <select class="uk-select" id="sort" name="sort">
                                <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>
                                    {{ __('search.newest_first') }}
                                </option>
                                <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>
                                    {{ __('search.oldest_first') }}
                                </option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                    {{ __('search.price_low_high') }}
                                </option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                    {{ __('search.price_high_low') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="min-w-[10rem]">
                        <label class="uk-form-label">{{ __('search.price_range') }}</label>
                        <div class="uk-input">
                            <uk-input-range multiple min="{{ $min_price }}" max="{{ $max_price }}"
                                            value="{{$current_min}},{{$current_max}}" name="price_range"
                                            label></uk-input-range>
                        </div>
                    </div>

                    @if(auth()->check())
                        <div>
                            <input id="favorite" name="favorite" type="checkbox" class="uk-checkbox" {{ request('favorite') ? 'checked' : ''  }}>
                            <label class="uk-form-label" for="favorite">{{ __('search.favorites_only') }}</label>
                        </div>
                    @endif

                    <div class="uk-width-1-1 uk-margin-small-top uk-text-right border-s pl-4">
                        <button type="submit" class="uk-btn uk-btn-primary">{{ __('search.button') }}</button>
                        <a href="{{ route('home') }}" class="uk-btn uk-btn-default">{{ __('search.reset') }}</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- PRODUCT LIST --}}
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3">
            @forelse($advertisements as $ad)
                <x-public.advertisement-card :advertisement="$ad"/>
            @empty
                <div class="uk-width-1-1">
                    <div class="uk-alert uk-alert-warning">
                        <p>{{ __('search.no_results') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-8 flex justify-center">
            {{ $advertisements->links() }}
        </div>
    </div>
@endsection
