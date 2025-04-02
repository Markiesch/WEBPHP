@extends('layouts.home')

@section('content')
    <div class="flex min-h-svh flex-col items-center justify-center bg-muted p-6 md:p-10">
        <div class="w-full max-w-sm md:max-w-3xl">
            <div class="flex flex-col gap-6">
                <div class="uk-card overflow-hidden">
                    <div class="uk-card-body grid p-0 md:grid-cols-2">
                        <form method="POST" action="{{ route('login.submit') }}" class="p-6 md:p-8">
                            @csrf
                            <div class="flex flex-col gap-6">
                                <div class="flex flex-col items-center text-center">
                                    <h1 class="text-2xl font-bold">Welcome Back</h1>
                                    <p class="text-balance text-muted-foreground">
                                        Login with your account
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
                                    <label class="font-medium" for="email">Email</label>
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
                                        <label class="font-medium" for="password">Password</label>
                                        <a
                                            href="#"
                                            class="ml-auto text-sm underline-offset-2 hover:underline"
                                        >
                                            Forgot Password?
                                        </a>
                                    </div>
                                    <input class="uk-input" id="password" type="password" name="password" required/>
                                </div>
                                <button type="submit" class="w-full uk-btn uk-btn-primary">
                                    Login
                                </button>
                                <div
                                    class="relative text-center text-sm after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
                                </div>

                                <div class="text-center text-sm">
                                    Don't have an account?
                                    <a href="{{ route('signup') }}"
                                       class="underline underline-offset-4">Register</a>
                                </div>
                            </div>
                        </form>
                        <div class="relative hidden bg-muted md:block">
                            <img
                                src="/assets/placeholder.svg"
                                alt="Login illustration"
                                class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary">
                    By continuing, you agree to our <a href="#">Terms of Service</a>
                    and <a href="#">Privacy Policy</a>.
                </div>
            </div>
        </div>
    </div>
@endsection
