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
            <h2 class="text-xl font-semibold">Business Contracts Overview</h2>
            <a href="{{ route('admin.contracts.showUploadForm') }}" class="uk-button uk-button-primary">
                Upload New Contract
            </a>
        </div>
    </div>

    <x-table
        :headers="[
            'business' => ['label' => __('Business Name'), 'sortable' => true],
            'status' => ['label' => __('Status'), 'sortable' => true],
            'description' => ['label' => __('Description')],
            'reviewed_at' => ['label' => __('Review Date'), 'sortable' => true],
            'created_at' => ['label' => __('Upload Date'), 'sortable' => true],
            'actions' => ['label' => __('Actions')]
        ]"
        :sort-by="request('sort_by')"
        :direction="request('direction')"
    >
        @foreach($contracts as $contract)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 border-b border-gray-100">
                    <div class="font-medium">{{ $contract->business->name }}</div>
                    <div class="text-sm text-gray-500">{{ $contract->business->kvk_number }}</div>
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    <span class="px-2 py-1 text-sm rounded-full {{ $contract->status_color }}-100 text-{{ $contract->status_color }}-800">
                        {{ ucfirst($contract->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    {{ Str::limit($contract->description, 50) }}
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    {{ $contract->reviewed_at?->format('d/m/Y H:i') ?? 'Not reviewed' }}
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    {{ $contract->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.contracts.review', $contract) }}"
                           class="uk-button uk-button-small uk-button-default">
                            Review
                        </a>

                        <form action="{{ route('admin.contracts.generate-pdf', $contract->business_id) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            <button type="submit"
                                    class="uk-button uk-button-small uk-button-secondary">
                                Export PDF
                            </button>
                        </form>

                        @if($contract->file_path)
                            <a href="{{ route('admin.contracts.download', $contract) }}"
                               class="uk-button uk-button-small uk-button-primary">
                                Download
                            </a>
                        @endif

                        <form action="{{ route('admin.contracts.destroy', $contract) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this contract?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="uk-button uk-button-small uk-button-danger">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach

        @if($contracts->isEmpty())
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    No contracts found
                </td>
            </tr>
        @endif
    </x-table>

    <div class="mt-4">
        <x-pagination :paginator="$contracts" />
    </div>
@endsection
