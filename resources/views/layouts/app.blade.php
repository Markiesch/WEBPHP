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
                        'dashboard' => ['label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                        'contracts.index' => ['label' => 'Contracts', 'icon' => 'file-text'],
                        'advertisements.index' => ['label' => 'Advertisements', 'icon' => 'megaphone'],
                        'calendar' => ['label' => 'Calendar', 'icon' => 'calendar']
                    ];
                    ?>
                    @foreach (links as $route => $data)
                        <li class="{{ request()->routeIs($route) ? 'uk-active' : '' }}">
                            <a href="{{ route($route) }}" class="flex gap-2">
                                <uk-icon width="20" height="20" icon="{{ $data['icon'] }}"></uk-icon>
                                {{ __($data['label']) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <ul class="p-2 uk-nav uk-nav-primary">
                <li>
                    <a href="" class="flex gap-2">
                        <uk-icon width="20" height="20" icon="log-out"></uk-icon>
                        uitloggen
                    </a>
                </li>
            </ul>
            <div></div>
        </div>
        <div class="flex-grow bg-white m-2 ml-0 rounded-lg shadow border">
            <div class="border-b h-12 flex items-center px-4 lg:px-6">
                <h2 class="text-base font-medium">
                    @yield('heading')
                </h2>
            </div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endsection
