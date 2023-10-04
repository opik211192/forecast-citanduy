<!DOCTYPE html>
<html>

<head>
    <title>Data Cuaca WS. CItanduy</title>
    <!-- Menggunakan Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('icon/logo_pu.png') }}" type="image/x-icon">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url("{{ asset('icon/background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100%;

        }

        .custom-table {
            background-color: whitesmoke;
        }

        .table-responsive {
            max-height: 500px;
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

        .nav-link {
            color: #333;
            background-color: #edecec
                /* Warna tautan dalam keadaan normal */
        }

        /* Gaya tautan navigasi saat di-hover */
        .nav-link:hover {
            color: skyblue;
            /* Warna tautan saat di-hover */
        }

        .nav-tabs>.active>a,
        .nav-tabs>.active>a:hover,
        .nav-tabs>.active>a:focus {
            border-color: black;
            border-bottom-color: transparent;
        }

        .nav-tabs {
            border-bottom: 1px solid black;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Data Cuaca WS. Citanduy</h2>
        <ul class="nav nav-tabs mb-2">
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
        </ul>
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