@extends('layouts.app')

@section('heading')
    {{ __('Create Advertisement') }}
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label for="title" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Title') }}
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                           value="{{ old('title') }}"
                           required>
                </div>

                <div>
                    <label for="description" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Description') }}
                    </label>
                    <textarea name="description"
                              id="description"
                              class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                              rows="4"
                              required>{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="price" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Price') }}
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                        <input type="number"
                               name="price"
                               id="price"
                               class="w-full pl-8 px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                               value="{{ old('price') }}"
                               step="0.01"
                               min="0"
                               required>
                    </div>
                </div>

                <div>
                    <label for="image" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Image') }}
                    </label>
                    <input type="file"
                           name="image"
                           id="image"
                           class="w-full px-6 py-4 border border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           required>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                        {{ __('Create Advertisement') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
