@extends('layouts.app')

@section('heading')
    {{ __('Edit Advertisement') }}
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

            <form action="{{ route('advertisements.update', $advertisement) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="type" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Type') }}
                    </label>
                    <select name="type"
                            id="type"
                            class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all @error('type') border-red-500 @enderror"
                            required>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type', $advertisement->type) === $value ? 'selected' : '' }}>
                                {{ __($label) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Title') }}
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all @error('title') border-red-500 @enderror"
                           value="{{ old('title', $advertisement->title) }}"
                           required>
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Description') }}
                    </label>
                    <textarea name="description"
                              id="description"
                              class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all @error('description') border-red-500 @enderror"
                              rows="4"
                              required>{{ old('description', $advertisement->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Price') }}
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">€</span>
                        <input type="number"
                               name="price"
                               id="price"
                               class="w-full pl-8 px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all @error('price') border-red-500 @enderror"
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
                    <label for="wear_percentage" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Wear Percentage') }}
                    </label>
                    <div class="relative">
                        <input type="number"
                               name="wear_percentage"
                               id="wear_percentage"
                               class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all @error('wear_percentage') border-red-500 @enderror"
                               value="{{ old('wear_percentage', $advertisement->wear_percentage) }}"
                               min="0"
                               max="100"
                               step="1"
                               required>
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                    </div>
                    @error('wear_percentage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="rental_start_date" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                            {{ __('Start Date') }}
                        </label>
                        <input type="date"
                               name="rental_start_date"
                               id="rental_start_date"
                               class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all @error('rental_start_date') border-red-500 @enderror"
                               value="{{ old('rental_start_date', optional($advertisement->rental_start_date)->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                            {{ $advertisement->type === 'rental' ? 'required' : '' }}>
                        @error('rental_start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rental_end_date" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                            {{ __('End Date') }}
                        </label>
                        <input type="date"
                               name="rental_end_date"
                               id="rental_end_date"
                               class="w-full px-6 py-4 border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all @error('rental_end_date') border-red-500 @enderror"
                               value="{{ old('rental_end_date', optional($advertisement->rental_end_date)->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            {{ $advertisement->type === 'rental' ? 'required' : '' }}>
                        @error('rental_end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="font-medium text-gray-500 uppercase tracking-wider block mb-2">
                        {{ __('Image') }}
                    </label>
                    @if($advertisement->image_url)
                        <div class="mb-2">
                            <img src="{{ $advertisement->image_url }}" alt="{{ $advertisement->title }}" class="w-32 h-32 object-cover rounded-md">
                        </div>
                    @endif
                    <input type="file"
                           name="image"
                           id="image"
                           class="w-full px-6 py-4 border border-gray-100 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all @error('image') border-red-500 @enderror"
                           accept="image/jpeg,image/png,image/jpg,image/gif">
                    @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="uk-btn uk-btn-primary">
                        {{ __('Update Advertisement') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
