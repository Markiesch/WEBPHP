<header class="py-4 border-b">
    <div class="uk-container mx-auto px-4">
        <div class="flex items-center justify-between">
            <!-- Logo and Brand -->
            <div class="flex items-center space-x-2">
                <a href="/" class="uk-text-xl font-bold">Bazaar</a>
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex items-center space-x-4">
                <a href="/" class="uk-btn-text">Home</a>
                <a href="/products" class="uk-btn-text">Products</a>
                <a href="/about" class="uk-btn-text">About</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center space-x-2">
                <a href="/login" class="uk-btn uk-btn-sm uk-btn-default">Login</a>
                <a href="/register" class="uk-btn uk-btn-sm uk-btn-primary">Register</a>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden py-4 bg-blue-600">
        <div class="uk-container mx-auto px-4">
            <nav class="flex flex-col space-y-2">
                <a href="/" class="uk-btn-text text-white hover:text-blue-200 transition">Home</a>
                <a href="/products" class="uk-btn-text text-white hover:text-blue-200 transition">Products</a>
                <a href="/about" class="uk-btn-text text-white hover:text-blue-200 transition">About</a>
            </nav>
        </div>
    </div>
</header>
