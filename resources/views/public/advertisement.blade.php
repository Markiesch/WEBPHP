@extends('layouts.home')

@section('content')
    <x-header/>

    <div class="uk-container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="uk-card mb-4">
                    <div class="uk-card-header bg-primary">
                        <h1 class="text-2xl font-bold">{{ $advertisement->title }}</h1>
                    </div>
                    <div class="uk-card-body">
                        <h5 class="card-title">Price: ${{ number_format($advertisement->price, 2) }}</h5>

                        <div class="mb-3">
                            <h6>Description:</h6>
                            <p class="card-text">{{ $advertisement->description }}</p>
                        </div>

                        <div class="mb-3">
                            <h6>Posted on:</h6>
                            <p class="card-text">{{ $advertisement->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="uk-card mb-4">
                    <div class="uk-card-header">
                        <h2 class="mb-0 h5">Reviews ({{ $advertisement->reviews->count() }})</h2>
                    </div>
                    <div class="uk-card-body">
                        @if($advertisement->reviews->count() > 0)
                            @foreach($advertisement->reviews as $review)
                                <div class="border-bottom mb-3 pb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>{{ $review->user->name }}</strong>
                                            <small
                                                class="text-muted ms-2">{{ $review->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }} text-warning"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No reviews yet.</p>
                        @endif

                        @auth
                            <div class="mt-4">
                                <h5>Write a Review</h5>
                                <form action="{{ route('advertisement.review', $advertisement->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating</label>
                                        <select class="form-select" id="rating" name="rating" required>
                                            <option value="">Select rating</option>
                                            <option value="5">5 - Excellent</option>
                                            <option value="4">4 - Very Good</option>
                                            <option value="3">3 - Good</option>
                                            <option value="2">2 - Fair</option>
                                            <option value="1">1 - Poor</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Comment</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Review</button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info mt-3">
                                <a href="{{ route('login') }}">Log in</a> to write a review.
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
