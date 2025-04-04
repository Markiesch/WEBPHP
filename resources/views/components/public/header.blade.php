<header class="py-4 border-b">
    <div class="uk-container mx-auto px-4">
        <div class="flex items-center justify-between">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-2">
                <a href="/" class="uk-text-xl font-bold">Bazaar</a>
            </div>

            <!-- Language Switcher and Auth Buttons -->
            <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="relative uk-dropdown-container">
                    <button class="uk-btn uk-btn-sm uk-btn-default" type="button">
                        {{ strtoupper(app()->getLocale()) }}
                        <span uk-icon="icon: triangle-down"></span>
                    </button>
                    <div class="uk-dropdown uk-dropdown-bottom-right" uk-dropdown="mode: click">
                        <ul class="uk-nav uk-dropdown-nav">
                            @foreach($availableLocales as $locale => $name)
                                <li @if(app()->getLocale() == $locale) class="uk-active" @endif>
                                    <a href="{{ route('language.switch', $locale) }}">
                                        {{ $name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Auth Buttons -->
                @auth
                    <a href="{{ route('home') }}" class="uk-btn uk-btn-sm uk-btn-primary">{{ __('Store') }}</a>
                    @if(auth()->user()->hasRole(['private_advertiser', 'business_advertiser', 'super_admin']))
                        <a href="{{ route('business.index') }}" class="uk-btn uk-btn-sm uk-btn-primary">{{ __('business') }}</a>
                    @else
                        <a href="{{ route('purchase.history') }}" class="uk-btn uk-btn-sm uk-btn-primary">{{ __('purchase history') }}</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="uk-btn uk-btn-sm uk-btn-default">
                            {{ __('logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="uk-btn uk-btn-sm uk-btn-default">{{ __('login') }}</a>
                    <a href="{{ route('signup') }}" class="uk-btn uk-btn-sm uk-btn-primary">{{ __('register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</header>
