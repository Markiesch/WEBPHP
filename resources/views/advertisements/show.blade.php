﻿@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">{{ $advertisement->title }}</h1>
                    <p class="mb-4">{{ $advertisement->description }}</p>
                    <p class="text-gray-600">{{ __('Price:') }} {{ $advertisement->price }}</p>
                    <div>
                        <img src = "{{ $qrTest }}" alt = "QR Code" />
                    </div

                    <div class="mt-4">
                        <a href="{{ route('advertisements.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                            {{ __('Back to Advertisements') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
