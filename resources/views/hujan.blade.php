@extends('layouts.dashboard')
@section('menuHujan', 'active')

@section('content')
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            @if (count($groupedData) > 0 )

            <div class="text-center">Jawa Barat</div>
            <div class="table-responsive table-wrappe">
                <table class="table table-bordered table-sm custom-table">
                    <thead>
                        <tr>
                            <th class="sticky-col" style="font-size: 12px">Kecamatan</th>
                            @foreach ($dates as $date)
                            <th style="font-size: 11px">{{ date('d-m-y', strtotime($date)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedData as $kecamatan => $dataByDate)
                        <tr>
                            <td class="sticky-col" style="font-size: 13px">{{ $kecamatan }}</td>
                            @foreach ($dates as $date)
                            <td>
                                @if (isset($dataByDate[$date]))
                                @php
                                $weatherCode = $dataByDate[$date]['weather_code_tertinggi'];
                                $weatherIcon = $weatherIcons[$weatherCode]['icon'];
                                $weatherTitle = $weatherIcons[$weatherCode]['title'];
                                $iconUrl = asset("icon/svg/$weatherIcon");
                                @endphp
                                <img src="{{ $iconUrl }}" height="50px" width="50px" alt="{{ $weatherTitle }}"
                                    title="{{ $weatherTitle }}">
                                @else

                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center">
                <h3>Jawa Barat</h3>
                <p>Tidak ada data</p>
            </div>
            @endif
        </div>
        <div class="col-lg-6">
            @if (count($groupedData2) > 0)
            <div class="text-center">Jawa Tengah</div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm custom-table">
                    <thead>
                        <tr>
                            <th class="sticky-col" style="font-size: 12px">Kecamatan</th>
                            @foreach ($dates2 as $date)
                            <th style="font-size: 11px">{{ date('d-m-y', strtotime($date)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedData2 as $kecamatan => $dataByDate)
                        <tr>
                            <td class="sticky-col" style="font-size: 13px">{{ $kecamatan }}</td>
                            @foreach ($dates2 as $date)
                            <td>
                                @if (isset($dataByDate[$date]))
                                @php
                                $weatherCode = $dataByDate[$date]['weather_code_tertinggi'];
                                $weatherIcon = $weatherIcons[$weatherCode]['icon'];
                                $weatherTitle = $weatherIcons[$weatherCode]['title'];
                                $iconUrl = asset("icon/svg/$weatherIcon");
                                @endphp
                                <img src="{{ $iconUrl }}" height="50px" width="50px" alt="{{ $weatherTitle }}"
                                    title="{{ $weatherTitle }}">
                                @else

                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center">
                <h3>Jawa Tengah</h3>
                <p>Tidak ada data</p>
            </div>
            @endif
        </div>
    </div>
</div>
<div class="mt-3 mb-3">
    <x-legenda />
</div>
@endsection