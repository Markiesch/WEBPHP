<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr class="bg-gray-50">
                @foreach($headers as $key => $header)
                    <th class="px-6 py-4 text-left border-b-2 border-gray-200">
                        @if(isset($header['sortable']) && $header['sortable'])
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-500 uppercase tracking-wider">{{ $header['label'] }}</span>
                                <a href="{{ request()->fullUrlWithQuery([
                                    'sort_by' => $key,
                                    'direction' => ($sortBy === $key && $direction === 'asc') ? 'desc' : 'asc'
                                ]) }}" class="ml-2 text-gray-500 hover:text-gray-900">
                                    @if($sortBy === $key)
                                        {!! $direction === 'asc' ? '↑' : '↓' !!}
                                    @else
                                        ↕
                                    @endif
                                </a>
                            </div>
                        @else
                            <span class="font-medium text-gray-500 uppercase tracking-wider">{{ $header['label'] }}</span>
                        @endif
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
