﻿@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ __('Upload Contract for') }} {{ $business->name }}</h1>
            <a href="{{ route('admin.contracts.index') }}" class="uk-btn uk-btn-secondary">
                {{ __('Back to List') }}
            </a>
        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <form action="{{ route('admin.contracts.upload.store', $business) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="contract_file">
                        {{ __('Contract PDF (Signed)') }}
                    </label>
                    <input type="file" name="contract_file" id="contract_file" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-gray-600 text-xs mt-1">{{ __('Upload the signed contract (PDF only, max 5MB)') }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        {{ __('Status') }}
                    </label>
                    <select name="status" id="status" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="pending">{{ __('Pending Review') }}</option>
                        <option value="approved">{{ __('approved') }}</option>
                        <option value="rejected">{{ __('rejected') }}</option>
                    </select>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="uk-btn uk-btn-primary">
                        {{ __('Upload Contract') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
