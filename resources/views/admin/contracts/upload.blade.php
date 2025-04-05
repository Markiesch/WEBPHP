@extends('layouts.admin')

@section('heading')
    Upload Contract
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form action="{{ route('admin.contracts.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="business_id" class="block text-sm font-medium text-gray-700">Business</label>
                        <select name="business_id" id="business_id" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Select a business</option>
                            @foreach($businesses as $business)
                                <option value="{{ $business->id }}">{{ $business->name }}</option>
                            @endforeach
                        </select>
                        @error('business_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contract" class="block text-sm font-medium text-gray-700">Contract File (PDF)</label>
                        <input type="file" name="contract" id="contract" accept=".pdf"
                               class="mt-1 block w-full">
                        @error('contract')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="uk-button uk-button-primary">
                            Upload Contract
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
