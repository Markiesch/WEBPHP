@extends('layouts.app')

@section('heading')
    {{ __('Upload CSV') }}
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <div class="mb-4">
                <a href="{{ asset('assets/csv/testercsv.csv') }}" class="uk-btn uk-btn-secondary" download>
                    {{ __('Download Sample CSV Template') }}
                </a>
            </div>

            <form action="{{ route('advertisements.process-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="csv_file" class="block text-sm font-medium text-gray-700">{{ __('CSV File') }}</label>
                    <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('csv_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="uk-btn uk-btn-primary">
                        {{ __('Upload') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
