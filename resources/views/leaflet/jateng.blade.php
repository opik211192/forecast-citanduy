@extends('layouts.app1')
@section('title', 'Data Cuaca Jawa Tengah')
@push('styles')
<style>
    .custom-table {
        font-size: 12px;
        background-color: whitesmoke;
    }

    .custom-table thead {
        position: sticky;
        top: 0;
        background: #f2f2f2;
        z-index: 2;
    }

    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
        position: relative;
        /* Tambahkan posisi relatif */
        z-index: 4;
        /* Tambahkan z-index yang lebih tinggi */
    }

    .custom-table td:first-child {
        position: sticky;
        left: 0;
        background-color: #f2f2f2;
        z-index: 1;
        border-right: 2px solid #ddd;
        /* Tambahkan border right */
    }

    .custom-table {
        width: 100%;
        text-align: center;
        border-collapse: separate;
        border-spacing: 0;
        /* Perhatikan penambahan baris yang tidak terpakai di sini */
    }

    .custom-table th {
        /* Apply both top and bottom borders to the <th> */
        border-top: 2px solid;
        border-bottom: 2px solid;
        border-right: 2px solid;
    }

    .custom-table thead th {
        position: sticky;
        top: 0;
        background-color: #07559e;
        color: white;
    }

    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 3;
        background-color: #07559e;
        border-right: 2px solid white;

    }

    li.active {
        background: #eee;
        border-radius: 8px;
    }

    .offcanvas-body li.active a,
    .offcanvas-body li.active a:hover {
        color: #243677;
    }

    .offcanvas-body li a {
        color: #000;
    }
</style>
@endpush
@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow p-3 mb-4 bg-body rounded">
    <a class="navbar-brand">
        <button class="border" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
            aria-controls="offcanvasExample" data-bs-placement="top" title="Menu" onclick="showMenu1()">
            <i class="fa fa-bars"></i>
        </button>
    </a>
    <div>
        Data Cuaca Jawa Tengah
    </div>
</nav>

<div class="container d-flex justify-content-center">
    <div class="col-lg-4">
        <div class="input-group mb-3">
            <label class="input-group-text" for="kabupaten">Cari</label>
            <select class="form-select" id="kabupaten">
                <option selected disabled>Pilih Kabupaten</option>
                @foreach ($kabupatenList as $kabupaten)
                <option value="{{ $kabupaten }}" {{ $kabupaten==$selectedKabupaten ? 'selected' : '' }}>
                    {{ $kabupaten }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="text-center mt-5 mb-5">
    <div id="loading" class="spinner-border" role="status" style="display: none;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container p-2 mt-4" id="contentDetail">
    <div id="detail" class="row m-2">
    </div>
</div>

<div class="container mt-3">
    <div class="row table-responsive">
        <div id="table">

        </div>
    </div>
</div>
<!-- Modal Detail-->
<div class="modal fade" id="kecamatanModal" tabindex="-1" aria-labelledby="kecamatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kecamatanModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="position-absolute top-50 start-50 translate-middle">
                    <div id="loadingModal" class="spinner-border" role="status" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="kecamatanInfo">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
{{-- footer --}}
<div class="container-fluid bg-light shadow-sm mt-5">
    <footer class="py-3 my-4">
        <div class="justify-content-center pb-3 mb-3">
            <h3 class="text-center">Supported By</h3>
        </div>
        <div class="d-flex justify-content-evenly border-bottom">
            <img src="{{ asset('logo/citanduy.png') }}" class="figure-img img-fluid rounded" alt="citanduy"
                width="125px" height="90px">
            <img src="{{ asset('logo/bmkg.png') }}" class="figure-img img-fluid rounded" alt="citanduy" width="80px"
                height="80px">
        </div>
        <p class="text-center text-muted mt-3">&copy; 2024 BBWS Citanduy</p>
    </footer>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#contentDetail').hide();

        $('#kabupaten').change(function() {
            // Tampilkan loading
            $('#loading').show();

            // Hapus tabel 
            $('#table').empty();
            $('#contentDetail').hide();
            $('#detail').html('');
            // Tampilkan loading
            $('#loading').show();
            
            // Ambil nilai kabupaten yang dipilih
            let selectedKabupaten = $(this).val();

          $.ajax({
                type: "POST",
                url: '{{ route("api.cari.jateng") }}',
                data: {
                    kabupaten: selectedKabupaten
                },
                success: function (response) {
                    $('#loading').hide();
                    $('#contentDetail').show();
                    
                  
                    let weatherCounts = response.weatherCounts;

                    function createWeatherCard(iconPath, count, condition) {
                    var cardHtml = `
                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                        <div class="card shadow">
                            <div class="card-content">
                                <div class="card-body d-flex justify-content-between">
                                    <div class="">
                                        <img src="${iconPath}" alt="${condition}" width="60" height="60">
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="d-flex justify-content-end">${count}</h3>
                                        <span>${condition}</span>
                                    </div>
                            </div>
                        </div>
                    </div>`;
                    
                        // Konversi HTML string menjadi elemen jQuery
                        var card = $(cardHtml);
                    
                        return card;
                    }

                    if (weatherCounts) {
                        // Membuat dan menambahkan card cuaca berdasarkan data yang ada
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/100_cerah.svg') }}', weatherCounts.locations_0, 'Cerah'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/101_102_cerah_berawan.svg') }}', weatherCounts.locations_1, 'Cerah Berawan'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/103_berawan.svg') }}', weatherCounts.locations_3,'Berawan'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/104_berawan_tebal.svg') }}', weatherCounts.locations_4,'Berawan Tebal'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/5_udara_kabur.svg') }}', weatherCounts.locations_5,'Udara Kabur'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/10_asap.svg') }}', weatherCounts.locations_10,'Asap'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/45_kabut.svg') }}', weatherCounts.locations_45,'Kabut'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/60_hujan_ringan.svg') }}', weatherCounts.locations_60, 'Hujan Ringan'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/61_hujan.svg') }}', weatherCounts.locations_61, 'Hujan Sedang'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/63_hujan_lebat.svg') }}', weatherCounts.locations_63, 'Hujan Lebat'));
                        $('#detail').append(createWeatherCard('{{ asset('icon/svg/95_97_hujan_petir.svg') }}', weatherCounts.locations_95, 'Hujan Petir'));
                    }else{
                        console.error('Data weatherCounts kosong atau null...');
                    }


                    // Dapatkan tanggal dan jam saat ini
                    const currentDate = new Date();
                    const currentDateString = currentDate.toLocaleDateString();
                    const currentHour = currentDate.getHours();

                    if (response.groupedData && Object.keys(response.groupedData).length > 0) {
                        // Buat tabel
                        let table = $('<table>').addClass('table table-bordered table-sm custom-table table-hover');
                        let thead = $('<thead>');
                        let trHeaderDate = $('<tr>');
                        trHeaderDate.append($('<th>').addClass('sticky-col').text('Nama Kecamatan'));
    
                        // Looping tanggal untuk membuat header tabel
                        $.each(response.uniqueDates, function(index, date) {
                            trHeaderDate.append($('<th>').attr('colspan', response.hoursInDay.length).text(date));
                        });
    
                        thead.append(trHeaderDate);
    
                        let trHeaderHour = $('<tr>');
                        trHeaderHour.append($('<th>').addClass('sticky-col'));
    
                        // Looping jam
                        $.each(response.uniqueDates, function(index, date) {
                            $.each(response.hoursInDay, function(index, hour) {
                                trHeaderHour.append($('<th>').text(str_pad(hour, 2, '0', 'STR_PAD_LEFT') + ':00'));
                            });
                        });
    
                        thead.append(trHeaderHour);
    
                        // Looping data kecamatan
                        let tbody = $('<tbody>');
                        $.each(response.groupedData, function(location, locationData) {
                            let trLocation = $('<tr>');
                            trLocation.append($('<td>').text(location));
                                
    
                            // Looping tanggal
                            $.each(response.uniqueDates, function(index, date) {
                                // Looping jam
                                $.each(response.hoursInDay, function(index, hour) {
                                    let td = $('<td>');
    
                                    if (locationData[date] && locationData[date][hour]) {
                                        let weatherData = locationData[date][hour];
                                        let weatherIconData = weatherData['weather_icon'];
                                        let weatherIcon = weatherIconData['icon'];
                                        let weatherTitle = weatherIconData['title'];
                                        let iconUrl = `{{ asset('icon/svg') }}/${weatherIcon}`;
                                        
                                        td.data('idLocation', weatherData['idLocation']);

                                        td.append($('<img>').attr({
                                            src: iconUrl,
                                            height: '50px',
                                            width: '50px',
                                            alt: 'Weather Icon',
                                            title: weatherTitle
                                        }));

                                        td.css('cursor', 'pointer');

                                        td.click(async function() {
                                            $('#kecamatanModal').modal('show');
                                            $('#kecamatanModalLabel').empty('');
                                            $('#kecamatanInfo').empty('');
                                            $('#loadingModal').show();
                                            
                                            // Mendapatkan ID lokasi dari elemen
                                            var idLocation = $(this).data('idLocation');
                                            var apiUrl = `{{ route('api.count.jateng', ['location' => 'dummy']) }}`.replace('dummy', idLocation);

                                            try {
                                                const response = await fetch(apiUrl);
                                                if (!response.ok) {
                                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                                }
                                                
                                                $('#loadingModal').hide();
                                               

                                                // Lakukan sesuatu dengan data yang diterima
                                                const data = await response.json();
                                                const kecamatan = data.weatherCounts.kecamatan;

                                                $('#kecamatanModalLabel').append(`Kecamatan ${kecamatan}`)
                                                $('#kecamatanInfo').html(`
                                                    <table class="table table-sm table-hover">
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/100_cerah.svg') }}" alt="" width="50px" height="50px">Cerah
                                                            </td>
                                                            <td>${data.weatherCounts.locations_0}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/101_102_cerah_berawan.svg') }}" alt="" width="50px" height="50px">Cerah Berawan
                                                            </td>
                                                            <td>${data.weatherCounts.locations_1}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/103_berawan.svg') }}" alt="" width="50px" height="50px">Berawan
                                                            </td>
                                                            <td>${data.weatherCounts.locations_3}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/104_berawan_tebal.svg') }}" alt="" width="50px" height="50px">Berawan Tebal
                                                            </td>
                                                            <td>${data.weatherCounts.locations_4}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/5_udara_kabur.svg') }}" alt="" width="50px" height="50px">Udara Kabur
                                                            </td>
                                                            <td>${data.weatherCounts.locations_5}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/10_asap.svg') }}" alt="" width="50px" height="50px">Asap
                                                            </td>
                                                            <td>${data.weatherCounts.locations_10}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/45_kabut.svg') }}" alt="" width="50px" height="50px">Kabut
                                                            </td>
                                                            <td>${data.weatherCounts.locations_45}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/60_hujan_ringan.svg') }}" alt="" width="50px" height="50px">Hujan Ringan
                                                            </td>
                                                            <td>${data.weatherCounts.locations_60}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/61_hujan.svg') }}" alt="" width="50px" height="50px">Hujan Sedang
                                                            </td>
                                                            <td>${data.weatherCounts.locations_61}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/63_hujan_lebat.svg') }}" alt="" width="50px" height="50px">Hujan Lebat
                                                            </td>
                                                            <td>${data.weatherCounts.locations_63}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><img src="{{ asset('icon/svg/95_97_hujan_petir.svg') }}" alt="" width="50px" height="50px">Hujan Petir
                                                            </td>
                                                            <td>${data.weatherCounts.locations_95}</td>
                                                        </tr>
                                                    </table>
                                                `);

                                                
                                            } catch (error) {
                                                console.error('Error fetching data:', error);
                                            }
                                             
                                        });
                                    }
    
                                    trLocation.append(td);
                                });
                            });
    
                            tbody.append(trLocation);
                        });
    
                        table.append(thead);
                        table.append(tbody);
    
                        // Tambahkan tabel ke elemen dengan ID 'table'
                        $('#table').append(table);
                        
                    }else {
                        $('#loading').hide();
                        // Tampilkan notifikasi jika tidak ada data
                        $('#table').append('<div class="mb-5 alert alert-warning text-center">Tidak ada data yang ditemukan.</div>');
                    }

                }
            });
        });
    });

     // Fungsi untuk padding angka dengan nol di depannya
    function str_pad(num, size, padStr) {
        num = num.toString();
        while (num.length < size) {
            num = padStr + num;
        }
        return num;
    }
</script>
@endpush