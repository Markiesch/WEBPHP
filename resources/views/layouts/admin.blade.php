@extends('layouts.app')

@section('content')
    <div class="flex h-svh bg-muted">
        <div style="width: 15rem" class="p-2 flex h-full flex-col">
            <div class="flex flex-col gap-2 p-2">
                <p class="uk-text-xl font-bold">Bazaar</p>
            </div>
            <div class="flex flex-col gap-2 flex-1">
                <ul class="uk-nav uk-nav-primary">
                    <li class="uk-active"><a href="">test</a></li>
                    <li><a href="">test</a></li>
                    <li><a href="">test</a></li>
                    <li><a href="">test</a></li>
                </ul>
            </div>
            <div class="flex flex-col gap-2 p-2">
                uitloggen
            </div>
            <div></div>
        </div>
        <div class="flex-grow bg-white m-2 ml-0 rounded-lg shadow-md">
            @yield('admin')
        </div>
    </div>
@endsection
