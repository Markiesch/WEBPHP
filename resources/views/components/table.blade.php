<table class="uk-table">
    <thead>
    <tr>
        @foreach($headers as $key => $header)
            <th class="px-8 py-5 bg-gray-50 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
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
    <tbody class="bg-white divide-y divide-gray-200">
    {{ $slot }}
    </tbody>
</table>
