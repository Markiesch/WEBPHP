@extends('layouts.app')

@section('heading')
    {{ __('Create Advertisement') }}
@endsection

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                    <ul class="list-disc list-inside text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('advertisements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label for="type" class="uk-form-label">
                        {{ __('Type') }}
                    </label>
                    <select name="type"
                            id="type"
                            class="uk-select @error('type') border-red-500 @enderror"
                            required>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>
                                {{ __($label) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="uk-form-label">
                        {{ __('Title') }}
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           class="uk-input @error('title') border-red-500 @enderror"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="uk-form-label">
                        {{ __('Description') }}
                    </label>
                    <textarea name="description"
                              id="description"
                              class="uk-textarea @error('description') border-red-500 @enderror"
                              rows="4"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="uk-form-label">
                        {{ __('Price') }}
                    </label>
                    <div class="relative">
                        <span class="uk-form-icon">
                            <uk-icon icon="euro"></uk-icon>
                        </span>
                        <input type="number"
                               name="price"
                               id="price"
                               class="uk-input @error('price') border-red-500 @enderror"
                               value="{{ old('price') }}"
                               step="0.01"
                               min="0"
                               required>
                    </div>
                    @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="wear_percentage" class="uk-form-label">
                        {{ __('Wear Percentage') }}
                    </label>
                    <div class="relative">
                        <span class="uk-form-icon">
                            <uk-icon icon="percent"></uk-icon>
                        </span>
                        <input type="number"
                               name="wear_percentage"
                               id="wear_percentage"
                               class="uk-input @error('wear_percentage') border-red-500 @enderror"
                               value="{{ old('wear_percentage', 0) }}"
                               min="0"
                               max="100"
                               step="1"
                               required>
                    </div>
                    @error('wear_percentage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="wear_per_day" class="uk-form-label">
                            {{ __('Wear Per Day') }}
                        </label>
                        <div class="relative">
                            <span class="uk-form-icon">
                                <uk-icon icon="percent"></uk-icon>
                            </span>
                            <input type="number"
                                   name="wear_per_day"
                                   id="wear_per_day"
                                   class="uk-input @error('wear_per_day') border-red-500 @enderror"
                                   value="{{ old('wear_per_day', 0) }}"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                {{ request()->old('type') === 'rental' ? 'required' : '' }}>
                        </div>
                        @error('wear_per_day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rental_start_date" class="uk-form-label">
                            {{ __('Start Date') }}
                        </label>
                        <input type="date"
                               name="rental_start_date"
                               id="rental_start_date"
                               class="uk-input @error('rental_start_date') border-red-500 @enderror"
                               value="{{ old('rental_start_date', date('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                            {{ request()->old('type') === 'rental' ? 'required' : '' }}>
                        @error('rental_start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rental_end_date" class="uk-form-label">
                            {{ __('End Date') }}
                        </label>
                        <input type="date"
                               name="rental_end_date"
                               id="rental_end_date"
                               class="uk-input @error('rental_end_date') border-red-500 @enderror"
                               value="{{ old('rental_end_date', date('Y-m-d', strtotime('+1 day'))) }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            {{ request()->old('type') === 'rental' ? 'required' : '' }}>
                        @error('rental_end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="uk-form-label">
                        {{ __('Image') }}
                    </label>
                    <input type="file"
                           name="image"
                           id="image"
                           class="uk-input @error('image') border-red-500 @enderror"
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           required>
                    @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="uk-btn uk-btn-primary">
                        {{ __('Create Advertisement') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
