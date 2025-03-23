@extends('layouts.root')

@section('root')
    <div class="flex h-svh bg-muted">
        <div style="width: 15rem" class="p-2 flex h-full flex-col">
            <div class="flex flex-col gap-2 p-2">
                <p class="uk-text-xl font-bold">Bazaar</p>
            </div>
            <div class="flex flex-col gap-2 flex-1">
                <ul class="uk-nav uk-nav-primary">

                    <?php
                    const links = [
                        'dashboard' => 'Dashboard',
                        'contracts.index' => 'Contracts',
                        'advertisements.index' => 'Advertisements',
                        'calendar' => 'Calendar'
                    ];
                    ?>
                    @foreach (links as $route => $label)
                        <li class="{{ request()->routeIs($route) ? 'uk-active' : '' }}">
                            <a href="{{ route($route) }}">{{ __($label) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="flex flex-col gap-2 p-2">
                uitloggen
            </div>
            <div></div>
        </div>
        <div class="flex-grow bg-white m-2 ml-0 rounded-lg shadow-md">
            @yield('content')
        </div>
    </div>
@endsection
