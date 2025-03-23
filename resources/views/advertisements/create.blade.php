@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">{{ __('Create Advertisement') }}</h1>
                    <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700">{{ __('Title') }}</label>
                            <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="w-full border-gray-300 rounded-md" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-gray-700">{{ __('Price') }}</label>
                            <input type="number" name="price" id="price" class="w-full border-gray-300 rounded-md" required>
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-gray-700">{{ __('Image') }}</label>
                            <input type="file" name="image" id="image" class="w-full border-gray-300 rounded-md" required>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">{{ __('Create Advertisement') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
