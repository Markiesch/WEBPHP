@extends('layouts.home')

@section('content')
    <x-header/>
    <div class="uk-container grid grid-cols-2 pt-6">
        <div>
            <img src="{{ asset($advertisement->image_url) }}"
                 alt="{{ $advertisement->title }}"
                 class="w-full object-cover rounded-lg shadow-lg aspect-video bg-gray-500"/>
        </div>

        <div class="pl-12 pt-6">
            <p>Listed by: <a href="/" class="text-blue-700 underline">{{ $advertisement->user->name }}</a></p>
            <h1 class="text-3xl font-bold">{{ $advertisement->title }}</h1>
            <p class="pt-2">{{ $advertisement->description }}</p>
            <p class="pt-2 text-3xl font-bold text-red-600">&euro;{{ number_format($advertisement->price, 2) }}</p>

            <div class="pt-4 grid gap-2">
                <button class="uk-btn uk-btn-md uk-btn-primary w-full gap-1">
                    <uk-icon icon="shopping-cart"></uk-icon>
                    Kopen
                </button>
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
                        {{ $advertisement->is_favorited ? "Favorited" : "Favorite" }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="uk-container py-6">
        <div class="uk-card uk-card-body p-6 grid grid-cols-1 lg:grid-cols-3 gap-y-8 lg:gap-8">
            <div>
                <div class="flex gap-3 items-end">
                    <?php
                    $mean = number_format($reviews->avg('rating'), 1)
                    ?>
                    <h2 class="text-6xl font-medium">{{ $mean }}</h2>
                    <div>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                     fill="{{ $i <= $mean ? '#eab308' : 'transparent' }}" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     class="text-yellow-500">
                                    <path
                                        d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                                </svg>
                            @endfor
                        </div>
                        <p>Gemiddelde van {{ $advertisement->reviews->count() }} reviews</p>
                    </div>
                </div>

                @auth
                    <div class="mt-6 pt-4 border-t">
                        <h5 class="font-bold">Write a Review</h5>
                        <form action="{{ route('reviews.submit', $advertisement->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <select class="uk-select" id="rating" name="rating" required>
                                    <option value="">Select rating</option>
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea class="uk-textarea" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="uk-btn uk-btn-secondary border w-full">Submit Review</button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-info mt-3">
                        <a href="{{ route('login') }}">Log in</a> to write a review.
                    </div>
                @endauth
            </div>
            <div class="col-span-2">
                <div class="flex justify-between items-end">
                    <h2 class="text-2xl font-bold">Reviews</h2>
                    <form id="sortForm" action="{{ route('advertisement', $advertisement->id) }}" method="GET">
                        <div class="uk-form-controls">
                            <select class="uk-select" id="sort" name="sort"
                                    onchange="document.getElementById('sortForm').submit()">
                                <option value="date_desc" {{ $currentSort === 'date_desc' ? 'selected' : '' }}>
                                    Newest first
                                </option>
                                <option value="date_asc" {{ $currentSort === 'date_asc' ? 'selected' : '' }}>
                                    Oldest first
                                </option>
                                <option value="rating_asc" {{ $currentSort === 'rating_asc' ? 'selected' : '' }}>
                                    Rating: Low to High
                                </option>
                                <option value="rating_desc" {{ $currentSort === 'rating_desc' ? 'selected' : '' }}>
                                    Rating: High to Low
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
                @if($reviews->count() > 0)
                    @foreach($reviews as $review)
                        <div class="flex justify-between pt-6">
                            <div>
                                <h2 class="font-bold pb-1">{{ $review->user->name }} @if(auth()->id() === $review->user->id)
                                        (you)
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
                                    <form action="{{ route('reviews.delete', [$advertisement->id, $review->id]) }}" method="POST">
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
                    <p class="text-muted">No reviews yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
