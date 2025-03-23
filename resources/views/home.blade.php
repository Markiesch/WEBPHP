@extends('layouts.app')

@section('content')
    <x-header />

    <div class="card-header">{{ __('title') }}</div>
    <a href="{{ url('dashboard') }}">Go to Dashboard</a>
@endsection

