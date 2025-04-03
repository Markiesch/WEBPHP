@extends('layouts.home')

@section('content')
    <x-header/>
    <div class="uk-container grid grid-cols-3 pt-6 gap-8">
        {{ $advertisements }}
    </div>
@endsection
