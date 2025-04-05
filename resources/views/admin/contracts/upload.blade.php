@extends('layouts.app')

@section('heading')
    {{ __('Upload Contract') }}
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('contracts.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Name') }}
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Email') }}
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contract" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Contract File') }}
                    </label>
                    <input type="file"
                           name="contract"
                           id="contract"
                           class="w-full px-6 py-4 border border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
                           required
                           accept=".pdf,.doc,.docx">
                    @error('contract')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="uk-btn uk-btn-primary">
                        {{ __('Upload Contract') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
