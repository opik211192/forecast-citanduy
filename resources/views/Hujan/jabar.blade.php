@extends('layouts.dashboard')
@section('menuHujanJabar', 'active')

@section('content')
<div class="text-center mt-3 mb-2">
    <h5>Data Hujan Wilayah Jawa Barat</h5>
</div>
<form action="{{ route('hujan.filterJabar') }}">
    <div class="row justify-content-center mt-2 mb-2">
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
<div class="row">
    <div class="col-lg-12">
        @if (count($groupedData) > 0 )
        <div class="table-responsive table-wrappe">
            <table class="table table-bordered table-sm custom-table">
                <thead>
                    <tr>
                        <th class="sticky-col">Kecamatan</th>
                        @foreach ($dates as $date)
                        <th>{{ date('d-m-Y', strtotime($date)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedData as $kecamatan => $dataByDate)
                    <tr>
                        <td class="sticky-col">{{ $kecamatan }}</td>
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
</div>
<div class="mt-3 mb-3">
    <x-legenda />
</div>
@push('script')
<script>
    const selectElement = document.getElementById('kabupaten');
    
        selectElement.addEventListener('change', function() {
            const selectedValue = selectElement.value;
    
            if (selectedValue === '') {
                window.location.href = '{{ route("hujan.jabar") }}';
            } else {
                //const kabupaten = selectedValue.replace(/ /g, '+');
                window.location.href = `{{ route("hujan.filterJabar") }}?kabupaten=${selectedValue}`;
            }
        });
</script>
@endpush
@endsection