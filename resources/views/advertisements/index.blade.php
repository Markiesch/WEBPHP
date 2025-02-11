@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                {{ __('Advertisements') }}
            </h2>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('advertisements.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                        {{ __('Create Advertisement') }}
                    </a>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($advertisements->isEmpty())
                        <p>{{ __('No advertisements found.') }}</p>
                    @else
                        <ul>
                            @foreach($advertisements as $advertisement)
                                <li class="mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $advertisement->title }}</h3>
                                    <p>{{ $advertisement->description }}</p>
                                    <p class="text-gray-600">{{ __('Price:') }} {{ $advertisement->price }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
