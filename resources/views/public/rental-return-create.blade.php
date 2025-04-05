@extends('layouts.home')

@section('content')
    <x-header/>

    <div class="uk-container py-8">
        <div class="uk-flex uk-flex-middle uk-margin-medium-bottom">
            <a href="{{ route('rental-return.index') }}" class="uk-button uk-button-link">
                <span uk-icon="arrow-left"></span>
            </a>
            <div class="uk-margin-left">
                <h1 class="uk-heading-small uk-margin-remove">{{ __('Return Product') }}</h1>
                <p class="uk-text-meta uk-margin-remove-top">{{ $transaction->advertisement->title }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm">
                    <form action="{{ route('rental-return.return', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="p-6">
                            @if($errors->any())
                                <div class="uk-alert-danger" uk-alert>
                                    <a class="uk-alert-close" uk-close></a>
                                    <ul class="uk-list uk-margin-remove">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="uk-margin">
                                <label class="text-sm font-medium">{{ __('Product Photo') }} <span class="text-red-600">*</span></label>
                                <div class="uk-margin-small">
                                    <div class="uk-inline uk-width-1-1">
                                        <div class="uk-form-custom">
                                            <input type="file" name="return_photo" accept="image/*" required>
                                            <button class="uk-width-1-1 uk-button uk-button-default" type="button" tabindex="-1">
                                                <span uk-icon="image" class="uk-margin-small-right"></span>
                                                {{ __('Select Photo') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500">
                                    <span uk-icon="info" class="uk-margin-small-right"></span>
                                    {{ __('Take a clear photo showing the current condition of the product') }}
                                </p>
                            </div>

                            <div class="uk-margin">
                                <label class="text-sm font-medium">{{ __('Comments') }}</label>
                                <textarea class="uk-textarea" name="notes" rows="3"
                                          placeholder="{{ __('Optional comments about the product condition') }}"></textarea>
                            </div>
                        </div>

                        <div class="p-6 bg-gray-50 rounded-b-xl border-t">
                            <div class="uk-flex uk-flex-between uk-flex-middle">
                                <a href="{{ route('rental-return.index') }}" class="uk-btn uk-btn-default">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="uk-btn uk-btn-primary">
                                    <span uk-icon="check" class="uk-margin-small-right"></span>
                                    {{ __('Return') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="p-6 bg-gray-50 rounded-t-xl border-b">
                        <h3 class="text-xl font-bold">{{ __('Return Details') }}</h3>
                    </div>
                    <div class="p-6">
                        <dl class="uk-description-list">
                            <dt class="text-sm text-gray-600">{{ __('Product') }}</dt>
                            <dd class="uk-margin-small-bottom font-medium">{{ $transaction->advertisement->title }}</dd>

                            <dt class="text-sm text-gray-600">{{ __('Business') }}</dt>
                            <dd class="uk-margin-small-bottom">{{ $transaction->advertisement->business->name }}</dd>

                            <dt class="text-sm text-gray-600">{{ __('Rental Period') }}</dt>
                            <dd class="uk-margin-small-bottom">
                                <span uk-icon="calendar" class="uk-margin-small-right"></span>
                                {{ $transaction->created_at->format('d M Y') }} -
                                {{ $transaction->created_at->addDays($transaction->rental_days)->format('d M Y') }}
                            </dd>

                            <dt class="text-sm text-gray-600">{{ __('Total Wear') }}</dt>
                            <dd class="uk-margin-small-bottom">
                                <span uk-icon="history" class="uk-margin-small-right"></span>
                                {{ number_format($transaction->advertisement->wear_per_day * now()->diffInDays($transaction->created_at), 2) }}%
                                <span class="text-sm text-gray-500">
                                    ({{ $transaction->advertisement->wear_per_day }}% {{ __('per day') }})
                                </span>
                            </dd>

                            <dt class="text-sm text-gray-600">{{ __('Status') }}</dt>
                            <dd>
                                <span class="uk-label {{ now()->isAfter($transaction->created_at->addDays($transaction->rental_days)) ? 'uk-label-danger' : 'uk-label-success' }}">
                                    <span uk-icon="{{ now()->isAfter($transaction->created_at->addDays($transaction->rental_days)) ? 'warning' : 'check' }}" class="uk-margin-small-right"></span>
                                    {{ now()->isAfter($transaction->created_at->addDays($transaction->rental_days)) ? __('Overdue') : __('On Time') }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
