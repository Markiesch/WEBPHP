@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $advertisement->title }}</h1>
                            <div class="flex items-center space-x-2 mb-6">
                                @if($advertisement->type !== 'auction')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        €{{ number_format($advertisement->price, 2) }}
                                    </span>
                                @endif
                                <span class="px-3 py-1 {{
                                    $advertisement->type === 'sale' ? 'bg-blue-100 text-blue-800' :
                                    ($advertisement->type === 'auction' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')
                                }} rounded-full text-sm font-medium">
                                    {{ __($advertisement->type === 'sale' ? 'For Sale' :
                                         ($advertisement->type === 'auction' ? 'Auction' : 'For Rent')) }}
                                </span>
                                <span class="px-3 py-1 {{ $advertisement->wear_percentage > 50 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }} rounded-full text-sm font-medium">
                                    {{ $advertisement->wear_percentage }}% {{ __('Wear') }}
                                </span>
                                <span class="text-gray-500 text-sm">
                                    {{ __('Posted on') }} {{ $advertisement->created_at->format('F j, Y') }}
                                </span>
                            </div>

                            @if($advertisement->type === 'rental')
                                <div class="mb-6 bg-gray-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Rental Details') }}</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-4 text-gray-600">
                                            <div>
                                                <span class="block text-sm font-medium">{{ __('Start Date') }}</span>
                                                <span class="text-sm">{{ $advertisement->rental_start_date->format('F j, Y') }}</span>
                                            </div>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                            <div>
                                                <span class="block text-sm font-medium">{{ __('End Date') }}</span>
                                                <span class="text-sm">{{ $advertisement->rental_end_date->format('F j, Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <span class="text-sm font-medium mr-2">{{ __('Wear Per Day:') }}</span>
                                            <span class="px-3 py-1 {{ $advertisement->wear_per_day > 1 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }} rounded-full text-sm font-medium">
                                                {{ number_format($advertisement->wear_per_day, 2) }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($advertisement->type === 'auction')
                                <div class="mb-6 bg-gray-50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Auction Details') }}</h3>
                                    <div class="space-y-4">
                                        <div class="flex items-center space-x-4 text-gray-600">
                                            <div>
                                                <span class="block text-sm font-medium">{{ __('Starting Price') }}</span>
                                                <span class="text-sm">€{{ number_format($advertisement->starting_price, 2) }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-sm font-medium">{{ __('Current Bid') }}</span>
                                                <span class="text-sm">€{{ number_format($advertisement->current_bid ?? $advertisement->starting_price, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="text-gray-600">
                                            <span class="block text-sm font-medium">{{ __('Ends At') }}</span>
                                            <span class="text-sm">{{ $advertisement->auction_end_date->format('F j, Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="prose max-w-none mb-8">
                                <p class="text-gray-700 leading-relaxed">{{ $advertisement->description }}</p>
                            </div>
                        </div>

                        @if($advertisement->image_url)
                            <div class="flex justify-center items-start">
                                <img src="{{ asset($advertisement->image_url) }}"
                                     alt="{{ $advertisement->title }}"
                                     class="w-full max-w-md object-cover rounded-lg shadow-lg"/>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('QR Code') }}</h2>
                        <div class="flex justify-center bg-gray-50 rounded-lg p-6">
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <img src="{{ $advertisement->getQrCodeDataUri() }}"
                                     alt="QR Code"
                                     class="w-48 h-48 object-contain"/>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('advertisements.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{ __('Back to Advertisements') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
