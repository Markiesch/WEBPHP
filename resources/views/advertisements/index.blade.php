@extends('layouts.app')

@section('heading')
    {{ __('Advertisements') }}
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
            <a href="{{ route('advertisements.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                {{ __('Create Advertisement') }}
            </a>
        </div>
    </div>

    <x-table
        :headers="[
            'title' => ['label' => __('Title')],
            'description' => ['label' => __('Description')],
            'price' => ['label' => __('Price'), 'sortable' => true],
            'created_at' => ['label' => __('Date'), 'sortable' => true],
            'qr' => ['label' => __('QR Code')]
        ]"
        :sort-by="request('sort_by')"
        :direction="request('direction')"
    >
        @foreach($advertisements as $advertisement)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-6 border-b border-gray-100">{{ $advertisement->title }}</td>
                <td class="px-6 py-6 border-b border-gray-100">{{ Str::limit($advertisement->description, 50) }}</td>
                <td class="px-6 py-6 border-b border-gray-100">€{{ number_format($advertisement->price, 2) }}</td>
                <td class="px-6 py-6 border-b border-gray-100">{{ $advertisement->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-6 py-6 border-b border-gray-100">
                    <a href="{{ route('advertisements.show', $advertisement->id) }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors duration-200">
                        {{ __('View QR') }}
                    </a>
                </td>
            </tr>
        @endforeach
    </x-table>

    <div class="mt-4">
        <x-pagination :paginator="$advertisements" />
    </div>
@endsection
