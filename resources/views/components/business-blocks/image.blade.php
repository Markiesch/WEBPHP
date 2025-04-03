<div class="py-8">
    @if(isset($block->content['title']))
        <h2 class="text-2xl font-bold mb-4">{{ $block->content['title'] }}</h2>
    @endif

    <div>
        <img
            src="{{ asset($block->content['url']) }}"
            alt="{{ $block->content['alt'] ?? 'Business image' }}"
            class="w-full rounded-lg {{ isset($block->content['height']) ? 'h-' . $block->content['height'] : '' }}"
        >

        @if(isset($block->content['caption']))
            <p class="text-sm text-gray-600 mt-2 text-center italic">{{ $block->content['caption'] }}</p>
        @endif
    </div>
</div>
