<header class="h-[75px] bg-white w-full flex items-center z-50 fixed top-0 shadow-md">
    <nav class="container mx-auto">
        <div class="row flex h-full items-center justify-between w-full">
            <div class="mr-4 h-full items-center flex">
                <a href="{{ route('home') }}" class="text-gray-900 text-4xl font-bold">{{ config('app.name') }}</a>
            </div>
            <div class="items-center flex">
                <ul class="list-none flex space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <li class="flex justify-center">
                                <a class="flex justify-center w-full text-center align-middle p-3 text-gray-900 hover:text-blue-800" href="{{ url('/dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                        @else
                            <li class="flex justify-center">
                                <a class="flex justify-center w-full text-center align-middle p-3 text-gray-900 hover:text-blue-800" href="{{ route('login') }}">
                                    Login
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="flex justify-center">
                                    <a class="flex justify-center w-full text-center align-middle p-3 text-gray-900 hover:text-blue-800" href="{{ route('register') }}">
                                        Register
                                    </a>
                                </li>
                            @endif
                        @endauth
                    @endif
                    <li class="flex justify-center">
                        <nav class="lang-selector relative">
                            <div class="wrapper">
                                <button class="flex items-center p-3 text-gray-900 hover:text-blue-800 focus:outline-none">
                                    <span class="mr-2">{{ strtoupper(app()->getLocale()) }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <ul class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded shadow-lg hidden">
                                    @foreach(config('app.available_locales') as $locale => $name)
                                        <li>
                                            <a href="{{ route('change-locale', $locale) }}" class="block px-4 py-2 text-gray-900 hover:bg-gray-100 {{ app()->getLocale() === $locale ? 'font-bold' : '' }}">
                                                <img class="inline w-4 h-4 mr-2" src="{{ asset('images/'.$locale.'.png') }}" alt="{{ $name }}">
                                                {{ $name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script>
    document.querySelector('.lang-selector button').addEventListener('click', function() {
        document.querySelector('.lang-selector ul').classList.toggle('hidden');
    });
</script>
