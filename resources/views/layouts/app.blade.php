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
                        'business.index' => ['label' => 'Business', 'icon' => 'store'],
                        'advertisements.index' => ['label' => 'Advertisements', 'icon' => 'megaphone'],
                        'agenda.index' => ['label' => 'Agenda', 'icon' => 'calendar'],
                        'contracts.index' => ['label' => 'Contracts', 'icon' => 'file-text'],
                        'api.index' => ['label' => 'API', 'icon' => 'webhook'],
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
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="flex gap-2">
                            <uk-icon width="20" height="20" icon="log-out"></uk-icon>
                            uitloggen
                        </a>
                    </form>
                </li>
            </ul>
            <div></div>
        </div>
        <div class="flex-grow bg-white m-2 ml-0 rounded-lg shadow border overflow-auto">
            <div class="bg-white z-10 border-b h-12 flex items-center px-4 lg:px-6 sticky top-0">
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
