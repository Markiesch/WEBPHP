@extends('layouts.app')

@section('heading')
    @lang('api.title')
        @endsection

        @section('content')
            <div class="uk-container">
                <h1 class="text-2xl font-bold pb-4">@lang('api.title')</h1>

                <div class="uk-card uk-card-body">
                    <p class="text-lg font-semibold">@lang('api.url_title')</p>
                    <p class="text-blue-500">{{route("api.advertisements.list", $business->id)}}</p>
                    <p class="text-blue-500">{{route("api.advertisements.show", [$business->id, 'id'])}}</p>

                    <a href="{{route("api.advertisements.list", $business->id)}}" target="_blank" class="uk-btn uk-btn-primary mt-4">
                        @lang('api.try_now')
                        <uk-icon icon="external-link" class="pl-2"></uk-icon>
                    </a>
                </div>
            </div>
        @endsection
