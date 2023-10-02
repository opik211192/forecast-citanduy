<!DOCTYPE html>
<html>

<head>
    <title>Data Tabel</title>
    <!-- Menggunakan Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table-responsive {
            max-height: 550px;
            overflow-y: auto;
        }


        table thead {
            position: sticky;
            top: 0;
            background: #f2f2f2;
            z-index: 2;
            /* Tambahkan border bottom */
        }

        tbody td:first-child {
            position: sticky;
            left: 0;
            background-color: #f2f2f2;
            z-index: 1;
            border-right: 2px solid #ddd;
            /* Tambahkan border right */
        }

        table {
            width: 100%;
            text-align: center;
            border-collapse: separate;
            /* Don't collapse */
            border-spacing: 0;
        }

        table th {
            /* Apply both top and bottom borders to the <th> */
            border-top: 2px solid;
            border-bottom: 2px solid;
            border-right: 2px solid;
        }

        table td {
            /* For cells, apply the border to one of each side only (right but not left, bottom but not top) */
            border-bottom: 2px solid;
            border-right: 2px solid;
        }

        table thead th {
            position: sticky;
            top: 0;
            background-color: #edecec;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            z-index: 3;
            background-color: #f2f2f2;
            border-right: 2px solid #ddd;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Data Cuaca WS. Citanduy</h2>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Jawa Barat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Jawa Tengah</a>
            </li>
        </ul>
        @if (count($groupedData) > 0)
        <div class="table-responsive table-wrappe">
            <table class="table table-bordered table-sm">
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

                            <img src="{{ $iconUrl }}" height="30px" width="30px" alt="Weather Icon"
                                title="{{ $weatherTitle }}">
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
        <p><small><em>Sumber data ini dari <a href="https://data.bmkg.go.id/csv/">BMKG</a></em></small></p>
    </div>

    <!-- Menggunakan Bootstrap JS dan Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>