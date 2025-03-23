@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                {{ __('Dashboard') }}
            </h2>
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        {{ __("You're logged in!") }}
                    </div>
                </div>


                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('What can you do?') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                            <div class="bg-blue-100 p-4 rounded-lg">
                                <p class="text-blue-700">Upload a contract</p>
                            </div>
                            <div class="bg-yellow-100 p-4 rounded-lg">
                                <p class="text-yellow-700">Make a advertisement</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Quick Links') }}</h3>
                        <div class="mt-4 flex space-x-4">
                            <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-800">{{ __('Profile') }}</a>
                            <a href="{{ route('contracts.showUploadForm') }}" class="text-blue-600 hover:text-blue-800">{{ __('Upload Contract') }}</a>
                            <a href="{{ route('advertisements.create') }}" class="text-blue-600 hover:text-blue-800">{{ __('Create Advertisement') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
