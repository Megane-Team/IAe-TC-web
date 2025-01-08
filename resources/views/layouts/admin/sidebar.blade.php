<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="active">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('assets/img/icons/dashboard.svg') }}" alt="img">
                        <span> Dashboard</span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/places.svg') }}" alt="img">
                        <span> Tempat </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('tempat.index') }}">List Tempat</a></li>
                        <li><a hrefhref="{{ route('tempat.gedung') }}">List Gedung</a></li>
                        <li><a href="{{ route('tempat.parkiran') }}">List Parkiran</a></li>
                        <li><a href="{{ route('tempat.import') }}">Import Tempat</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i data-feather="columns"></i>
                        <span> Ruangan </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('ruangan.index') }}">List Ruangan</a></li>
                        <li><a href="{{ route('ruangan.import') }}">Import Ruangan</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/product.svg') }}" alt="img">
                        <span> Barang </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{route('barang.index')}}">List Barang</a></li>
                        <li><a href="{{route('barang.import')}}">Import Barang</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/car.svg') }}" alt="img">
                        <span> Kendaraan </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('kendaraan.index') }}">List Kendaraan</a></li>
                        <li><a href="{{ route('kendaraan.import') }}">Import Kendaraan</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/users1.svg') }}" alt="img">
                        <span> Users</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('user.index') }}">List Users</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/time.svg') }}" alt="img">
                        <span> Logs </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('logs.index') }}">List Logs</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>