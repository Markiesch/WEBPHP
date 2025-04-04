@extends('layouts.app')

@section('heading')
    {{ __('Advertisement Agenda') }}
@endsection

@section('content')
    <div class="uk-container">
        {{-- Month Navigation --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">
                {{ $currentMonth }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('advertisement.agenda', ['month' => $previousMonth]) }}"
                   class="uk-button uk-button-default uk-button-small">
                    <span uk-icon="icon: chevron-left"></span>
                </a>
                <a href="{{ route('advertisement.agenda') }}"
                   class="uk-button uk-button-primary uk-button-small">
                    Today
                </a>
                <a href="{{ route('advertisement.agenda', ['month' => $nextMonth]) }}"
                   class="uk-button uk-button-default uk-button-small">
                    <span uk-icon="icon: chevron-right"></span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="col-span-3">
                {{-- Calendar Grid --}}
                <div class="uk-card uk-card-default">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                            <tr class="text-left border-b">
                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                    <th class="p-2 text-center font-medium bg-gray-50">{{ $day }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @php $currentDay = 0; @endphp
                            @foreach($calendar as $date => $day)
                                @if($currentDay % 7 === 0)
                                    <tr>
                                        @endif

                                        <td class="border p-2 min-h-[120px] align-top {{ !$day['isCurrentMonth'] ? 'bg-gray-50' : 'bg-white' }}">
                                            <div class="flex justify-between items-start">
                                            <span class="inline-block px-2 py-1 rounded-full text-sm
                                                {{ $day['isToday'] ? 'bg-blue-600 text-white' : '' }}
                                                {{ !$day['isCurrentMonth'] ? 'text-gray-400' : '' }}">
                                                {{ $day['date']->format('j') }}
                                            </span>
                                            </div>

                                            @if(count($day['advertisements']) > 0)
                                                <div class="mt-2 space-y-1.5">
                                                    @foreach($day['advertisements'] as $ad)
                                                        <div class="text-xs p-2 {{ $ad['is_expiring_soon'] ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700' }} rounded-md shadow-sm">
                                                            <div class="flex items-center justify-between">
                                                                <a href="{{ route('advertisements.show', $ad['id']) }}"
                                                                   class="hover:underline font-medium truncate">
                                                                    {{ $ad['title'] }}
                                                                </a>
                                                                <span class="text-xs {{ $ad['is_expiring_soon'] ? 'text-red-500' : 'text-blue-500' }}">
                                                                @if($ad['days_until_expiry'] === 0)
                                                                        Ends today
                                                                    @else
                                                                        {{ $ad['days_until_expiry'] }}d
                                                                    @endif
                                                            </span>
                                                            </div>
                                                            <div class="text-xs mt-1 opacity-75">
                                                                {{ ucfirst($ad['type']) }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>

                                        @php $currentDay++; @endphp
                                        @if($currentDay % 7 === 0)
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-span-1">
                {{-- Upcoming End Dates Sidebar --}}
                <div class="uk-card uk-card-default uk-card-body rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Upcoming End Dates</h3>
                    @if(count($upcomingEndDates) > 0)
                        <div class="space-y-3">
                            @foreach($upcomingEndDates as $ad)
                                <div class="p-3 {{ $ad['days_until_expiry'] <= 5 ? 'bg-red-50' : 'bg-gray-50' }} rounded-md">
                                    <div class="font-medium text-sm">{{ $ad['title'] }}</div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        Type: {{ ucfirst($ad['type']) }}
                                    </div>
                                    <div class="text-xs {{ $ad['days_until_expiry'] <= 5 ? 'text-red-600' : 'text-gray-600' }} mt-1">
                                        Expires: {{ \Carbon\Carbon::parse($ad['expiry_date'])->format('M j, Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No upcoming end dates</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
