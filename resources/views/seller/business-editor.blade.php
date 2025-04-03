@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Business Page Editor</h1>

            @if($business)
                <div>
                    <a href="{{ route('business-page', ['url' => $business->url]) }}" class="uk-btn uk-btn-secondary"
                       target="_blank">
                        View live page
                        <uk-icon icon="external-link" class="pl-2"></uk-icon>
                    </a>
                </div>
            @endif
        </div>

        <!-- Block type selector for adding new blocks -->
        <div class="uk-card uk-card-body mb-4">
            <h2 class="text-lg font-semibold mb-4">Add New Block</h2>
            <form action="{{ route('business.blocks.create') }}" method="POST" class="flex gap-4">
                @csrf
                <select name="type" class="form-select">
                    <option value="intro_text">Text Section</option>
                    <option value="featured_ads">Featured Advertisements</option>
                    <option value="image">Image</option>
                </select>
                <button type="submit" class="uk-btn uk-btn-primary">Add Block</button>
            </form>
        </div>

        <!-- Block order management -->
        <form id="orderForm" action="{{ route('business.blocks.order') }}" method="POST">
            @csrf
            <div id="blockContainer" class="space-y-4 mb-6">
                @forelse($blocks as $block)
                    <div class="uk-card uk-card-body" data-id="{{ $block->id }}">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center">
                                    <span class="mr-2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </span>
                                <h3 class="font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $block->type)) }}
                                </h3>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" class="uk-btn uk-btn-default"
                                        onclick="toggleEditor({{ $block->id }})">
                                    Edit
                                </button>
                                <form action="{{ route('business.blocks.delete', $block) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this block?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="uk-btn uk-btn-destructive">Delete</button>
                                </form>
                            </div>
                        </div>

                        <!-- Preview of block content -->
                        <div class="preview mb-4">
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
                        <div id="editor-{{ $block->id }}" class="editor-panel hidden border-t pt-4 mt-4">
                            <form action="{{ route('business.blocks.update', $block) }}" method="POST">
                                @csrf
                                @method('PUT')

                                @if($block->type === 'intro_text')
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Title</label>
                                        <input type="text" name="content[title]" value="{{ $block->content['title'] }}"
                                               class="form-input w-full">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Content</label>
                                        <textarea name="content[text]" rows="5"
                                                  class="form-textarea w-full richtext">{{ $block->content['text'] }}</textarea>
                                    </div>
                                @elseif($block->type === 'featured_ads')
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Title</label>
                                        <input type="text" name="content[title]" value="{{ $block->content['title'] }}"
                                               class="form-input w-full">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Number of Ads</label>
                                        <input type="number" name="content[count]"
                                               value="{{ $block->content['count'] ?? 3 }}"
                                               min="1" max="12" class="form-input w-32">
                                    </div>
                                @elseif($block->type === 'image')
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Title</label>
                                        <input type="text" name="content[title]" value="{{ $block->content['title'] }}"
                                               class="form-input w-full">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Image URL</label>
                                        <input type="text" name="content[url]" value="{{ $block->content['url'] }}"
                                               class="form-input w-full">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Alt Text</label>
                                        <input type="text" name="content[alt]" value="{{ $block->content['alt'] }}"
                                               class="form-input w-full">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Caption</label>
                                        <input type="text" name="content[caption]"
                                               value="{{ $block->content['caption'] }}"
                                               class="form-input w-full">
                                    </div>
                                    <div class="mb-3">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="content[fullWidth]" value="1"
                                                   {{ isset($block->content['fullWidth']) && $block->content['fullWidth'] ? 'checked' : '' }}
                                                   class="form-checkbox">
                                            <span class="ml-2">Full Width</span>
                                        </label>
                                    </div>
                                @endif

                                <div class="flex justify-end">
                                    <button type="button" class="uk-btn uk-btn-secondary mr-2"
                                            onclick="toggleEditor({{ $block->id }})">Cancel
                                    </button>
                                    <button type="submit" class="uk-btn uk-btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        <input type="hidden" name="blocks[]" value="{{ $block->id }}">
                    </div>
                @empty
                    <div class="bg-gray-100 rounded-lg p-8 text-center">
                        <p class="text-gray-500">No blocks added yet. Add your first block above.</p>
                    </div>
                @endforelse
            </div>

            @if(count($blocks) > 1)
                <div class="flex justify-end">
                    <button type="submit" class="uk-btn uk-btn-primary">Save Block Order</button>
                </div>
            @endif
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize drag and drop functionality
            const container = document.getElementById('blockContainer');
            if (container) {
                Sortable.create(container, {
                    animation: 150,
                    handle: '.block-item',
                    onEnd: function () {
                        // Update hidden input values to match new order
                        const blocks = container.querySelectorAll('.block-item');
                        const inputs = container.querySelectorAll('input[name="blocks[]"]');

                        inputs.forEach((input, index) => {
                            input.value = blocks[index].getAttribute('data-id');
                        });
                    }
                });
            }

            // Initialize rich text editor
            tinymce.init({
                selector: '.richtext',
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | bold italic backcolor | \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help'
            });
        });

        // Toggle editor panel visibility
        function toggleEditor(blockId) {
            const editorPanel = document.getElementById('editor-' + blockId);
            editorPanel.classList.toggle('hidden');
        }
    </script>
@endpush
