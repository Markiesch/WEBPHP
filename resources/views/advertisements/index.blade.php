@extends('layouts.app')

@section('heading')
    {{ __('Advertisements') }}
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center">
                <a href="{{ route('advertisements.create') }}" class="uk-btn uk-btn-primary">
                    {{ __('Create Advertisement') }}
                </a>
                <a href="{{ route('advertisements.upload-csv') }}" class="uk-btn uk-btn-secondary">
                    {{ __('Upload CSV') }}
                </a>
                <div class="flex items-center">
                    <div class="flex items-center space-x-2">
                        <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}"
                           class="uk-btn min-w-[100px] whitespace-nowrap text-center {{ !request('type') ? 'uk-btn-primary' : 'uk-btn-default' }}">
                            {{ __('All') }}
                        </a>
                        @foreach($types as $value => $label)
                            <a href="{{ request()->fullUrlWithQuery(['type' => $value]) }}"
                               class="uk-btn min-w-[100px] whitespace-nowrap text-center {{ request('type') === $value ? 'uk-btn-primary' : 'uk-btn-default' }}">
                                {{ __($label) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-table
        :headers="[
            'title' => ['label' => __('Title')],
            'type' => ['label' => __('Type'), 'sortable' => true],
            'description' => ['label' => __('Description')],
            'price' => ['label' => __('Price'), 'sortable' => true],
            'wear_percentage' => ['label' => __('Wear'), 'sortable' => true],
            'wear_per_day' => ['label' => __('Wear/Day'), 'sortable' => true],
            'created_at' => ['label' => __('Date'), 'sortable' => true],
            'qr' => ['label' => __('QR Code/Edit')]
        ]"
        :sort-by="request('sort_by')"
        :direction="request('direction')"
        class="divide-y divide-gray-200"
    >
        @foreach($advertisements as $advertisement)
            <tr class="hover:bg-gray-50">
                <td class="px-8 py-4">{{ $advertisement->title }}</td>
                <td class="px-8 py-4">
                    <span class="uk-badge {{ $advertisement->type === 'sale' ? 'uk-badge-success' : 'uk-badge-warning' }} whitespace-nowrap">
                  {{ __($types[$advertisement->type]) }}
                    </span>
                    @if($advertisement->type === 'rental' && $advertisement->rental_start_date && $advertisement->rental_end_date)
                        <span class="text-xs text-gray-500 block mt-1">
                            {{ $advertisement->rental_start_date->format('d/m/Y') }} -
                            {{ $advertisement->rental_end_date->format('d/m/Y') }}
                        </span>
                    @endif
                </td>
                <td class="px-8 py-4">{{ Str::limit($advertisement->description, 50) }}</td>
                <td class="px-8 py-4">€{{ number_format($advertisement->price, 2) }}</td>
                <td class="px-8 py-4">
                    <span class="uk-badge {{ $advertisement->wear_percentage > 50 ? 'uk-badge-warning' : 'uk-badge-success' }}">
                        {{ $advertisement->wear_percentage }}%
                    </span>
                </td>
                <td class="px-8 py-4">
                    @if($advertisement->type === 'rental' && $advertisement->wear_per_day)
                        <span class="uk-badge {{ $advertisement->wear_per_day > 1 ? 'uk-badge-warning' : 'uk-badge-success' }}">
                            {{ number_format($advertisement->wear_per_day, 2) }}%
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-8 py-4">{{ $advertisement->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-8 py-4">
                    <div class="flex gap-3">
                        <a href="{{ route('advertisements.show', $advertisement->id) }}" class="uk-btn uk-btn-secondary">
                            {{ __('View QR') }}
                        </a>
                        <a href="{{ route('advertisements.edit', $advertisement->id) }}" class="uk-btn uk-btn-secondary">
                            {{ __('Edit') }}
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table>

    <div class="mt-6">
        <x-pagination :paginator="$advertisements" />
    </div>
@endsection
