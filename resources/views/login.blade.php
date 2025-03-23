@extends('layouts.app')
@section('title', 'Bazaar - Home')

@section('content')
    {{ app()->getLocale()}}
    {{ __('welcome') }}
    <div class="card-header">{{ __('auth.failed') }}</div>

    <div class="flex min-h-svh flex-col items-center justify-center bg-muted p-6 md:p-10">
        <div class="w-full max-w-sm md:max-w-3xl">
            <div class="flex flex-col gap-6">
                <div class="uk-card overflow-hidden">
                    <div class="uk-card-body grid p-0 md:grid-cols-2">
                        <form method="POST" action="{{ route('login.submit') }}" class="p-6 md:p-8">
                            @csrf
                            <div class="flex flex-col gap-6">
                                <div class="flex flex-col items-center text-center">
                                    <h1 class="text-2xl font-bold">Welkom terug</h1>
                                    <p class="text-balance text-muted-foreground">
                                        Login met uw Bazaar account
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
                                    <label class="font-medium" for="email">E-mail</label>
                                    <input
                                        class="uk-input"
                                        id="email"
                                        type="email"
                                        name="email"
                                        required
                                    />
                                </div>
                                <div class="grid gap-1">
                                    <div class="flex items-center">
                                        <label class="font-medium" for="password">Wachtwoord</label>
{{--                                            href="{{ route('password.request') }}"--}}
                                        <a
                                            href="#"
                                            class="ml-auto text-sm underline-offset-2 hover:underline"
                                        >
                                            Wachtwoord vergeten?
                                        </a>
                                    </div>
                                    <input class="uk-input" id="password" type="password" name="password" required/>
                                </div>
                                <button type="submit" class="w-full uk-btn uk-btn-primary">
                                    Login
                                </button>
                                <div
                                    class="relative text-center text-sm after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
                                    <!-- Optional: Add some text or elements here -->
                                </div>

                                <div class="text-center text-sm">
                                    Don&apos;t have an account?
                                    <a href="{{ route('signup') }}" class="underline underline-offset-4">Registreren</a>
{{--                                    <a href="{{ route('register') }}" class="underline underline-offset-4">Registreren</a>--}}
                                </div>
                            </div>
                        </form>
                        <div class="relative hidden bg-muted md:block">
                            <img
                                src="/assets/placeholder.svg"
                                alt="Image"
                                class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary">
                    By clicking continue, you agree to our <a href="#">Terms of Service</a>
                    and <a href="#">Privacy Policy</a>.
                </div>
            </div>
        </div>
    </div>
@endsection

