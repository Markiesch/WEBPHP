@extends('layouts.app')

@section('heading')
    Agenda
@endsection

@section('content')
    <div class="uk-container">

        <h1 class="text-2xl font-bold pb-4">Verhuurde producten</h1>

        @foreach($ads as $ad)
            <div class="uk-card uk-card-body flex justify-between">
                <div>

                    <h2 class="text-xl font-bold pb-2">{{$ad->title}}</h2>
                    <div class="flex gap-1 items-center pt-2">
                        <p>van</p>
                        <uk-icon icon="calendar"></uk-icon>
                        <p>{{ $ad->rental_start_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex gap-1 items-center pt-2">
                        <p>tot</p>
                        <uk-icon icon="calendar"></uk-icon>
                        <p>{{ $ad->rental_end_date->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('advertisements.show', $ad->id) }}" class="uk-btn uk-btn-default">bekijk product</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
