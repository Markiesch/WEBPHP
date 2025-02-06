<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bazaar - Online Marktplaats & Veilingen</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="antialiased">
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">

    @if (Route::has('login'))
        <div class="fixed top-0 right-0 px-6 py-4 flex items-center space-x-8">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Inloggen</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900">
                        Registreren
                    </a>
                @endif
            @endauth
        </div>
    @endif


    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-8">
                Welom bij <span class="text-primary-600">Bazaar</span>
            </h1>
            <p class="text-xl text-gray-600 mb-12 max-w-2xl mx-auto">
                Jouw online marktplaats voor kopen, verkopen, veilen en verhuren.
                Ontdek duizenden producten of plaats je eigen advertentie.
            </p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12 max-w-3xl mx-auto">
                <div class="flex flex-col items-center">
                    <div class="bg-primary-100 p-3 rounded-full mb-3">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Kopen</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-primary-100 p-3 rounded-full mb-3">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Verkopen</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-primary-100 p-3 rounded-full mb-3">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Veilen</span>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-primary-100 p-3 rounded-full mb-3">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">Verhuren</span>
                </div>
            </div>
        </div>
    </div>


    <footer class="absolute bottom-0 w-full py-4 text-center text-sm text-gray-500 bg-white/50 backdrop-blur-sm">
        &copy; {{ date('Y') }} Bazaar. Alle rechten voorbehouden.
    </footer>
</div>
</body>
</html>
