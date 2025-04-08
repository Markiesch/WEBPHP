@extends('layouts.home')

@section('content')
    <x-header/>
    <div class="uk-container py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Image Section -->
            <div class="lg:col-span-2">
                <div class="rounded-xl overflow-hidden shadow-lg bg-gray-50">
                    <img src="{{ asset($advertisement->image_url) }}"
                         alt="@lang('advertisement.image_alt', ['title' => $advertisement->title])"
                         class="w-full aspect-video object-cover hover:scale-105 transition-transform duration-300"/>
                </div>
            </div>

            <!-- Details Section -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <!-- Business Info -->
                <div class="flex items-center gap-2 text-gray-600 mb-4">
                    <uk-icon icon="shop" ratio="1.2" class="text-blue-600"></uk-icon>
                    <a href="{{ route('business-page', $advertisement->business->url) }}"
                       class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        {{ $advertisement->business->name }}
                    </a>
                </div>

                <!-- Title and Description -->
                <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $advertisement->title }}</h1>
                <p class="text-gray-600 mb-6 leading-relaxed">{{ $advertisement->description }}</p>

                <!-- Price Section -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6 shadow-inner">
                    @if($advertisement->type === 'auction')
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <span>@lang('advertisement.starting_price')</span>
                                <span class="font-medium">@lang('advertisement.currency_symbol'){{ number_format($advertisement->price, 2) }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-900 font-medium">@lang('advertisement.current_bid')</span>
                                <span class="text-2xl font-bold text-red-600">
                                    @lang('advertisement.currency_symbol'){{ number_format($advertisement->current_bid ?? $advertisement->price, 2) }}
                                </span>
                            </div>

                            @if($advertisement->auction_end_date)
                                <div class="pt-3 border-t">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">@lang('advertisement.ends_in')</span>
                                        <span class="font-semibold {{ $advertisement->daysUntilExpiry() < 2 ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ $advertisement->auction_end_date->diffForHumans() }}
                                        </span>
                                    </div>
                                    @if(!$advertisement->isAuctionEnded())
                                        <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-600 rounded-full transition-all duration-300"
                                                 style="width: {{ min(100, 100 - ($advertisement->daysUntilExpiry() / 7 * 100)) }}%">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-2">
                            <span class="text-3xl font-bold text-red-600">@lang('advertisement.currency_symbol'){{ number_format($advertisement->price, 2) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    @if ($advertisement->isPurchased())
                        <div class="bg-gray-100 rounded-lg p-4 text-center text-gray-600">
                            <uk-icon icon="check" ratio="1.2" class="text-green-600"></uk-icon>
                            <span class="ml-2 font-medium">@lang('advertisement.sold')</span>
                        </div>
                    @else
                        <button class="uk-btn uk-btn-large uk-btn-primary w-full rounded-lg transition-all duration-300
                                     hover:scale-[1.02] hover:shadow-md"
                                data-uk-toggle="target: #buy-modal">
                            <uk-icon icon="{{ $advertisement->type === 'auction' ? 'gavel' : 'shopping-cart' }}"
                                     ratio="1.2">
                            </uk-icon>
                            <span class="ml-2">
                                @lang($advertisement->type === 'auction' ? 'advertisement.place_bid' :
                                    ($advertisement->type === 'sale' ? 'advertisement.buy' : 'advertisement.rent'))
                            </span>
                        </button>
                    @endif

                    <form action="{{ route('advertisement.favorite', $advertisement->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="value" value="{{!$advertisement->is_favorited}}">
                        <button type="submit"
                                class="uk-btn uk-btn-large w-full rounded-lg border transition-all duration-300
                                       {{ $advertisement->is_favorited
                                           ? 'bg-red-50 border-red-200 text-red-600 hover:bg-red-100'
                                           : 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                 fill="{{ $advertisement->is_favorited ? 'currentColor' : 'none' }}"
                                 stroke="currentColor" stroke-width="2"
                                 class="inline-block mr-2 transition-transform duration-300 hover:scale-110">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                            </svg>
                            {{ $advertisement->is_favorited ? __('advertisement.favorited') : __('advertisement.favorite') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Purchase/Bid Modal -->
        <div class="uk-modal" id="buy-modal" data-uk-modal>
            <div class="uk-modal-dialog uk-margin-auto-vertical rounded-xl shadow-xl">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-6">{{ $advertisement->title }}</h2>
                    <form action="{{ $advertisement->type === 'auction' ? route('advertisements.bid', $advertisement->id) : route('advertisement.buy', $advertisement->id) }}"
                          method="POST">
                        @csrf
                        @if($advertisement->type === 'auction')
                            <div class="mb-6">
                                <label for="bid_amount" class="block text-sm font-medium mb-2">@lang('advertisement.your_bid')</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500">@lang('advertisement.currency_symbol')</span>
                                    <input type="number" name="bid_amount" id="bid_amount"
                                           class="uk-input pl-7"
                                           min="{{ ($advertisement->current_bid ?? $advertisement->price) + 1 }}"
                                           step="0.01"
                                           required>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    @lang('advertisement.minimum_bid'): @lang('advertisement.currency_symbol'){{ number_format(($advertisement->current_bid ?? $advertisement->price) + 1, 2) }}
                                </p>
                            </div>
                        @endif

                        <div class="flex gap-3">
                            <button type="button" class="uk-modal-close uk-btn uk-btn-default flex-1 hover:bg-gray-100">
                                @lang('common.cancel')
                            </button>
                            <button type="submit" class="uk-btn uk-btn-primary flex-1 hover:bg-blue-700">
                                @lang($advertisement->type === 'auction' ? 'advertisement.place_bid' :
                                    ($advertisement->type === 'sale' ? 'advertisement.buy' : 'advertisement.rent'))
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-container py-6">
        <div class="uk-card uk-card-body p-6 grid grid-cols-1 lg:grid-cols-3 gap-y-8 lg:gap-8">
            <div>
                <?php
                // Initialize reviews-related variables if they don't exist
                $total_reviews_count = $total_reviews_count ?? 0;
                $mean_rating = $mean_rating ?? 0;
                $reviews_count = $reviews_count ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                ?>
                <div class="flex gap-3 items-end pb-4">
                    <h2 class="text-6xl font-medium">{{ $mean_rating }}</h2>
                    <div>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                     fill="{{ $i <= $mean_rating ? '#eab308' : 'transparent' }}"
                                     stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     class="text-yellow-500">
                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                                </svg>
                            @endfor
                        </div>
                        <p>@lang('advertisement.average_of_reviews', ['count' => $total_reviews_count])</p>
                    </div>
                </div>

                @for($i = 5; $i > 0; $i--)
                    <form action="{{ route('advertisement', $advertisement->id) }}" method="GET">
                        <input type="hidden" name="rating" value="{{ $i }}">
                        @if (request('sort') !== null)
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <button type="submit" class="w-full flex gap-4 items-center">
                            <div class="flex justify-end flex-grow-0 w-[8rem]">
                                @for($y = 1; $y <= $i; $y++)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                         fill="currentColor" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="text-yellow-500">
                                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                                    </svg>
                                @endfor
                            </div>

                                <?php
                                $count = isset($reviews) ? $reviews->where('rating', $i)->count() : 0;
                                $percentage = $total_reviews_count == 0 ? 0 : $reviews_count[$i] / $total_reviews_count * 100;
                                ?>

                            <div class="flex-grow-1 w-full">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-yellow-500 h-full rounded-full"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <p>{{$reviews_count[$i]}}</p>
                        </button>
                    </form>
                @endfor

                @auth
                    <div class="mt-6 pt-4 border-t">
                        <h5 class="font-bold">@lang('advertisement.write_review')</h5>
                        <form action="{{ route('reviews.submit', $advertisement->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rating" class="form-label">@lang('advertisement.rating')</label>
                                <select class="uk-select" id="rating" name="rating" required>
                                    <option value="">@lang('advertisement.select_rating')</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">@lang("advertisement.rating_$i")</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">@lang('advertisement.comment')</label>
                                <textarea class="uk-textarea" id="comment" name="comment" rows="3"
                                          required></textarea>
                            </div>
                            <button type="submit"
                                    class="uk-btn uk-btn-secondary border w-full">@lang('advertisement.submit_review')</button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-info mt-3">
                        <a href="{{ route('login') }}">@lang('advertisement.login_to_review')</a>
                    </div>
                @endauth
            </div>
            <div class="col-span-2">
                <div class="flex justify-between items-end">
                    <h2 class="text-2xl font-bold">@lang('advertisement.reviews')</h2>
                    <div class="uk-form-controls">
                        <form id="sortForm" action="{{ route('advertisement', $advertisement->id) }}" method="GET">
                            @if (request('rating') !== null)
                                <input type="hidden" name="rating" value="{{ request('rating') }}">
                            @endif

                            <select class="uk-select" id="sort" name="sort"
                                    onchange="document.getElementById('sortForm').submit()">
                                <option value="date_desc" {{ ($currentSort ?? '') === 'date_desc' ? 'selected' : '' }}>
                                    @lang('advertisement.newest_first')
                                </option>
                                <option value="date_asc" {{ ($currentSort ?? '') === 'date_asc' ? 'selected' : '' }}>
                                    @lang('advertisement.oldest_first')
                                </option>
                                <option value="rating_asc" {{ ($currentSort ?? '') === 'rating_asc' ? 'selected' : '' }}>
                                    @lang('advertisement.rating_low_high')
                                </option>
                                <option value="rating_desc" {{ ($currentSort ?? '') === 'rating_desc' ? 'selected' : '' }}>
                                    @lang('advertisement.rating_high_low')
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
                @if(isset($reviews) && $reviews->count() > 0)
                    @foreach($reviews as $review)
                        <div class="flex justify-between pt-6">
                            <div>
                                <h2 class="font-bold pb-1">{{ $review->user->name }} @if(auth()->id() === $review->user->id)
                                        (@lang('advertisement.you'))
                                    @endif</h2>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             viewBox="0 0 24 24"
                                             fill="{{ $i <= $review->rating ? '#eab308' : 'transparent' }}"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="text-yellow-500">
                                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <div class="flex gap-2">
                                @if(auth()->id() === $review->user->id)
                                    <form action="{{ route('reviews.delete', [$advertisement->id, $review->id]) }}"
                                          method="POST">
                                        @csrf
                                        <button class="uk-btn uk-btn-icon uk-btn-destructive uk-btn-xs">
                                            <uk-icon icon="trash"></uk-icon>
                                        </button>
                                    </form>
                                @endif
                                <p class="text-muted-foreground">{{ $review->created_at->format(__('advertisement.date_format')) }}</p>
                            </div>
                        </div>
                        <p class="pt-2">{{ $review->comment }}</p>
                    @endforeach

                    @if($advertisement->isAuction() && $advertisement->bids->isNotEmpty())
                        <div class="mt-6 pt-4 border-t">
                            <h3 class="text-xl font-semibold mb-4">@lang('advertisement.bid_history')</h3>
                            <div class="bg-white rounded-lg shadow divide-y">
                                @foreach($advertisement->bids()->with('user')->latest()->take(10)->get() as $bid)
                                    <div class="p-4 flex justify-between items-center hover:bg-gray-50">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">{{ substr($bid->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium">{{ $bid->user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $bid->created_at->format(__('advertisement.datetime_format')) }}</p>
                                            </div>
                                        </div>
                                        <span class="text-lg font-semibold">@lang('advertisement.currency_symbol'){{ number_format($bid->amount, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if($advertisement->bids->count() > 10)
                                <p class="text-center text-sm text-gray-500 mt-4">
                                    @lang('advertisement.showing_recent_bids', ['count' => 10, 'total' => $advertisement->bids->count()])
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Pagination Links -->
                    <div class="mt-6 pt-4">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <p class="text-muted-foreground">@lang('advertisement.no_reviews_yet')</p>
                @endif
            </div>
        </div>
    </div>
@endsection
