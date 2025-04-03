@extends('layouts.home')

@section('content')
    <x-header/>
    <div class="uk-container py-6">
        @if($blocks->count() > 0)
            @foreach($blocks as $block)
                @include('components.business-blocks.' . $block->type, ['block' => $block])
            @endforeach
        @else
            <div class="uk-container grid grid-cols-3 pt-6 gap-8">
                {{ $advertisements }}
            </div>
        @endif
    </div>
@endsection
