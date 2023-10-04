<!DOCTYPE html>
<html>

<head>
    <title>Data Cuaca WS. CItanduy</title>
    <!-- Menggunakan Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

        .custom-table thead {
            position: sticky;
            top: 0;
            background: #f2f2f2;
            z-index: 2;
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
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
            /* Don't collapse */
            border-spacing: 0;
            border-bottom: 2px solid;
            border-right: 2px solid;
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

        #sliding-menu {
            position: fixed;
            top: 0;
            left: -250px;
            /* Menyembunyikan menu di awal */
            width: 250px;
            height: 100%;
            background-color: #333;
            color: #fff;
            transition: left 0.3s ease-in-out;
            z-index: 3;
            /* Efek sliding */
        }

        #sliding-menu ul {
            padding: 0;
            list-style-type: none;
        }

        #sliding-menu ul li {
            padding: 10px;
        }

        #sliding-menu.open {
            left: 0;
            /* Menampilkan menu saat dibuka */
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
        @yield('legenda')
        <div class="mt-2 mb-2">
            <button id="toggle-menu" class="fa fa-question-circle" title="Keterngan Cuaca"></button>
        </div>
        <p class=""><small><em>Sumber data ini dari <a href="https://data.bmkg.go.id/csv/" target="_blank"
                        class="text-dark">BMKG</a></em></small>
        </p>
    </div>

    <!-- Menggunakan Bootstrap JS dan Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
        $("#toggle-menu").click(function () {
        // Toggle menu dengan mengubah nilai left
        $("#sliding-menu").toggleClass("open");
        });
        
        // Menambahkan event listener untuk menutup menu ketika di luar menu diklik
        $("body").click(function (event) {
        // Periksa apakah pengklikan terjadi di luar menu atau tombol "Toggle Menu"
        if (!$(event.target).closest("#sliding-menu").length &&
        !$(event.target).is("#toggle-menu")) {
        // Tutup menu jika pengklikan terjadi di luar menu atau tombol "Toggle Menu"
        $("#sliding-menu").removeClass("open");
        }
        });
        
        // Mencegah event bubbling (propagasi) agar tidak memicu penutupan menu saat mengklik menu itu sendiri
        $("#sliding-menu").click(function (event) {
        event.stopPropagation();
        });
        });
    </script>
</body>

</html>