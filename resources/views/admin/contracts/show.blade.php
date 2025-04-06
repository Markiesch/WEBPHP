@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ __('Contract Review') }}: {{ $business->name }}</h1>
            <a href="{{ route('admin.contracts.index') }}" class="uk-btn uk-btn-secondary">
                {{ __('Back to List') }}
            </a>
        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Business Information') }}</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">{{ __('Business Name') }}:</p>
                        <p class="font-medium">{{ $business->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">{{ __('Email') }}:</p>
                        <p class="font-medium">{{ $business->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">{{ __('Phone') }}:</p>
                        <p class="font-medium">{{ $business->phone ?? __('N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">{{ __('Registration Date') }}:</p>
                        <p class="font-medium">{{ $business->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">{{ __('Contract Status') }}</h2>
                <div class="mb-2">
                <span class="px-2 py-1 rounded text-sm
                    @if($business->contract_status === 'approved') bg-green-100 text-green-800
                    @elseif($business->contract_status === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ __(ucfirst($business->contract_status ?? 'pending')) }}
                </span>
                </div>

                @if($business->contract_file)
                    <div class="mb-4">
                        <p class="text-gray-600">{{ __('Uploaded on') }}:</p>
                        <p class="font-medium">{{ $business->contract_updated_at ? $business->contract_updated_at->format('d M Y H:i') : __('N/A') }}</p>
                    </div>
                @else
                    <p class="text-gray-600">{{ __('No contract has been uploaded yet.') }}</p>

                    <div class="mt-4">
                        <a href="{{ route('admin.contracts.upload', $business) }}"
                           class="uk-btn uk-btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Upload Contract') }}
                        </a>
                    </div>
                @endif
            </div>

            @if($business->contract_file && $business->contract_status !== 'approved')
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4">{{ __('Review Contract') }}</h2>
                    <form action="{{ route('admin.contracts.update-status', $business) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                                {{ __('Update Status') }}
                            </label>
                            <select name="status" id="status" required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="approved">{{ __('Approve Contract') }}</option>
                                <option value="rejected">{{ __('Reject Contract') }}</option>
                            </select>
                        </div>

                        <div class="mb-4 rejection-reason hidden">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="rejection_reason">
                                {{ __('Rejection Reason') }}
                            </label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Update Status') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        const statusSelect = document.getElementById('status');
        const rejectionReasonDiv = document.querySelector('.rejection-reason');

        statusSelect.addEventListener('change', function() {
            if (this.value === 'rejected') {
                rejectionReasonDiv.classList.remove('hidden');
                document.getElementById('rejection_reason').setAttribute('required', 'required');
            } else {
                rejectionReasonDiv.classList.add('hidden');
                document.getElementById('rejection_reason').removeAttribute('required');
            }
        });
    </script>
@endsection
