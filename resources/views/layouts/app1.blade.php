<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    <link rel="icon" href="{!! asset('logo/citanduy.png') !!}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="{{ asset('css/SlideMenu.css') }}">
    <!-- Leaflet Search CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-search/dist/leaflet-search.min.css" />
    @stack('styles')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Leaflet Search JavaScript -->
    <script src="https://unpkg.com/leaflet-search/dist/leaflet-search.min.js"></script>
    <script src="{{ asset('js/SlideMenu.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header d-flex justify-content-between align-items-center">
            <div></div>
            <div>
                <img class="img-fluid" style="max-width: 100px;" src="{{ asset('logo/citanduy.png') }}"
                    alt="logo_citanduy">
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-unstyled px-2">
                <li class=""><a href="{{ route('map.index') }}" class="text-decoration-none px-3 py-2 d-block"><i
                            class="fa fa-home"></i>
                        Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><i class="fa fa-database"></i>
                        Data Cuaca
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('map.jabar') }}"><i class="fa fa-map"></i> Jawa
                            Barat</a>
                        <a class="dropdown-item" href="{{ route('map.jateng') }}"><i class="fa fa-map"></i> Jawa
                            Tengah</a>
                    </div>
                </li>
                <li class=""><a href="" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        data-bs-dismiss="offcanvas" class="text-decoration-none px-3 py-2 d-block"><i
                            class="fa fa-users"></i>
                        About</a></li>
            </ul>
        </div>
    </div>
    <!--About Modal -->
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
                                <img src="{{ asset('logo/bmkg.png') }}" class="figure-img img-fluid rounded"
                                    alt="citanduy" width="80px" height="80px">
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
    @yield('content')

    @stack('scripts')
</body>

</html>