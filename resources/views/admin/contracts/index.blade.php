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

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
            <h2 class="text-xl font-semibold">Business Contracts</h2>
        </div>
    </div>

    <x-table
        :headers="[
            'name' => ['label' => __('Business Name'), 'sortable' => true],
            'created_at' => ['label' => __('Registration Date'), 'sortable' => true],
            'contract_status' => ['label' => __('Contract Status'), 'sortable' => true],
            'actions' => ['label' => __('Actions')]
        ]"
        :sort-by="request('sort_by')"
        :direction="request('direction')"
    >
        @forelse($businesses as $business)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 border-b border-gray-100">
                    <div class="font-medium">{{ $business->name }}</div>
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    {{ $business->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        @if($business->contract_status === 'approved') bg-green-100 text-green-800
                        @elseif($business->contract_status === 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ ucfirst($business->contract_status ?? 'pending') }}
                    </span>
                </td>
                <td class="px-6 py-4 border-b border-gray-100">
                    <div class="flex space-x-2">
                        <form action="{{ route('admin.contracts.generate-pdf', $business) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            <button type="submit"
                                    class="uk-btn uk-btn-secondary">
                                Generate Contract
                            </button>
                        </form>

                        @if($business->contract_file)
                            <a href="{{ Storage::url($business->contract_file) }}"
                               target="_blank"
                               class="uk-btn uk-btn-secondary">
                                View Contract
                            </a>
                        @endif

                        <a href="{{ route('admin.contracts.upload', $business) }}"
                           class="uk-btn uk-btn-secondary">
                            Upload Signed
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                    No businesses found
                </td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">
        <x-pagination :paginator="$businesses" />
    </div>
@endsection
