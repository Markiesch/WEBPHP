<div class="py-8">
    <h2 class="text-2xl font-bold mb-6">{{ $block->content['title'] ?? 'Featured Listings' }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($business->advertisements()->latest()->take($block->content['count'] ?? 3)->get() as $ad)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full h-48 object-cover">

                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $ad->title }}</h3>
                    <p class="text-gray-600 mb-2 truncate">{{ $ad->description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg">{{ $ad->price_formatted }}</span>
                        <a href="{{ route('advertisement', $ad->id) }}" class="text-blue-600 hover:text-blue-800">View
                            details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
