@extends('layouts.app')
@section('title', 'Bazaar - Home')

@section('content')
    <x-header />

    <h1>Bazaar</h1>
    <div class="card-header">{{ __('title') }}</div>
    <div class="card-header">{{ __('auth.failed') }}</div>

@endsection
