<div class="header">
    <div class="header-left active">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" alt="">
        </a>
        <a href="{{ url('/') }}" class="logo-small">
            <img src="{{ asset('assets/img/logo-small.png') }}" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);"></a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">
        <li class="nav-item">
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-img">
                    <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('assets/img/profiles/default-avatar.jpg') }}" alt="">
                    <span class="status online"></span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img">
                            <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('assets/img/profiles/default-avatar.jpg') }}" alt="">
                            <span class="status online"></span>
                        </span>
                        <div class="profilesets">
                            <h6>{{ auth()->user()->name }}</h6>
                            <h5>{{ ucfirst(auth()->user()->role) }}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                        <i class="me-2" data-feather="user"></i> My Profile
                    </a>
                    <hr class="m-0">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item logout pb-0">
                            <img src="{{ asset('assets/img/icons/log-out.svg') }}" class="me-2" alt="img"> Logout
                        </button>
                    </form>
                </div>
            </div>
        </li>
    </ul>

    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-ellipsis-v"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">My Profile</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">Logout</button>
            </form>
        </div>
    </div>
</div>
