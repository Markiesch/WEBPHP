@props(['advertisement'])

<div class="uk-card w-full overflow-hidden">
    <div class="aspect-video bg-gray-400">
        @if($advertisement->image_url)
            <img src="{{ $advertisement->image_url }}" alt="{{ $advertisement->title }}" class="h-full w-full object-cover">
        @else
            <div class="uk-height-medium uk-background-muted uk-flex uk-flex-center uk-flex-middle">
                <span uk-icon="icon: image; ratio: 3"></span>
            </div>
        @endif
    </div>
    <div class="uk-card-body">
        <div>
            <h3 class="uk-card-title">{{ $advertisement->title }}</h3>
            <p class="uk-text-meta">Price: {{ number_format($advertisement->price, 2) }}</p>
            <p class="uk-text-truncate">{{ $advertisement->description }}</p>
        </div>
        <div>
            <a href="{{ route('advertisement', $advertisement->id) }}" class="uk-button uk-button-text">Read
                more</a>
        </div>
    </div>
</div>
