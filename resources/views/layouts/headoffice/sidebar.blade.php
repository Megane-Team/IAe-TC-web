<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="active">
                    <a href="{{ route('headoffice.dashboard') }}">
                        <img src="{{ asset('assets/img/icons/dashboard.svg') }}" alt="img">
                        <span> Dashboard</span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void (0);">
                        <img src="{{ asset('assets/img/icons/transfer1.svg') }}" alt="img">
                        <span> Peminjaman</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('peminjaman.index') }}">List Peminjaman</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/purchase1.svg') }}" alt="img">
                        <span> Konfirmasi</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('detailpeminjaman.index') }}">Detail Peminjaman</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>