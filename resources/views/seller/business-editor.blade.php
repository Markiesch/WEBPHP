@extends('layouts.app')

@section('heading')
    Business
@endsection

@section('content')
    <div class="uk-container">

        <div class="uk-card uk-card-body mb-6">
            <h2 class="text-lg font-semibold mb-4">Business settings</h2>
            <form action="{{ route('business.update') }}" method="POST">
                @csrf
                @method("PUT")
                <label for="name" class="uk-form-label">Name</label>

                <input name="name" type="text" class="uk-input mb-4" value="{{ $business->name }}"/>
                <label for="url" class="uk-form-label">URL</label>
                <div class="flex">
                    <div class="uk-card shadow-sm rounded-r-none px-4 flex items-center border-e-0">
                        {{ url('/') }}/businesses/
                    </div>
                    <input name="url" type="text" class="uk-input rounded-none" value="{{ $business->url }}"/>
                    <div class="flex-shrink-0">
                        <a href="{{ route('business-page', $business->url) }}" class="uk-btn uk-btn-secondary border-s-0 border rounded-s-none" target="_blank">
                            View live page
                            <uk-icon icon="external-link" class="pl-2"></uk-icon>
                        </a>
                    </div>
                </div>
                <button type="submit" class="uk-btn uk-btn-primary mt-4">Save</button>
            </form>
        </div>

        <div class="flex justify-between items-center mb-6 border-t pt-4">
            <h1 class="text-2xl font-bold">Business Page Editor</h1>
        </div>

        <!-- Block type selector for adding new blocks -->
        <div class="uk-card uk-card-secondary uk-card-body mb-4">
            <h2 class="text-lg font-semibold mb-4">Add New Block</h2>
            <form action="{{ route('business.blocks.create') }}" method="POST" class="flex gap-2">
                @csrf
                <select name="type" class="uk-select bg-white">
                    <option value="intro_text">Text Section</option>
                    <option value="featured_ads">Featured Advertisements</option>
                    <option value="image">Image</option>
                </select>
                <button type="submit" class="uk-btn uk-btn-primary flex-shrink-0">Add Block</button>
            </form>
        </div>

        <!-- Block order management -->
        <div id="blockContainer" class="mb-6 uk-card border-b-0">
            @forelse($blocks as $block)
                <div class="rounded-none uk-card-body border-b" data-id="{{ $block->id }}">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center gap-2">
                            <form id="orderForm" action="{{ route('business.blocks.order') }}" method="POST">
                                @csrf
                                <input type="hidden" name="block_id" value="{{ $block->id }}">
                                <input type="hidden" name="direction" value="up">
                                <button type="submit" class="uk-btn uk-btn-sm uk-btn-default uk-btn-icon">
                                    <uk-icon icon="move-up"></uk-icon>
                                </button>
                            </form>
                            <form id="orderForm" action="{{ route('business.blocks.order') }}" method="POST">
                                @csrf
                                <input type="hidden" name="block_id" value="{{ $block->id }}">
                                <input type="hidden" name="direction" value="down">
                                <button type="submit" class="uk-btn uk-btn-sm uk-btn-default uk-btn-icon">
                                    <uk-icon icon="move-down"></uk-icon>
                                </button>
                            </form>
                            <h3 class="font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $block->type)) }}
                            </h3>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" class="uk-btn uk-btn-sm uk-btn-default"
                                    data-uk-toggle="target: {{ '#editor-' . $block->id }}">
                                Edit
                            </button>
                            <form action="{{ route('business.blocks.delete', $block) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="uk-btn uk-btn-sm uk-btn-destructive">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Preview of block content -->
                    <div class="preview">
                        @if($block->type === 'intro_text')
                            <h4 class="font-medium">{{ $block->content['title'] }}</h4>
                            <div
                                class="text-sm text-gray-600">{!! Str::limit(strip_tags($block->content['text']), 100) !!}</div>
                        @elseif($block->type === 'featured_ads')
                            <h4 class="font-medium">{{ $block->content['title'] }}</h4>
                            <div class="text-sm text-gray-600">Showing {{ $block->content['count'] ?? 3 }} featured
                                ads
                            </div>
                        @elseif($block->type === 'image')
                            <h4 class="font-medium">{{ $block->content['title'] }}</h4>
                            @if(!empty($block->content['url']))
                                <img src="{{ $block->content['url'] }}" alt="{{ $block->content['alt'] }}"
                                     class="h-20 object-cover mt-2">
                            @else
                                <div class="text-sm text-gray-600">No image uploaded yet</div>
                            @endif
                        @endif
                    </div>

                    <!-- Hidden editor panel -->
                    <div id="editor-{{ $block->id }}" class="uk-modal" data-uk-modal>
                        <div class="uk-modal-dialog uk-margin-auto-vertical uk-modal-body">

                            <form action="{{ route('business.blocks.update', $block) }}" method="POST">
                                @csrf
                                @method('PUT')

                                @if($block->type === 'intro_text')
                                    <div class="mb-3">
                                        <label class="uk-form-label">Title</label>
                                        <input type="text" name="content[title]" value="{{ $block->content['title'] }}"
                                               class="uk-input">
                                    </div>
                                    <div class="mb-3">
                                        <label class="uk-form-label">Content</label>
                                        <textarea name="content[text]" rows="5"
                                                  class="uk-textarea w-full richtext">{{ $block->content['text'] }}</textarea>
                                    </div>
                                @elseif($block->type === 'featured_ads')
                                    <div class="mb-3">
                                        <label class="uk-form-label">Title</label>
                                        <input type="text" name="content[title]" value="{{ $block->content['title'] }}"
                                               class="uk-input">
                                    </div>
                                    <div class="mb-3">
                                        <label class="uk-form-label">Number of Ads</label>
                                        <input type="number" name="content[count]"
                                               value="{{ $block->content['count'] ?? 3 }}" min="1" max="12"
                                               class="uk-input">
                                    </div>
                                @elseif($block->type === 'image')
                                    <div class="mb-3">
                                        <label class="uk-form-label">Title</label>
                                        <input type="text" name="content[title]" value="{{ $block->content['title'] }}"
                                               class="uk-input">
                                    </div>
                                    <div class="mb-3">
                                        <label class="uk-form-label">Image URL</label>
                                        <input type="text" name="content[url]" value="{{ $block->content['url'] }}"
                                               class="uk-input">
                                    </div>
                                    <div class="mb-3">
                                        <label class="uk-form-label">Alt Text</label>
                                        <input type="text" name="content[alt]" value="{{ $block->content['alt'] }}"
                                               class="uk-input">
                                    </div>
                                    <div class="mb-3">
                                        <label class="uk-form-label">Caption</label>
                                        <input type="text" name="content[caption]"
                                               value="{{ $block->content['caption'] }}" class="uk-input">
                                    </div>
                                    <div class="mb-3">
                                        <label class="flex items-center uk-form-label">
                                            <input type="checkbox" name="content[fullWidth]" value="1"
                                                   {{ isset($block->content['fullWidth']) && $block->content['fullWidth'] ? 'checked' : '' }} class="uk-checkbox">
                                            <span class="ml-2">Full Width</span>
                                        </label>
                                    </div>
                                @endif

                                <div class="flex justify-end">
                                    <button type="button" class="uk-btn uk-modal-close uk-btn-secondary mr-2">Cancel
                                    </button>
                                    <button type="submit" class="uk-btn uk-btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <input type="hidden" name="blocks[]" value="{{ $block->id }}">
                </div>
            @empty
                <div class="bg-gray-100 rounded-lg p-8 text-center">
                    <p class="text-gray-500">No blocks added yet. Add your first block above.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
