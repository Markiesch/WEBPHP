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
            <tr>
                <td>{{ $advertisement->title }}</td>
                <td>{{ Str::limit($advertisement->description, 50) }}</td>
                <td>€{{ number_format($advertisement->price, 2) }}</td>
                <td>{{ $advertisement->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('advertisements.show', $advertisement->id) }}">
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
