@props(['advertisement'])

<div class="uk-card w-full overflow-hidden">
    <a href="{{ route('advertisement', $advertisement->id) }}">
        <div class="aspect-video bg-gray-400">
            @if($advertisement->image_url)
                <img src="{{ $advertisement->image_url }}" alt="{{ $advertisement->title }}"
                     class="h-full w-full object-cover">
            @else
                <div class="uk-height-medium uk-background-muted uk-flex uk-flex-center uk-flex-middle">
                    <span uk-icon="icon: image; ratio: 3"></span>
                </div>
            @endif
        </div>
        <div class="uk-card-body">
            <div>
                <div class="flex justify-between">
                    <h3 class="uk-card-title">{{ $advertisement->title }}</h3>
                    @if($advertisement->is_favorited)
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="text-destructive">
                            <path
                                d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"/>
                            <path d="M5 21h14"/>
                        </svg>
                    @endif
                </div>
                <p class="uk-text-meta">Price: {{ number_format($advertisement->price, 2) }}</p>
                <p class="uk-text-truncate">{{ $advertisement->description }}</p>
            </div>
        </div>
    </a>
</div>

