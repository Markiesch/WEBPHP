﻿@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                {{ __('Upload Contract') }}
            </h2>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('contracts.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full" required>
                        </div>
                        <div class="mb-4">
                            <label for="description"
                                   class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="mt-1 block w-full" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="contract"
                                   class="block text-sm font-medium text-gray-700">{{ __('Contract File') }}</label>
                            <input type="file" name="contract" id="contract" class="mt-1 block w-full" required>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                                {{ __('Upload') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
