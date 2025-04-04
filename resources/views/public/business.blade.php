@extends('layouts.home')

@section('content')
    <x-header/>
    <div class="uk-container py-6">
        {{-- Business Details --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-4">{{ $business->name }}</h1>
            <div class="flex items-center gap-4 text-muted-foreground">
                <span>Type: {{ ucfirst($business->type) }}</span>
                <span>KvK: {{ $business->kvk_number }}</span>
            </div>
        </div>

        {{-- Business Content Blocks --}}
        @if($blocks->count() > 0)
            @foreach($blocks as $block)
                @include('components.business-blocks.' . $block->type, ['block' => $block])
            @endforeach
        @endif

        {{-- Business Advertisements --}}
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Advertisements</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($advertisements as $advertisement)
                    <x-public.advertisement-card :advertisement="$advertisement"/>
                @empty
                    <p class="text-muted-foreground">No advertisements available.</p>
                @endforelse
            </div>
        </div>

        {{-- Business Reviews --}}
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Reviews</h2>

            @auth
                <form action="{{ route('business.reviews.submit', $business->id) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="grid gap-4">
                        <div>
                            <label class="uk-form-label">Rating</label>
                            <select name="rating" class="uk-select">
                                @foreach(range(1, 5) as $rating)
                                    <option value="{{ $rating }}">{{ $rating }} stars</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="uk-form-label">Your Review</label>
                            <textarea name="content" rows="3" class="uk-textarea" required></textarea>
                        </div>

                        <div>
                            <button type="submit" class="uk-btn uk-btn-primary">Post Review</button>
                        </div>
                    </div>
                </form>
            @else
                <p class="mb-6">Please <a href="{{ route('login') }}" class="text-blue-500">login</a> to leave a review.</p>
            @endauth

            <div class="space-y-6">
                @forelse($business->reviews()->latest()->get() as $review)
                    <div class="uk-card">
                        <div class="flex justify-between">
                            <div>
                                <h3 class="font-bold">{{ $review->user->name }}
                                    @if(auth()->id() === $review->user_id)
                                        (You)
                                    @endif
                                </h3>
                                <div class="flex text-yellow-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             viewBox="0 0 24 24"
                                             fill="{{ $i <= $review->rating ? '#eab308' : 'transparent' }}"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <div class="flex gap-2 items-start">
                                @if(auth()->id() === $review->user_id)
                                    <form action="{{ route('business.reviews.delete', [$business->id, $review->id]) }}" method="POST">
                                        @csrf
                                        <button class="uk-btn uk-btn-icon uk-btn-destructive uk-btn-xs">
                                            <uk-icon icon="trash"></uk-icon>
                                        </button>
                                    </form>
                                @endif
                                <span class="text-muted-foreground">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <p class="mt-2">{{ $review->content }}</p>
                    </div>
                @empty
                    <p class="text-muted-foreground">No reviews yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
