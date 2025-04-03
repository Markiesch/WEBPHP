@extends('layouts.home')

@section('content')
    <x-header/>
    <div class="uk-container grid grid-cols-3 pt-6 gap-8">
        <div class="col-span-2">
            <img src="{{ asset($advertisement->image_url) }}"
                 alt="{{ $advertisement->title }}"
                 class="w-full object-cover rounded-lg shadow-lg aspect-video bg-gray-500"/>
        </div>

        <div class="pt-6">
            <p>@lang('advertisement.listed_by'): <a href="{{ route('business-page', $advertisement->business->url)  }}"
                                                    class="text-blue-700 underline">{{ $advertisement->business->name }}</a>
            </p>
            <h1 class="text-3xl font-bold">{{ $advertisement->title }}</h1>
            <p class="pt-2">{{ $advertisement->description }}</p>
            <p class="pt-2 text-3xl font-bold text-red-600">&euro;{{ number_format($advertisement->price, 2) }}</p>

            <div class="uk-modal" id="buy-modal" data-uk-modal>
                <div class="uk-modal-dialog uk-margin-auto-vertical uk-modal-body">
                    <form action="{{route("advertisement.buy", $advertisement->id)  }}" method="get">
                        <h2 class="uk-modal-title mb-4">{{ $advertisement->title }}</h2>
                        <button class="uk-modal-close uk-btn uk-btn-default w-full gap-1 mb-2" type="button">
                            <uk-icon icon="circle-x"></uk-icon>
                            {{__("cancel")}}
                        </button>
                        <button class="uk-btn uk-btn-md uk-btn-primary w-full gap-1"
                                data-uk-toggle="target: #buy-modal">
                            <uk-icon icon="shopping-cart"></uk-icon>
                            @if ($advertisement->type == "sale")
                                {{ __('advertisement.buy') }}
                            @endif
                            @if ($advertisement->type == "rental")
                                @lang('advertisement.rent')
                            @endif
                        </button>
                    </form>
                </div>
            </div>

            <div class="pt-4 grid gap-2">
                @if ($advertisement->isPurchased())
                    <button class="uk-btn uk-btn-md uk-btn-primary w-full gap-1">
                        <uk-icon icon="circle-x"></uk-icon>
                        {{ __('sold') }}
                    </button>
                @else
                    <button class="uk-btn uk-btn-md uk-btn-primary w-full gap-1" data-uk-toggle="target: #buy-modal">
                        <uk-icon icon="shopping-cart"></uk-icon>
                        @if ($advertisement->type == "sale")
                            {{ __('advertisement.buy') }}
                        @endif
                        @if ($advertisement->type == "rental")
                            @lang('advertisement.rent')
                                @endif
                    </button>
                @endif
                <form action="{{ route('advertisement.favorite', $advertisement->id) }}" method="POST">
                    @csrf
                    <input id="value" name="value" type="hidden" value="{{!$advertisement->is_favorited}}">
                    <button
                        type="submit"
                        class="uk-btn uk-btn-md w-full gap-1 border {{ $advertisement->is_favorited ? "uk-btn-destructive" : "uk-btn-ghost" }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="{{ $advertisement->is_favorited ? "currentColor" : "transparent" }}"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-crown-icon lucide-crown">
                            <path
                                d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"/>
                            <path d="M5 21h14"/>
                        </svg>
                        {{ $advertisement->is_favorited ? __('advertisement.favorited') : __('advertisement.favorite') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="uk-container py-6">
        <div class="uk-card uk-card-body p-6 grid grid-cols-1 lg:grid-cols-3 gap-y-8 lg:gap-8">
            <div>
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
                                    <path
                                        d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
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
                                        <path
                                            d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                                    </svg>
                                @endfor
                            </div>

                                <?php
                                $count = $reviews->where('rating', $i)->count();
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
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
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
                                <option value="date_desc" {{ $currentSort === 'date_desc' ? 'selected' : '' }}>
                                    @lang('advertisement.newest_first')
                                </option>
                                <option value="date_asc" {{ $currentSort === 'date_asc' ? 'selected' : '' }}>
                                    @lang('advertisement.oldest_first')
                                </option>
                                <option value="rating_asc" {{ $currentSort === 'rating_asc' ? 'selected' : '' }}>
                                    @lang('advertisement.rating_low_high')
                                </option>
                                <option value="rating_desc" {{ $currentSort === 'rating_desc' ? 'selected' : '' }}>
                                    @lang('advertisement.rating_high_low')
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
                @if($reviews->count() > 0)
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
                                            <path
                                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
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
                                <p class="text-muted-foreground">{{ $review->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <p class="pt-2">{{ $review->comment }}</p>
                    @endforeach

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
