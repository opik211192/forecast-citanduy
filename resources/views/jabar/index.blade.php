@extends('layouts.dashboard')
@section('menuJabar', 'active')

@section('content')
@if (count($groupedData) > 0)
<div class="table-responsive">
    <table class="table table-bordered table-sm custom-table table-hover">
        <thead>
            <tr>
                <th class="sticky-col">Nama Lokasi</th>
                {{-- Looping tanggal --}}
                @foreach ($uniqueDates as $date)
                <th colspan="{{ count($hoursInDay) }}">{{ $date }}</th>
                @endforeach
            </tr>
            <tr>
                <th class="sticky-col"></th>
                {{-- Looping jam --}}
                @foreach ($uniqueDates as $date)
                @foreach ($hoursInDay as $hour)
                <th>{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</th>
                @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- Looping data kecamatan --}}
            @foreach ($groupedData as $location => $locationData)
            <tr>
                <td>{{ $location }}</td>
                {{-- Looping tanggal --}}
                @foreach ($uniqueDates as $date)
                {{-- Looping jam --}}
                @foreach ($hoursInDay as $hour)
                <td>
                    @if (isset($locationData[$date][$hour]))
                    {{-- {{ $locationData[$date][$hour] }} --}}
                    @php
                    $weatherData = $locationData[$date][$hour];
                    $weatherIconData = $weatherData['weather_icon'];
                    $weatherIcon = $weatherIconData['icon'];
                    $weatherTitle = $weatherIconData['title'];
                    $iconUrl = asset("icon/$weatherIcon");
                    @endphp

                    <img src="{{ $iconUrl }}" height="30px" width="30px" alt="Weather Icon" title="{{ $weatherTitle }}">
                    @endif
                </td>
                @endforeach
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<h4 class="text-center">Belum ada data penarikan dari BMKG</h4>
@endif
<div class="mt-3 mb-3">
    <x-legenda />
</div>
@endsection