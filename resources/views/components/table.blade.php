<table class="uk-table uk-table-divider">
    <thead>
    <tr>
        @foreach($headers as $key => $header)
            <th>
                @if(isset($header['sortable']) && $header['sortable'])
                    <div class="flex items-center gap-2">
                        <span>{{ $header['label'] }}</span>
                        <a href="{{ request()->fullUrlWithQuery([
                                    'sort_by' => $key,
                                    'direction' => ($sortBy === $key && $direction === 'asc') ? 'desc' : 'asc'
                                ]) }}"
                           class="text-gray-400 hover:text-gray-600">
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
