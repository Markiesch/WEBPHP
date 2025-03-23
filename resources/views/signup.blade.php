@extends('layouts.app')

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
                                    <h1 class="text-2xl font-bold">{{ __('create_account') }}</h1>
                                    <p class="text-balance text-muted-foreground">
                                        {{ __('signup_with_account') }}
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
                                    <label class="font-medium" for="name">{{ __('name') }}</label>
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
                                    <label class="font-medium" for="email">{{ __('email') }}</label>
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
                                    <label class="font-medium" for="password">{{ __('password') }}</label>
                                    <input class="uk-input" id="password" type="password" name="password" required/>
                                </div>

                                <!-- Account Type Toggle -->
                                <div class="flex flex-col gap-3">
                                    <input type="hidden" name="account_type" value="{{ $accountType ?? 'buyer' }}" id="account_type">

                                    <!-- Seller-specific fields -->
                                    <div id="seller_fields"
                                         class="{{ (isset($accountType) && $accountType == 'seller') ? '' : 'hidden' }} grid gap-3">
                                        <div class="grid gap-1">
                                            <div class="flex gap-4">
                                                <label class="flex items-center gap-2">
                                                    <input
                                                        type="radio"
                                                        name="seller_type"
                                                        value="particulier"
                                                        class="uk-radio"
                                                        checked
                                                    >
                                                    {{ __('private') }}
                                                </label>
                                                <label class="flex items-center gap-2">
                                                    <input
                                                        type="radio"
                                                        name="seller_type"
                                                        value="zakelijk"
                                                        class="uk-radio"
                                                    >
                                                    {{ __('business') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="w-full uk-btn uk-btn-primary">{{ __('signup') }}</button>

                                    <!-- Account Type Switch Button -->
                                    <div class="pt-2">
                                        @if(isset($accountType) && $accountType == 'seller')
                                            <a href="{{ route('signup', ['type' => 'buyer']) }}"
                                               class="uk-btn uk-btn-secondary w-full">
                                                {{ __('continue_as_buyer') }}
                                            </a>
                                        @else
                                            <a href="{{ route('signup', ['type' => 'seller']) }}"
                                               class="uk-btn uk-btn-secondary w-full">
                                                {{ __('continue_as_seller') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-center text-sm">
                                    {{ __('have_account') }}
                                    <a href="{{ route('login') }}" class="underline underline-offset-4">{{ __('login') }}</a>
                                </div>
                            </div>
                        </form>
                        <div class="relative hidden bg-muted md:block">
                            <img
                                src="/assets/placeholder.svg"
                                alt="{{ __('image_alt') }}"
                                class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary">
                    {{ __('terms_agreement') }} <a href="#">{{ __('terms_of_service') }}</a>
                    {{ __('and') }} <a href="#">{{ __('privacy_policy') }}</a>.
                </div>
            </div>
        </div>
    </div>
@endsection
