@props(['advertisement'])

<div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
    <div class="relative">
        <a href="{{ route('advertisement', $advertisement->id) }}">
            <div class="aspect-video bg-gray-100">
                @if($advertisement->image_url)
                    <img src="{{ $advertisement->image_url }}"
                         alt="{{ $advertisement->title }}"
                         class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full flex items-center justify-center">
                        <uk-icon icon="image" ratio="3" class="text-gray-400"></uk-icon>
                    </div>
                @endif
            </div>
        </a>

        @auth
            <form action="{{ route('advertisement.favorite', $advertisement->id) }}"
                  method="POST"
                  class="absolute top-2 right-2">
                @csrf
                <input type="hidden" name="value" value="{{!$advertisement->is_favorited}}">
                <button type="submit"
                        class="p-2 rounded-full {{ $advertisement->is_favorited
                            ? 'bg-red-50 text-red-600 hover:bg-red-100'
                            : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}
                        transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                         fill="{{ $advertisement->is_favorited ? 'currentColor' : 'none' }}"
                         stroke="currentColor" stroke-width="2"
                         class="transition-transform hover:scale-110">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </button>
            </form>
        @endauth
    </div>

    <div class="p-4">
        <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
            <uk-icon icon="shop" ratio="0.8" class="text-blue-600"></uk-icon>
            <a href="{{ route('business-page', $advertisement->business->url) }}"
               class="text-blue-600 hover:text-blue-800 transition-colors">
                {{ $advertisement->business->name }}
            </a>
        </div>

        <a href="{{ route('advertisement', $advertisement->id) }}"
           class="block group">
            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                {{ $advertisement->title }}
            </h3>

            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                {{ $advertisement->description }}
            </p>
        </a>

        <div class="flex justify-between items-end">
            <div>
                @if($advertisement->isPurchased())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        <uk-icon icon="check" ratio="0.8" class="mr-1"></uk-icon>
                        {{ __('sold') }}
                    </span>
                @else
                    @if($advertisement->type === 'auction')
                        <div class="space-y-1">
                            @if($advertisement->current_bid)
                                <p class="text-sm text-gray-500">@lang('advertisement.current_bid')</p>
                                <p class="text-xl font-bold text-red-600">
                                    &euro;{{ number_format($advertisement->current_bid, 2) }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500">@lang('advertisement.starting_price')</p>
                                <p class="text-xl font-bold text-red-600">
                                    &euro;{{ number_format($advertisement->price, 2) }}
                                </p>
                            @endif
                        </div>
                    @else
                        <p class="text-xl font-bold text-red-600">
                            &euro;{{ number_format($advertisement->price, 2) }}
                        </p>
                    @endif
                @endif
            </div>

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                {{ $advertisement->type === 'auction'
                    ? 'bg-blue-50 text-blue-700'
                    : ($advertisement->type === 'sale'
                        ? 'bg-green-50 text-green-700'
                        : 'bg-purple-50 text-purple-700') }}">
                @lang('advertisement.' . $advertisement->type)
            </span>
        </div>
    </div>
</div>
