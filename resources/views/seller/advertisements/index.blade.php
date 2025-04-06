@extends('layouts.app')

@section('heading')
    {{ __('Advertisements') }}
@endsection

@section('content')
    <div>
        {{-- Action Buttons --}}
        <div class="flex justify-between pb-6">
            <div class="flex gap-2 items-center">
                <a href="{{ route('advertisements.create') }}" class="uk-btn uk-btn-sm uk-btn-primary">
                    {{ __('Create Advertisement') }}
                </a>
                <a href="{{ route('advertisements.upload-csv') }}" class="uk-btn uk-btn-sm uk-btn-secondary">
                    {{ __('Upload CSV') }}
                </a>
            </div>

            {{-- Type Filters --}}
            <div class="flex items-center space-x-2">
                <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}"
                   class="uk-btn uk-btn-sm min-w-[100px] text-center {{ !request('type') ? 'uk-btn-primary' : 'uk-btn-default' }}">
                    {{ __('All') }}
                </a>
                @foreach($types as $value => $label)
                    <a href="{{ request()->fullUrlWithQuery(['type' => $value]) }}"
                       class="uk-btn uk-btn-sm min-w-[100px] text-center {{ request('type') === $value ? 'uk-btn-primary' : 'uk-btn-default' }}">
                        {{ __($label) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Advertisements Table --}}
    <div class="uk-card">
        <x-table
            :headers="[
                'title' => ['label' => __('Title')],
                'type' => ['label' => __('Type'), 'sortable' => true],
                'description' => ['label' => __('Description')],
                'price' => ['label' => __('Price'), 'sortable' => true],
                'wear_percentage' => ['label' => __('Wear'), 'sortable' => true],
                'wear_per_day' => ['label' => __('Wear/Day'), 'sortable' => true],
                'created_at' => ['label' => __('Date'), 'sortable' => true],
                'actions' => ['label' => __('Actions')]
            ]"
            :sort-by="request('sort_by')"
            :direction="request('direction')"
        >
            @foreach($advertisements as $advertisement)
                <tr>
                    {{-- Title & Related Ads --}}
                    <td>
                        <div class="font-medium">{{ $advertisement->title }}</div>
                        @if($advertisement->relatedAdvertisements->count() > 0)
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($advertisement->relatedAdvertisements as $related)
                                    <span class="uk-badge uk-badge-info text-xs">
                                        {{ Str::limit($related->title, 20) }}
                                        <small class="opacity-75">({{ __($types[$related->type]) }})</small>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </td>

                    {{-- Type & Rental Dates --}}
                    <td>
                        <span class="uk-badge {{ $advertisement->type === 'sale' ? 'uk-badge-success' : 'uk-badge-warning' }}">
                            {{ __($types[$advertisement->type]) }}
                        </span>
                        @if($advertisement->type === 'rental' && $advertisement->rental_start_date && $advertisement->rental_end_date)
                            <span class="text-xs text-gray-500 block mt-1">
                                {{ $advertisement->rental_start_date->format('d/m/Y') }} -
                                {{ $advertisement->rental_end_date->format('d/m/Y') }}
                            </span>
                        @endif
                    </td>

                    <td>{{ Str::limit($advertisement->description, 50) }}</td>
                    <td>€{{ number_format($advertisement->price, 2) }}</td>

                    {{-- Wear Percentage --}}
                    <td>
                        <span class="uk-badge {{ $advertisement->wear_percentage > 50 ? 'uk-badge-warning' : 'uk-badge-success' }}">
                            {{ $advertisement->wear_percentage }}%
                        </span>
                    </td>

                    {{-- Wear Per Day --}}
                    <td>
                        @if($advertisement->type === 'rental' && $advertisement->wear_per_day)
                            <span class="uk-badge {{ $advertisement->wear_per_day > 1 ? 'uk-badge-warning' : 'uk-badge-success' }}">
                                {{ number_format($advertisement->wear_per_day, 2) }}%
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <td>{{ $advertisement->created_at->format('Y-m-d H:i') }}</td>

                    {{-- Actions --}}
                    <td class="flex-shrink-0 w-[15rem]">
                        <div class="flex gap-1 flex-shrink-0">
                            <a href="{{ route('advertisements.show', $advertisement->id) }}"
                               class="flex-shrink-0 uk-btn uk-btn-xs uk-btn-default">
                                {{ __('View QR') }}
                            </a>
                            <a href="{{ route('advertisements.edit', $advertisement->id) }}"
                               class="flex-shrink-0 uk-btn uk-btn-xs uk-btn-default">
                                {{ __('Edit') }}
                            </a>
                            <a href="{{ route('advertisements.edit-related', $advertisement->id) }}"
                               class="flex-shrink-0 uk-btn uk-btn-xs uk-btn-secondary">
                                {{ __('Related') }}
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        <x-pagination :paginator="$advertisements"/>
    </div>
@endsection
