<div class="featured-ads">
    <h2>Featured Advertisements</h2>
    @if(!empty($data->ads) && is_array($data->ads))
        <ul>
            @foreach($data->ads as $ad)
                <li>
                    <h3>{{ $ad->title }}</h3>
                    <p>{{ $ad->description }}</p>
                    <p>Price: ${{ $ad->price }}</p>
                </li>
            @endforeach
        </ul>
    @else
        {{ $slot }}
    @endif
</div>
