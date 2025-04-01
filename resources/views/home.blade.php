@extends('layouts.home')

@section('content')
    <x-header/>

    <div class="uk-container">

        {{-- SEARCH --}}
        <div class="uk-card uk-card-default uk-card-body uk-margin-medium-bottom">
            <form method="GET" action="{{ route('home') }}" class="uk-grid-small" uk-grid>
                <div class="uk-width-1-1">
                    <h3 class="uk-card-title">Search Advertisements</h3>
                </div>

                <div class="uk-width-1-1 uk-width-1-2@s">
                    <label class="uk-form-label" for="search">Search by title</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" type="text" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Enter keywords...">
                    </div>
                </div>

                <div class="uk-width-1-1 uk-width-1-2@s">
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

                <div class="uk-width-1-1">
                    <label class="uk-form-label">Price range:
                        <span id="price-min">{{ $current_min }}</span> -
                        <span id="price-max">{{ $current_max }}</span>
                    </label>
                    <div class="uk-form-controls">
                        <div class="uk-margin uk-grid-small" uk-grid>
                            <div class="uk-width-1-2">
                                <input class="uk-range" type="range" name="min_price" id="min_price"
                                       min="{{ $min_price }}" max="{{ $max_price }}"
                                       value="{{ $current_min }}"
                                       oninput="document.getElementById('price-min').textContent = this.value">
                            </div>
                            <div class="uk-width-1-2">
                                <input class="uk-range" type="range" name="max_price" id="max_price"
                                       min="{{ $min_price }}" max="{{ $max_price }}"
                                       value="{{ $current_max }}"
                                       oninput="document.getElementById('price-max').textContent = this.value">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uk-width-1-1 uk-text-right">
                    <button type="submit" class="uk-button uk-button-primary">Search</button>
                    <a href="{{ route('home') }}" class="uk-button uk-button-default">Reset</a>
                </div>
            </form>
        </div>

        {{-- PRODUCT LIST --}}
        <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m" uk-grid>
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure max is always greater than min
            document.getElementById('min_price').addEventListener('input', function() {
                const minPrice = parseInt(this.value);
                const maxPrice = parseInt(document.getElementById('max_price').value);

                if (minPrice > maxPrice) {
                    document.getElementById('max_price').value = minPrice;
                    document.getElementById('price-max').textContent = minPrice;
                }
            });

            document.getElementById('max_price').addEventListener('input', function() {
                const maxPrice = parseInt(this.value);
                const minPrice = parseInt(document.getElementById('min_price').value);

                if (maxPrice < minPrice) {
                    document.getElementById('min_price').value = maxPrice;
                    document.getElementById('price-min').textContent = maxPrice;
                }
            });
        });
    </script>
@endsection
