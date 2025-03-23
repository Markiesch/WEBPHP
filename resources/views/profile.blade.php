@extends('layouts.app')

@section("heading")
            {{ __('Profile') }}
@endsection

@section('content')
    <div class="uk-card uk-card-body">
        <div class="max-w-xl">
            <livewire:profile.update-profile-information-form/>
        </div>
    </div>

    <div class="uk-card uk-card-body">
        <div class="max-w-xl">
            <livewire:profile.update-password-form/>
        </div>
    </div>

    <div class="uk-card uk-card-body">
        <div class="max-w-xl">
            <livewire:profile.delete-user-form/>
        </div>
    </div>
@endsection
