<table class="uk-table">
    <thead>
    <tr>
        @foreach($headers as $key => $header)
            <th>
                @if(isset($header['sortable']) && $header['sortable'])
                    <div>
                        <span>{{ $header['label'] }}</span>
                        <a href="{{ request()->fullUrlWithQuery([
                                    'sort_by' => $key,
                                    'direction' => ($sortBy === $key && $direction === 'asc') ? 'desc' : 'asc'
                                ]) }}">
                            @if($sortBy === $key)
                                {!! $direction === 'asc' ? '↑' : '↓' !!}
                            @else
                                ↕
                            @endif
                        </a>
                    </div>
                @else
                    <span>{{ $header['label'] }}</span>
                @endif
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    {{ $slot }}
    </tbody>
</table>
