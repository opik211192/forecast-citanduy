@extends('layouts.app1')
@section('title', 'Info Cuaca WS Citanduy')
@push('styles')
<style>
    #map {
        width: 100%;
        height: 100vh;
        z-index: 0;
    }

    #drawer {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #fff;
        padding: 16px;
        border-top: 1px solid #ddd;
        box-shadow: 0px -2px 5px 0px #aaa;
        z-index: 1000;
    }

    #drawerMenu {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #fff;
        padding: 16px;
        border-top: 1px solid #ddd;
        box-shadow: 0px -2px 5px 0px #aaa;
        z-index: 1000;
    }

    #loadingOverlay {
        display: none;
        position: absolute;
        text-align: center;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.7);
        padding: 16px;
        border-radius: 8px;
        z-index: 999;
    }

    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 10;
        border-right: 2px solid #fff;
    }

    #detailTable {
        font-size: 9px;
    }

    /* Baris pertama untuk tanggal dan jam */
    #detailTable th.sticky-col {
        position: sticky;
        left: 0;
        z-index: 10;
        border-right: 2px solid #fff;
        background-color: #fff;
        /* Mungkin diperlukan untuk mengatasi masalah transparansi */
        padding-left: 10px;
        /* Sesuaikan dengan lebar elemen lain yang mungkin menutupi */
    }

    /* Baris ketiga untuk cuaca dan ikon */
    #detailTable td.sticky-col {
        position: sticky;
        left: 0;
        z-index: 10;
        border-right: 2px solid #fff;
        background-color: #fff;
        /* Mungkin diperlukan untuk mengatasi masalah transparansi */
        padding-left: 10px;
        /* Sesuaikan dengan lebar elemen lain yang mungkin menutupi */
    }

    .leaflet-popup-content-wrapper,
    .leaflet-popup-tip {
        opacity: 0.8;
    }

    #detailDeskripsi {
        font-size: 12px;
    }

    #detailDeskripsi dl {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    #detailDeskripsi dt,
    #detailDeskripsi dd {
        width: 35%;
        /* Menyesuaikan lebar masing-masing elemen */
        margin-bottom: 7px;
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
<div class="">
    <div id="map"></div>
</div>

{{-- untuk loading data --}}
<div class="spinner-border" role="status" id="loadingSpinner" style="display: none;">
    <span class="visually-hidden">Loading...</span>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header d-flex justify-content-between align-items-center">
        <div></div>
        <div>
            <img class="img-fluid" style="max-width: 100px;" src="{{ asset('logo/citanduy.png') }}" alt="logo_citanduy">
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-unstyled px-2">
            <li class="active"><a href="{{ route('map.index') }}" class="text-decoration-none px-3 py-2 d-block"><i
                        class="fa fa-home"></i>
                    Home</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"><i class="fa fa-database"></i>
                    Data Wilayah
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('map.jabar') }}"><i class="fa fa-map"></i> Jawa
                        Barat</a>
                    <a class="dropdown-item" href="{{ route('map.jateng') }}"><i class="fa fa-map"></i> Jawa
                        Tengah</a>
                </div>
            </li>
            <li class=""><a href="" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-dismiss="offcanvas"
                    class="text-decoration-none px-3 py-2 d-block"><i class="fa fa-users"></i>
                    About</a></li>
        </ul>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">About</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid mt-5">
                    <footer class="py-3 my-4">
                        <div class="justify-content-center pb-3 mb-3">
                            <h2 class="text-center">Supported By</h2>
                        </div>
                        <div class="d-flex justify-content-evenly">
                            <img src="{{ asset('logo/citanduy.png') }}" class="figure-img img-fluid rounded"
                                alt="citanduy" width="125px" height="90px">
                            <img src="{{ asset('logo/bmkg.png') }}" class="figure-img img-fluid rounded" alt="citanduy"
                                width="80px" height="80px">
                        </div>
                        <p class="text-center text-muted mt-5">&copy; 2024 BBWS Citanduy</p>
                    </footer>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Drawer -->
<div id="drawer">
    <div id="loadingOverlay" class="mt-5">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="position-absolute" style="top: -10px; left: 10px; z-index: 1001;">
        <button type="button" id="closeButton" onclick="closeDrawer()" class="rounded-circle" aria-label="Close"
            data-bs-toggle="tooltip" data-bs-placement="left" title="Close" style="border-radius: 0;"><i
                class="fa fa-window-close" aria-hidden="true"></i></button>
    </div>


    {{-- drawer detail --}}
    <div class="col-12">
        <div class="d-flex shadow-lg">
            <div class="col-9">
                <h3 id="drawerTitle"></h3>
                <div id="drawerContent">
                    <!-- Tabel Detail -->
                    <div class="table-responsive text-sm" id="detailTableContainer" style="display: none;">
                        <table id="detailTable" class="text-center">

                        </table>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div style="margin-left: 1.25rem;">
                    <div id="detailDeskripsi">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // $(".offcanvas-body ul li").on('click', function () {
    //     $(".offcanvas-body ul li.active").removeClass('active');
    //     $(this).addClass('active');
    //     });
        
    //     $('.open-btn').on('click', function () {
    //     $('.offcanvas-body').addClass('active');
        
    //     });
        
        
    //     $('.close-btn').on('click', function () {
    //     $('.offcanvas-body').removeClass('active');
        
    //     })
</script>
<script>
    const lat = -7.1168155;
    const lng = 108.5551111;


    const peta1 = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            })

    const peta2 = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
                attribution: 'google',
                maxZoom: 19
            });


    //const map = L.map('map').setView([lat, lng], 4.5);
    const map = L.map('map', {
        center: [lat, lng],
        zoom : 8,
        layers: [peta1],
        zoomControl: false,
    });

    const baseMaps = {
        'StreetMap': peta1,
        'Satelite': peta2
    };

    const overlayMaps = {
        

    };

    
    const layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);
    // // Buat menu
    const menuControl = L.control({ position: 'topleft' });
    
    menuControl.onAdd = function (map) {
        const container = L.DomUtil.create('div', 'leaflet-control-menu');
        
        // Tambahkan elemen menu di sini
        container.innerHTML = `
                <button class="btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample data-bs-placement="top" title="Menu"" onclick="showMenu1()"><i class="fa fa-bars"></i></button>
        `;
        
        return container;
    };
    
    menuControl.addTo(map);

    const showMenu1 = () =>{
           closeDrawer();
    }

    const showMenu2 = () => {
        
    }

    // const showMenu2 = () => {
    //     alert('ini menu 2')
    // }
    // Tambahkan tombol zoom di posisi yang lebih rendah
    const zoomControl = L.control.zoom({ position: 'bottomright' }).addTo(map);

    const toggleSpinner = (display) => {
        const spinner = document.getElementById('loadingSpinner');
        spinner.style.display = display ? 'block' : 'none';
    }

    //function detail
    const dataDetail = async (location) => {
        // Menunjukkan elemen loading di dalam drawer
        document.getElementById('loadingOverlay').style.display = 'block';
        try {
            //const apiUrl = `{{ route('api.lokasi.detail', ['location' => 'dummy']) }}`.replace('dummy', location);
            const apiUrl = `https://infocuaca.bbwscitanduy.id/api/lokasi/location/${location}`;
            const response = await axios.get(apiUrl);
            const detailData = response.data;

            //console.log(detailData);
            // Menyembunyikan elemen loading setelah data diterima
            document.getElementById('loadingOverlay').style.display = 'none';
            // Menunjukkan elemen drawer
            document.getElementById('drawer').style.display = 'block';

            // Memperbarui tabel dengan data yang diterima
            updateDetailTable(detailData);

            // Update detail deskripsi
            updateDetailDeskripsi(detailData);
        } catch (error) {
            console.error('Error fetching data:', error);

           // Menyembunyikan elemen loading jika terjadi kesalahan
            document.getElementById('loadingOverlay').style.display = 'none';
        }
    }

    // function untuk memperbarui tabel detail
    const updateDetailTable = (detailData) => {
        const table = document.getElementById('detailTable');
        const tableBody = document.createElement('tbody');
        
        // Hapus semua elemen anak dari <tbody>
        while (table.firstChild) {
            table.removeChild(table.firstChild);
        }
        
        // Baris pertama untuk tanggal dan jam
        const dateRow = document.createElement('tr');
        dateRow.appendChild(document.createElement('th'));
        dateRow.firstElementChild.textContent = 'Tanggal';
        dateRow.firstElementChild.classList.add('sticky-col');
        dateRow.firstElementChild.style.zIndex = '10';

        
        // Looping tanggal
        for (const date of detailData.uniqueDates) {
            const colspan = detailData.hoursInDay.length;
            const dateCell = document.createElement('td');
            dateCell.setAttribute('class', 'fw-bold');
            dateCell.textContent = date;
            dateCell.colSpan = colspan;
            dateRow.appendChild(dateCell);
        }

   
        
        tableBody.appendChild(dateRow);
        
        // Baris kedua untuk jam
        const hourRow = document.createElement('tr');
        hourRow.appendChild(document.createElement('th'));
        hourRow.firstElementChild.textContent = "Jam";
        hourRow.firstElementChild.classList.add('sticky-col');
        hourRow.firstElementChild.style.zIndex = '10';
        
        function roundToNearestThreeHour(hour) {
            return Math.floor(hour / 3) * 3;
        }
        let previousDate = null;
        let hourNow = new Date().getHours();
        let dateNow = new Date().getDate();

        let roundedHour = roundToNearestThreeHour(hourNow);
       
        // Looping jam
        for (const date of detailData.uniqueDates) {
                const dateParts = date.split(' ');
                const dayOfMonth = parseInt(dateParts[0], 10);
            for (const hour of detailData.hoursInDay) {
                const hourCell = document.createElement('td');
                
                // Tambahkan border atau warna latar belakang jika jam saat ini
                if (date !== previousDate) {
                    hourCell.style.borderLeft = '1px solid #000';
                    previousDate = date;
                }

                if (dayOfMonth === dateNow && hour === roundedHour) {
                        hourCell.style.borderBottom = '1px solid red';
                }

                hourCell.textContent = `${hour}`;

            

                hourRow.appendChild(hourCell);
            }
        }
        
        tableBody.appendChild(hourRow);
        
            // Baris ketiga untuk cuaca dan ikon
            const weatherRow = document.createElement('tr');
            weatherRow.appendChild(document.createElement('th'));
            weatherRow.firstElementChild.textContent = "Cuaca";
            weatherRow.firstElementChild.classList.add('sticky-col');
            weatherRow.firstElementChild.style.zIndex = '10';
        
            // Baris keempat untuk suhu
            const temperatureRow = document.createElement('tr');
            temperatureRow.appendChild(document.createElement('th'));
            temperatureRow.firstElementChild.textContent = "Suhu";
            temperatureRow.firstElementChild.classList.add('sticky-col');
            temperatureRow.firstElementChild.style.zIndex = '10';

            //baris kelima humidity
            const humidityRow = document.createElement('tr');
            humidityRow.appendChild(document.createElement('th'));
            humidityRow.firstElementChild.textContent = 'Kelembaban';
            humidityRow.firstElementChild.classList.add('sticky-col');
            humidityRow.firstElementChild.style.zIndex = '10';

            //baris keenam wind speed
            const windSpeedRow = document.createElement('tr');
            windSpeedRow.appendChild(document.createElement('th'));
            windSpeedRow.firstElementChild.textContent = 'Angin';
            windSpeedRow.firstElementChild.classList.add('sticky-col');
            windSpeedRow.firstElementChild.style.zIndex = '10';

            // Loop untuk setiap lokasi
            for (const location in detailData.groupedData) {
                const locationData = detailData.groupedData[location];
            
                // Looping tanggal
                for (const date of detailData.uniqueDates) {
                    // Looping jam
                    for (const hour of detailData.hoursInDay) {
                        const dataCellWeather = document.createElement('td');
                        const dataCellTemperature = document.createElement('td');
                        const dataCellHumidity = document.createElement('td');
                        const dataCellWindSpeed = document.createElement('td');
                    
                        // Cek apakah data tersedia untuk lokasi, tanggal, dan jam tertentu
                        if (locationData[date] && locationData[date][hour]) {
                            const weatherData = locationData[date][hour];
                            const temperature = weatherData.temperature;
                            const humidity = weatherData.humidity;
                            const windSpeed = weatherData.wind_speed;
                            const weatherIconData = weatherData.weather_icon;
                            const weatherIcon = weatherIconData.icon;
                            const iconUrl = `{{ asset('icon/svg') }}/${weatherIcon}`;
                        
                            // Membuat elemen gambar untuk menampilkan ikon cuaca
                            const weatherIconElement = document.createElement('img');
                            weatherIconElement.src = iconUrl;
                            weatherIconElement.alt = 'Weather Icon';
                            weatherIconElement.title = weatherIconData.title;
                            weatherIconElement.height = 40;
                            weatherIconElement.width = 40;
                        
                            // Membuat elemen teks untuk menampilkan suhu
                            const temperatureElement = document.createElement('span');
                            temperatureElement.textContent = `${temperature}°C`;

                            //membuat elemen teks untuk humidity
                            const humidityElement = document.createElement('span');
                            humidityElement.textContent = `${humidity}%`;

                            //membuat element teks untuk windspeed
                            const windSpeedElement = document.createElement('span');
                            windSpeedElement.textContent = `${windSpeed} m/s`;

                            // Menambahkan elemen gambar dan teks ke dalam sel data
                            dataCellWeather.appendChild(weatherIconElement);
                            dataCellTemperature.appendChild(temperatureElement);
                            dataCellHumidity.appendChild(humidityElement);
                            dataCellWindSpeed.appendChild(windSpeedElement);
                        }
                    
                        // Menambahkan sel data ke dalam baris cuaca dan suhu
                        weatherRow.appendChild(dataCellWeather);
                        temperatureRow.appendChild(dataCellTemperature);
                        humidityRow.appendChild(dataCellHumidity);
                        windSpeedRow.appendChild(dataCellWindSpeed);
                    }
                }
            }
        
            // Menambahkan baris cuaca dan suhu ke dalam tabel
            tableBody.appendChild(weatherRow);
            tableBody.appendChild(temperatureRow);
            tableBody.appendChild(humidityRow);
            tableBody.appendChild(windSpeedRow);
            
            // Menambahkan tbody ke dalam tabel
            table.appendChild(tableBody);
        
            // Tampilkan tabel detail
            document.getElementById('detailTableContainer').style.display = 'block';            
    }
    

    // Function untuk memperbarui detail deskripsi
    const updateDetailDeskripsi = (detailData) => {
        const detailDeskripsi = document.getElementById('detailDeskripsi');

        const dataDeskripsi = detailData['lokasi'][0];
        const dlElement = document.createElement('dl');

        // Judul dan isi deskripsi
        const pairs = [
            ['Kecamatan', dataDeskripsi.kecamatan],
            ['Provinsi', dataDeskripsi.provinsi],
            ['Kabupaten/Kota', dataDeskripsi.kabupaten],
            ['Latitude', dataDeskripsi.latitude],
            ['Longitude', dataDeskripsi.longitude],
        ];

        pairs.forEach(pair => {
            const dtElement = document.createElement('dt');
            dtElement.textContent = pair[0];
            dtElement.classList.add('fw-bold');

            const ddElement = document.createElement('dd');
            ddElement.textContent = pair[1];

            
            // Menambahkan elemen judul dan isi ke dalam <dl>
            dlElement.appendChild(dtElement);
            dlElement.appendChild(ddElement);
        });

        // Mengosongkan elemen detailDeskripsi sebelum menambahkan yang baru
        detailDeskripsi.innerHTML = '';
        // Memasukkan elemen <dl> ke dalam detailDeskripsi
        detailDeskripsi.appendChild(dlElement);
    
    }

    // function close drawer
    const closeDrawer = () => {
        document.getElementById('drawer').style.display = 'none';
    }

   // Function untuk mengambil data dari database dan menambahkan pencarian

    const fetchDataLocation = async () => {
        //toggleSpinner(true);
        try {
            //const apiUrl = "{{ route('api.lokasi.index') }}";
            const apiUrl = `https://infocuaca.bbwscitanduy.id/api/lokasi`
            const response = await axios.get(apiUrl);
            const datas = response.data.data;

            // Buat grup layer untuk menampung marker
            const markerGroup = L.layerGroup().addTo(map);

            datas.forEach(data => {
                // console.log(data);
                const customIcon = L.icon({
                    iconUrl: `{{ asset('icon/svg') }}/${data.weather_icon}`,
                    iconAnchor: [30, 10]
                });

                const marker = L.marker([data.latitude, data.longitude], 
                { 
                    icon: customIcon,
                    location: `${data.kecamatan} (${data.provinsi})`,
                    
                });

                marker.bindPopup(`
                    <div class="text-center">
                        <h5>${data.temperature}&deg;C</h5>
                        <span>${data.weather_name}</span>
                    </div>
                    <table class="mt-3 table table-sm table-borderless">
                        <tr>
                            <td><b>Provinsi</b></td>
                            <td></td>
                            <td>${data.provinsi}</td>
                        </tr>
                        <tr>
                            <td><b>Kabupaten/Kota</b></td>
                            <td></td>
                            <td>${data.kabupaten}</td>
                        </tr>
                        <tr>
                            <td><b>Kecamatan</b></td>
                            <td></td>
                            <td>${data.kecamatan}</td>
                        </tr>
                    </table>
                    <div class="text-center">
                        <button class="btn btn-secondary btn-sm shadow-sm" onclick="dataDetail('${data.location}')">Detail</button>
                    </div>
                `);

                // marker.on('mouseover', function () {
                //     this.openPopup();
                // });

               markerGroup.addLayer(marker); // Tambahkan marker ke dalam layer group
                
            });

            // Inisialisasi Leaflet Search dengan layer group
            const searchControl = new L.Control.Search({
                layer: markerGroup,
                propertyName: 'location', // Ganti 'nama' dengan properti yang berisi teks yang ingin Anda cari pada marker
                marker: false,
                moveToLocation: function (latlng, title, map) {
                    map.setView(latlng, 15);
                }
            });

            searchControl.on('search:locationfound', function (e) {
                // Callback ketika lokasi ditemukan
                // Anda dapat menambahkan logika tambahan di sini jika diperlukan

                const foundMarker = e.layer;
                // Buka pop-up pada marker yang ditemukan
                foundMarker.openPopup();

                closeDrawer();
            });

            map.addControl(searchControl);

            } catch (error) {
                console.log(error);
                // Menampilkan alert dengan tombol refresh
                const alertMessage = `
                    Terjadi kesalahan. Silakan ulangi lagi halaman ini.
                    <br>
                    <button class="btn btn-sm btn-secondary" onclick="window.location.reload()">Ulangi</button>
                `;

            alert(alertMessage);
        }finally {
            toggleSpinner(false);
        }
    }

    fetchDataLocation();

    // Set interval untuk menjalankan fetchDataLocation setiap tiga jam
    setInterval(fetchDataLocation, 3 * 60 * 60 * 1000);
</script>
@endpush