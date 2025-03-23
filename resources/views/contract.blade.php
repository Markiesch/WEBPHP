@extends('layouts.app')

@section('heading')
    {{ __('Upload Contract') }}
@endsection

@section('content')
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                {{ __('Uploaded Contracts') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($contracts as $contract)
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <p><strong>{{ __('Name') }}:</strong> {{ $contract->name }}</p>
                        <p><strong>{{ __('Email') }}:</strong> {{ $contract->email }}</p>
                        <p><strong>{{ __('Description') }}:</strong> {{ $contract->description }}</p>
                        <form action="{{ route('export-pdf', $contract->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md">
                                {{ __('Export to PDF') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
