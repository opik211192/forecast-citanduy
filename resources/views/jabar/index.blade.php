@extends('layouts.dashboard')
@section('menuJabar', 'active')


@section('legenda')
<div id="sliding-menu">
    <!-- Isi menu Anda di sini -->
    {{-- <ul>
        <li><a href="#">Menu 1</a></li>
        <li><a href="#">Menu 2</a></li>
        <li><a href="#">Menu 3</a></li>
    </ul> --}}
    <div class="d-flex justify-content-center">

        <table border="1" class="table-xs">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/100_cerah.png') }}" alt=""></td>
                    <td>Cerah</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/101_102_cerah_berawan.png') }}" alt=""></td>
                    <td>Cerah Berawan</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/103_berawan.png') }}" alt=""></td>
                    <td>Berawan</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/104_berawan_tebal.png') }}" alt=""></td>
                    <td>Berawan Tebal</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center" src="{{ asset('icon/10_asap.png') }}"
                            alt=""></td>
                    <td>Asap</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/45_kabut.png') }}" alt=""></td>
                    <td>Kabut</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/5_udara_kabur.png') }}" alt=""></td>
                    <td>Udara Kabur</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/60_hujan_ringan.png') }}" alt=""></td>
                    <td>Hujan Ringan</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/61_hujan.png') }}" alt=""></td>
                    <td>Hujan</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/63_hujan_lebat.png') }}" alt=""></td>
                    <td>Hujan Lebat</td>
                </tr>
                <tr>
                    <td><img style="width: 30px; height: 30px" class="text-center"
                            src="{{ asset('icon/95_97_hujan_petir.png') }}" alt=""></td>
                    <td>Hujan Petir</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
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
@endsection