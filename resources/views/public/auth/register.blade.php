@extends('layouts.home')

@section('content')
    <div class="flex min-h-svh flex-col items-center justify-center bg-muted p-6 md:p-10">
        <div class="w-full max-w-sm md:max-w-3xl">
            <div class="flex flex-col gap-6">
                <div class="uk-card overflow-hidden">
                    <div class="uk-card-body grid p-0 md:grid-cols-2">
                        <form method="POST" action="{{ route('signup.submit') }}" class="p-6 md:p-8">
                            @csrf
                            <div class="flex flex-col gap-6">
                                <div class="flex flex-col items-center text-center">
                                    <h1 class="text-2xl font-bold">Create Account</h1>
                                    <p class="text-balance text-muted-foreground">
                                        Sign up to get started
                                    </p>
                                </div>
                                @if ($errors->any())
                                    <div class="text-red-600">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="grid gap-1">
                                    <label class="font-medium" for="name">Name</label>
                                    <input
                                            class="uk-input"
                                            id="name"
                                            type="text"
                                            name="name"
                                            value="{{ old('name') }}"
                                            required
                                    />
                                </div>
                                <div class="grid gap-1">
                                    <label class="font-medium" for="email">Email</label>
                                    <input
                                            class="uk-input"
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            required
                                    />
                                </div>
                                <div class="grid gap-1">
                                    <label class="font-medium" for="password">Password</label>
                                    <input class="uk-input" id="password" type="password" name="password" required/>
                                </div>

                                <!-- Account Type Toggle -->
                                <input type="hidden" name="account_type" value="{{ $accountType ?? 'buyer' }}"
                                       id="account_type">

                                <!-- Seller-specific fields -->
                                <div id="seller_fields" class="{{ $accountType === 'seller' ? '' : 'hidden' }} grid gap-3">
                                    <div class="grid gap-1">
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="seller_type" value="particulier" class="uk-radio" checked onclick="toggleBusinessField()">
                                                Private
                                            </label>
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="seller_type" value="zakelijk" class="uk-radio" onclick="toggleBusinessField()">
                                                Business
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Business Name Field -->
                                <div id="business_name_field" class="grid gap-1 hidden">
                                    <label class="font-medium" for="business_name">Business Name</label>
                                    <input
                                            class="uk-input"
                                            id="business_name"
                                            type="text"
                                            name="business_name"
                                            value="{{ old('business_name') }}"
                                    />
                                    @error('business_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <button type="submit" class="w-full uk-btn uk-btn-primary">Sign Up</button>
                                    <div class="pt-2">
                                        @if(isset($accountType) && $accountType === 'seller')
                                            <a href="{{ route('signup', ['type' => 'buyer']) }}" class="uk-btn uk-btn-secondary w-full">Continue as Buyer</a>
                                        @else
                                            <a href="{{ route('signup', ['type' => 'seller']) }}" class="uk-btn uk-btn-secondary w-full">Continue as Seller</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-center text-sm">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="underline underline-offset-4">Login</a>
                                </div>
                            </div>
                        </form>
                        <div class="relative hidden bg-muted md:block">
                            <img
                                    src="/assets/placeholder.svg"
                                    alt="Registration illustration"
                                    class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                            />
                        </div>
                    </div>
                </div>
                <div class="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary">
                    By signing up, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                </div>
            </div>
        </div>
    </div>


    // Business name toggle
    <script>
        function toggleBusinessField() {
            const businessField = document.getElementById('business_name_field');
            const businessInput = document.getElementById('business_name');
            const isBusinessType = document.querySelector('input[name="seller_type"]:checked').value === 'zakelijk';

            businessField.classList.toggle('hidden', !isBusinessType);
            businessInput.required = isBusinessType;
        }
    </script>
@endsection
