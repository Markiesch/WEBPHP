@extends('layouts.admin')

@section('heading')
    Contract Management
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900 flex justify-between items-center">
            <h2 class="text-xl font-semibold">Business Overview</h2>
            <a href="{{ route('admin.contracts.showUploadForm') }}" class="uk-button uk-button-primary">
                Upload New Contract
            </a>
        </div>
    </div>

    <x-table
        :headers="[
            'name' => ['label' => __('Business Name'), 'sortable' => true],
            'kvk_number' => ['label' => __('KvK Number'), 'sortable' => true],
            'phone' => ['label' => __('Phone')],
            'email' => ['label' => __('Email')],
            'address' => ['label' => __('Address')],
            'actions' => ['label' => __('Actions')]
        ]"
        :sort-by="request('sort_by')"
        :direction="request('direction')"
    >
        @foreach($businesses as $business)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 border-b border-gray-100">
                    <div class="font-medium">{{ $business->name }}</div>
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    {{ $business->kvk_number }}
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    {{ $business->phone }}
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    {{ $business->email }}
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    {{ $business->address }}
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.contracts.showUploadForm', ['business' => $business->id]) }}"
                           class="uk-button uk-button-small uk-button-primary">
                            Upload Contract
                        </a>

                        <form action="{{ route('admin.contracts.generate-pdf', $business) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            <button type="submit"
                                    class="uk-button uk-button-small uk-button-secondary">
                                Generate Contract
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach

        @if($businesses->isEmpty())
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    No businesses found
                </td>
            </tr>
        @endif
    </x-table>

    <div class="mt-4">
        <x-pagination :paginator="$businesses" />
    </div>
@endsection
