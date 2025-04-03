<div class="py-8 max-w-3xl mx-auto">
    @if(isset($block->content['title']))
        <h2 class="text-2xl font-bold mb-4">{{ $block->content['title'] }}</h2>
    @endif
    
    <div class="prose lg:prose-xl">
        {!! $block->content['text'] ?? '' !!}
    </div>
</div>
