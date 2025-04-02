@extends('layouts.app')

@section('heading')
    Contracts
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
            <a href="{{ route('contracts.showUploadForm') }}" class="uk-btn uk-btn-primary">
                Upload Contract
            </a>
        </div>
    </div>

    <x-table
        :headers="[
            'name' => ['label' => __('Name')],
            'email' => ['label' => __('Email')],
            'description' => ['label' => __('Description')],
            'created_at' => ['label' => __('Upload Date'), 'sortable' => true],
            'actions' => ['label' => __('Actions')]
        ]"
        :sort-by="request('sort_by')"
        :direction="request('direction')"
    >
        @foreach($contracts as $contract)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-6 border-b border-gray-100">{{ $contract->name }}</td>
                <td class="px-6 py-6 border-b border-gray-100">{{ $contract->email }}</td>
                <td class="px-6 py-6 border-b border-gray-100">{{ $contract->description }}</td>
                <td class="px-6 py-6 border-b border-gray-100">{{ $contract->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-6 border-b border-gray-100">
                    <form action="{{ route('export-pdf', $contract->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="uk-btn uk-btn-secondary">
                            Export to PDF
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </x-table>

    <div class="mt-4">
        <x-pagination :paginator="$contracts" />
    </div>
@endsection
