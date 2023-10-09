<!DOCTYPE html>
<html>

<head>
    <title>Prakiraan Cuaca Wilayah Sungai Citanduy</title>
    <!-- Menggunakan Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('icon/logo_pu.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
    <style>
        body {
            background-image: url("{{ asset('icon/background.jpg') }}")
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="mt-2 mb-3">Prakiraan Cuaca Wilayah Sungai Citanduy</h2>
        {{-- <ul class="nav nav-tabs mb-2">
            <li class="nav-item">
                <a class="nav-link @yield('menuJabar')" aria-current="page" href="{{ route('dashboard-jabar') }}">Jawa
                    Barat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuJateng')" aria-current="page" href="{{ route('dashboard-jateng') }}">Jawa
                    Tengah</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @yield('menuHujan')" aria-current="page" href="{{ route('hujan.index') }}">Data
                    Hujan</a>
            </li>
        </ul> --}}
        <x-navbar />
        @yield('content')
        <p class=""><small><em>Sumber data ini dari <a href="https://data.bmkg.go.id/csv/" target="_blank"
                        class="text-dark">BMKG</a></em></small>
        </p>
    </div>

    <!-- Menggunakan Bootstrap JS dan Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>