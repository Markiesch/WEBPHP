<header class="py-4 border-b">
    <div class="uk-container mx-auto px-4">
        <div class="flex items-center justify-between">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-2">
                <a href="/" class="uk-text-xl font-bold">Bazaar</a>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center space-x-4">
                <a href="/" class="uk-btn-text">{{ __('messages.home') }}</a>
                <a href="/products" class="uk-btn-text">{{ __('messages.products') }}</a>
                <a href="/about" class="uk-btn-text">{{ __('messages.about') }}</a>
            </nav>

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
                <a href="{{ route('login') }}" class="uk-btn uk-btn-sm uk-btn-default">{{ __('messages.login') }}</a>
                <a href="{{ route('signup') }}" class="uk-btn uk-btn-sm uk-btn-primary">{{ __('messages.register') }}</a>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden py-4 bg-blue-600">
        <div class="uk-container mx-auto px-4">
            <nav class="flex flex-col space-y-2">
                <a href="/" class="uk-btn-text text-white hover:text-blue-200 transition">{{ __('messages.home') }}</a>
                <a href="/products" class="uk-btn-text text-white hover:text-blue-200 transition">{{ __('messages.products') }}</a>
                <a href="/about" class="uk-btn-text text-white hover:text-blue-200 transition">{{ __('messages.about') }}</a>
            </nav>
        </div>
    </div>
</header>
