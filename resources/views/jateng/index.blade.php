@extends('layouts.dashboard')
@section('menuJateng', 'active')

@section('content')
<div class="text-center mt-3 mb-2">
    <h5>Data Wilayah Jawa Tengah</h5>
</div>
<form action="{{ route('jateng.filter') }}">
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <label for="kabupaten" class="input-group-text">Cari</label>
                </div>
                <select name="kabupaten" id="kabupaten" class="custom-select">
                    <option value="">Semua</option>
                    @foreach ($kabupatenList as $kabupaten)
                    <option value="{{ $kabupaten }}" {{ $kabupaten==$selectedKabupaten ? 'selected' : '' }}>
                        {{ $kabupaten }}
                    </option>
                    @endforeach
                </select>
                {{-- <div class="input-group-append ml-auto">
                    <button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Cari">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </div> --}}
            </div>
        </div>
    </div>
</form>
@if (count($groupedData) > 0)
<div class="table-responsive table-wrappe">
    <table class="table table-bordered table-sm custom-table">
        <thead>
            <tr>
                <th class="sticky-col">Nama Kecamatan</th>
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

            @foreach ($groupedData as $location => $locationData)
            <tr>
                <td>{{ $location }}</td>

                @foreach ($uniqueDates as $date)

                @foreach ($hoursInDay as $hour)
                <td>
                    @if (isset($locationData[$date][$hour]))

                    @php
                    $weatherData = $locationData[$date][$hour];
                    $weatherIconData = $weatherData['weather_icon'];
                    $weatherIcon = $weatherIconData['icon'];
                    $weatherTitle = $weatherIconData['title'];
                    $iconUrl = asset("icon/svg/$weatherIcon");
                    @endphp

                    <img src="{{ $iconUrl }}" height="50px" width="50px" alt="Weather Icon" title="{{ $weatherTitle }}">
                    @endif
                </td>
                @endforeach
                @endforeach
            </tr>
            {{--
            <pre>{{ print_r($locationData) }}</pre> --}}
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
@push('script')
<script>
    const selectElement = document.getElementById('kabupaten');
    
        selectElement.addEventListener('change', function() {
            const selectedValue = selectElement.value;
    
            if (selectedValue === '') {
                window.location.href = '{{ route("dashboard-jateng") }}';
            } else {
                //const kabupaten = selectedValue.replace(/ /g, '+');
                window.location.href = `{{ route("jateng.filter") }}?kabupaten=${selectedValue}`;
            }
        });
</script>
@endpush
@endsection