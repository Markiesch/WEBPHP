<div class="py-8">
    @if(isset($block->content['title']))
        <h2 class="text-2xl font-bold mb-4">{{ $block->content['title'] }}</h2>
    @endif
    
    <div class="relative {{ $block->content['fullWidth'] ?? false ? 'w-full' : 'max-w-4xl mx-auto' }}">
        <img 
            src="{{ $block->content['url'] ?? '' }}" 
            alt="{{ $block->content['alt'] ?? 'Business image' }}" 
            class="w-full rounded-lg {{ isset($block->content['height']) ? 'h-' . $block->content['height'] : '' }} object-cover shadow-lg"
        >
        
        @if(isset($block->content['caption']))
            <p class="text-sm text-gray-600 mt-2 text-center italic">{{ $block->content['caption'] }}</p>
        @endif
    </div>
</div>
