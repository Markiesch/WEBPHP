@extends('layouts.app')

@section('heading')
    {{ __('Upload CSV') }}
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('advertisements.process-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        Upload a CSV file with the following columns: title, description, price, type, wear_percentage
                    </p>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            CSV File
                        </label>
                        <input type="file"
                               name="csv_file"
                               accept=".csv"
                               class="uk-input @error('csv_file') uk-form-danger @enderror">

                        @error('csv_file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="uk-btn uk-btn-primary">
                            Upload CSV
                        </button>

                        <a href="{{ route('advertisements.template') }}" class="uk-btn uk-btn-link">
                            Download Template
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
