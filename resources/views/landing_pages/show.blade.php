@extends('layouts.app')

@section('content')
    <div class="landing-page">
        <x-intro_text :data="$data"/>

        <x-featured_ads :data="$data">
            <p>No advertisements available.</p>
        </x-featured_ads>
    </div>
@endsection
