@props(['advertisement'])

<div class="uk-card uk-card-default uk-card-hover" >
    <div class="uk-card-media-top">
        @if($advertisement->image_url)
            <img src="{{ $advertisement->image_url }}" alt="{{ $advertisement->title }}">
        @else
            <div class="uk-height-medium uk-background-muted uk-flex uk-flex-center uk-flex-middle">
                <span uk-icon="icon: image; ratio: 3"></span>
            </div>
        @endif
    </div>
    <div class="uk-card-body">
        <h3 class="uk-card-title">{{ $advertisement->title }}</h3>
        <p class="uk-text-meta">Price: {{ number_format($advertisement->price, 2) }}</p>
        <p class="uk-text-truncate">{{ $advertisement->description }}</p>
    </div>
    <div class="uk-card-footer">
        <a href="{{ route('advertisements.show', $advertisement->id) }}" class="uk-button uk-button-text">Read more</a>
    </div>
</div>
