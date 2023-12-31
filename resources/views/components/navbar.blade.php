<div class="mb-3">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <a class="navbar-brand" href="#"><img src="{{ asset('icon/logo_pu.png') }}" class="img-thumbnail rounded"
                style="width: 40px; height: 40px" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                {{-- <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link @yield('menuJabar')" href="{{ route('dashboard-jabar') }}">Jawa Barat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @yield('menuJateng')" href="{{ route('dashboard-jateng') }}">Jawa Tengah</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Data Hujan
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item @yield('menuHujanJabar')" href="{{ route('hujan.jabar') }}">Jawa
                            Barat</a>
                        <a class="dropdown-item @yield('menuHujanJateng')" href="{{ route('hujan.jateng') }}">Jawa
                            Tengah</a>
                        {{-- <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a> --}}
                    </div>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link @yield('menuHujan')" href="{{ route('hujan.index') }}">Data Hujan</a>
                </li> --}}
            </ul>
            {{-- <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form> --}}
        </div>
    </nav>
</div>