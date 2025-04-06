@extends('layouts.app')

@section('heading')
    <div class="flex items-center gap-2">
        <span>{{ __('advertisements.related_management') }}</span>
        <span class="uk-badge uk-badge-primary">{{ $advertisement->title }}</span>
    </div>
@endsection

@section('content')
    <div class="uk-card uk-card-default uk-card-body max-w-4xl mx-auto">
        <form action="{{ route('seller.advertisements.update-related', $advertisement) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Related Advertisements Selection --}}
                <div>
                    <label class="uk-form-label font-medium text-lg mb-2 block">
                        {{ __('advertisements.select_related') }}
                    </label>

                    <div class="uk-form-controls bg-gray-50 rounded-md p-4">
                        <div class="space-y-2 divide-y divide-gray-100">
                            @forelse($availableAdvertisements as $related)
                                <label class="flex items-center gap-4 py-3 hover:bg-gray-50 px-2 rounded cursor-pointer">
                                    <input type="checkbox"
                                           name="related_advertisements[]"
                                           value="{{ $related->id }}"
                                           class="uk-checkbox"
                                        {{ in_array($related->id, $selectedIds) ? 'checked' : '' }}>

                                    <div class="flex-1">
                                        <span class="font-medium">{{ $related->title }}</span>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <span class="uk-badge uk-badge-{{ $related->type === 'sale' ? 'success' : 'warning' }}">
                                            {{ __('advertisement.'.$related->type) }}
                                        </span>
                                        <span class="text-gray-600 font-medium">
                                            €{{ number_format($related->price, 2) }}
                                        </span>
                                    </div>
                                </label>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    {{ __('advertisements.no_available') }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('seller.advertisements.index') }}"
                       class="uk-btn uk-btn-default hover:bg-gray-100">
                        {{ __('cancel') }}
                    </a>
                    <button type="submit"
                            class="uk-btn uk-btn-primary hover:opacity-90">
                        {{ __('advertisements.save_related') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
