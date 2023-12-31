<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{--
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
</head>

<body>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <a href="https://flowbite.com" class="flex items-center">
                <img src="{{ asset('icon/logo_pu.png') }}" class="rounded-lg h-8 mr-3" alt="Flowbite Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Data Cuaca WS.
                    Citanduy</span>
            </a>
            {{-- <div class="flex items-center">
                <a href="tel:5541251234" class="mr-6 text-sm  text-gray-500 dark:text-white hover:underline">(555)
                    412-1234</a>
                <a href="#" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline">Login</a>
            </div> --}}
        </div>
    </nav>
    <nav class="bg-gray-50 dark:bg-gray-700">
        <div class="max-w-screen-xl px-4 py-3 mx-auto">
            <div class="flex items-center">
                <ul class="flex flex-row font-medium mt-0 mr-6 space-x-8 text-sm">
                    <li>
                        <a href="#" class="text-gray-900 dark:text-white hover:underline" aria-current="page">Home</a>
                    </li>
                    <li>
                        <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                            class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-blue-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">Cuaca
                            <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg></button>
                        <!-- Dropdown menu -->
                        <div id="dropdownNavbar"
                            class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-400"
                                aria-labelledby="dropdownLargeButton">
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Jawa
                                        Barat</a>
                                </li>
                                <li>
                                    <a href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Jawa
                                        Tengah</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left">Waktu</th>
                    <th class="text-left">Temperatur (°C)</th>
                    <th class="text-left">Kelembaban (%)</th>
                    <th class="text-left">Cuaca</th>
                    <th class="text-left">Arah Angin</th>
                    <th class="text-left">Kecepatan Angin (Kt)</th>
                </tr>
            </thead>
            <tbody id="tableId">
                <!-- Data cuaca per jam akan ditambahkan di sini melalui JavaScript -->
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // URL API BMKG
                const apiUrl = 'https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/DigitalForecast-JawaBarat.xml';
        
                // Fungsi untuk mengambil data dari API BMKG
                    async function fetchData() {
                        try {
                            const response = await fetch(apiUrl);
                            const xmlText = await response.text();
                            const parser = new DOMParser();
                            const xmlDoc = parser.parseFromString(xmlText, 'text/xml');
                            //console.log(xmlDoc);
                            
                            // Mengambil elemen "area" untuk Kota Bandung
                            const banjarData = xmlDoc.querySelector('area[description="Banjar"]');
                            let parameterElements = banjarData.querySelectorAll('parameter');
                            console.log(banjarData);

                            // Objek untuk menyimpan data
                            const dataHuHourly = {};
                            const dataHumaxDaily = {};
                            const dataTmax = {};
                            const dataHumin = {};
                            const dataTmin = {};
                            const dataT = {};
                            const dataWeather = {};
                            const dataWd = {};
                            const dataWs = {};

                            parameterElements.forEach((parameterElement) => {
                                const parameterId = parameterElement.getAttribute('id');
                                const timerangeElements = parameterElement.querySelectorAll('timerange');

                                timerangeElements.forEach(function(timerangeElement) {
                                    const datetime = timerangeElement.getAttribute('datetime');
                                    const valueElement = timerangeElement.querySelector('value');
                                    const value = valueElement.textContent.trim();
                                    
                                    
                                    if (parameterId === 'hu') {
                                        dataHuHourly[datetime] = value;
                                    } else if (parameterId === 'humax') {
                                        dataHumaxDaily[datetime] = value;
                                    }else if (parameterId === 'tmax'){
                                        dataTmax[datetime] = value;
                                    }else if (parameterId === 'humin'){
                                        dataHumin[datetime] = value;
                                    }else if (parameterId === 'tmin'){
                                        dataTmin[datetime] = value;
                                    }else if (parameterId === 't'){
                                        dataT[datetime] = value;
                                    }else if (parameterId === 'weather'){
                                        dataWeather[datetime] = value;
                                    }else if (parameterId === 'wd'){
                                        dataWd[datetime] = value;
                                    }else if(parameterId === 'ws'){
                                        dataWs[datetime] = value;
                                    } 
                                });
                                
                            })
                            console.log('------hu-------');
                            console.log(dataHuHourly);
                            console.log('------humax-------');
                            console.log(dataHumaxDaily);
                            console.log('-------tmax------');
                            console.log(dataTmax);
                            console.log('---------humin----');
                            console.log(dataHumin);
                            console.log('------tmin-------');
                            console.log(dataTmin);
                            console.log('--------t------');
                            console.log(dataT);
                            console.log('-------weather------');
                            console.log(dataWeather);
                            console.log('-----wd--------');
                            console.log(dataWd);
                            console.log('-----ws--------');
                            console.log(dataWs);
                            
                        // const parameter = banjarData.querySelector('parameter[id="t"]');
                        // const timeranges = parameter.querySelectorAll('timerange');


                        // //console.log(banjarData);
        
                        // // Mendapatkan tabel
                        // const weatherTable = document.getElementById('weatherData');
        
                        // // Loop melalui elemen "timerange" untuk menampilkan data per jam
                        // timeranges.forEach(function(timerange) {
                        //     const time = timerange.getAttribute('datetime');
                        //     const temperature = timerange.querySelector('value[unit="C"]').textContent;
        
                        //     // Membuat baris tabel untuk setiap data cuaca per jam
                        //     const row = document.createElement('tr');
                        //     row.innerHTML = `
                        //         <td>${time}</td>
                        //         <td>${temperature}°C</td>
                        //         <td></td> <!-- Data kelembaban disini -->
                        //         <td></td> <!-- Data cuaca disini -->
                        //         <td></td> <!-- Data arah angin disini -->
                        //         <td></td> <!-- Data kecepatan angin disini -->
                        //     `;
        
                        //     weatherTable.appendChild(row);
                        // });
                       
                    } catch (error) {
                        console.error('Gagal mengambil data cuaca:', error);
                    }
                }
        
                // Panggil fungsi untuk mengambil dan menampilkan data cuaca
                fetchData();
    </script>
    </script>
</body>

</html>