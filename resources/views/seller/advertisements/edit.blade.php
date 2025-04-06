@extends('layouts.app')

@section('heading')
    {{ __('advertisements.edit') }}
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

            <form action="{{ route('seller.advertisements.update', $advertisement) }}" method="POST"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="type" class="uk-form-label">
                        {{ __('advertisements.type') }}
                    </label>
                    <select name="type"
                            id="type"
                            class="uk-select @error('type') border-red-500 @enderror"
                            required>
                        @foreach($types as $value => $label)
                            <option
                                value="{{ $value }}" {{ old('type', $advertisement->type) === $value ? 'selected' : '' }}>
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
                        {{ __('advertisements.title_field') }}
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           class="uk-input @error('title') border-red-500 @enderror"
                           value="{{ old('title', $advertisement->title) }}"
                           required>
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="uk-form-label">
                        {{ __('advertisements.description') }}
                    </label>
                    <textarea name="description"
                              id="description"
                              class="uk-textarea @error('description') border-red-500 @enderror"
                              rows="4"
                              required>{{ old('description', $advertisement->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="uk-form-label">
                        {{ __('advertisements.price') }}
                    </label>
                    <div class="relative">
                        <span class="uk-form-icon">
                            <uk-icon icon="euro"></uk-icon>
                          </span>
                        <input type="number"
                               name="price"
                               id="price"
                               class="uk-input @error('price') border-red-500 @enderror"
                               value="{{ old('price', $advertisement->price) }}"
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
                        {{ __('advertisements.wear_percentage') }}
                    </label>
                    <div class="relative">
                          <span class="uk-form-icon">
                            <uk-icon icon="percent"></uk-icon>
                          </span>
                        <input type="number"
                               name="wear_percentage"
                               id="wear_percentage"
                               class="uk-input @error('wear_percentage') border-red-500 @enderror"
                               value="{{ old('wear_percentage', $advertisement->wear_percentage) }}"
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
                            {{ __('advertisements.wear_per_day') }}
                        </label>
                        <div class="relative">
                            <span class="uk-form-icon">
                                <uk-icon icon="percent"></uk-icon>
                            </span>
                            <input type="number"
                                   name="wear_per_day"
                                   id="wear_per_day"
                                   class="uk-input @error('wear_per_day') border-red-500 @enderror"
                                   value="{{ old('wear_per_day', $advertisement->wear_per_day) }}"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                {{ $advertisement->type === 'rental' ? 'required' : '' }}>
                        </div>
                        @error('wear_per_day')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rental_start_date"
                               class="uk-form-label">
                            {{ __('advertisements.start_date') }}
                        </label>
                        <input type="date"
                               name="rental_start_date"
                               id="rental_start_date"
                               class="uk-input @error('rental_start_date') border-red-500 @enderror"
                               value="{{ old('rental_start_date', optional($advertisement->rental_start_date)->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                            {{ $advertisement->type === 'rental' ? 'required' : '' }}>
                        @error('rental_start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rental_end_date"
                               class="uk-form-label">
                            {{ __('advertisements.end_date') }}
                        </label>
                        <input type="date"
                               name="rental_end_date"
                               id="rental_end_date"
                               class="uk-input @error('rental_end_date') border-red-500 @enderror"
                               value="{{ old('rental_end_date', optional($advertisement->rental_end_date)->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            {{ $advertisement->type === 'rental' ? 'required' : '' }}>
                        @error('rental_end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="uk-form-label">
                        {{ __('advertisements.image') }}
                    </label>
                    @if($advertisement->image_url)
                        <div class="mb-2">
                            <img src="{{ $advertisement->image_url }}" alt="{{ $advertisement->title }}"
                                 class="w-32 h-32 object-cover rounded-md">
                        </div>
                    @endif
                    <input type="file"
                           name="image"
                           id="image"
                           class="uk-input @error('image') border-red-500 @enderror"
                           accept="image/jpeg,image/png,image/jpg,image/gif">
                    @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="uk-btn uk-btn-primary">
                        {{ __('advertisements.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
