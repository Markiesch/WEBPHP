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

        {{-- Business Reviews Section --}}
        <div class="mt-12">
            <div class="uk-card uk-card-body">
                <h2 class="text-2xl font-bold mb-6">Reviews</h2>

                {{-- Review Form --}}
                @auth
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
                        <form action="{{ route('business.reviews.submit', $business->id) }}" method="POST">
                            @csrf
                            <div class="grid gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Rating</label>
                                    <select name="rating" class="uk-select" required>
                                        <option value="">Select rating</option>
                                        @foreach(range(5, 1) as $rating)
                                            <option value="{{ $rating }}">{{ $rating }} stars</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Your Review</label>
                                    <textarea
                                        name="content"
                                        rows="3"
                                        class="uk-textarea"
                                        placeholder="Share your experience..."
                                        required
                                    ></textarea>
                                </div>

                                <div>
                                    <button type="submit" class="uk-btn uk-btn-primary w-full">
                                        Submit Review
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg text-center">
                        <p>Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to leave a review.</p>
                    </div>
                @endauth

                {{-- Reviews List --}}
                <div class="space-y-6">
                    @forelse($business->reviews()->latest()->get() as $review)
                        <div class="p-4 border rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold">
                                            {{ $review->user->name }}
                                            @if(auth()->id() === $review->user_id)
                                                <span class="text-sm text-gray-500">(You)</span>
                                            @endif
                                        </h3>
                                        <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex text-yellow-500 mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4"
                                                 viewBox="0 0 24 24"
                                                 fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}"
                                                 stroke="currentColor">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                @if(auth()->id() === $review->user_id)
                                    <form action="{{ route('business.reviews.delete', [$business->id, $review->id]) }}"
                                          method="POST"
                                          class="ml-4">
                                        @csrf
                                        <button type="submit"
                                                class="uk-btn uk-btn-link text-red-500 hover:text-red-700"
                                                onclick="return confirm('Are you sure you want to delete this review?')">
                                            <uk-icon icon="trash"></uk-icon>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <p class="mt-3 text-gray-700">{{ $review->content }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>No reviews yet. Be the first to review!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
