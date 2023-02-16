<div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('images/AmistAppIcon.png') }}" alt="AmistAppLogo" height="90"
            width="90" />
    </div>
    <!--Navbar responsive-->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                </div>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('images/' . session('imgPerfil')) }}"
                        class="user-image img-circle elevation-2" alt="User Image">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <img src="{{ asset('images/' . session('imgPerfil')) }}"
                            class="user-image img-circle elevation-2" alt="User Image">
                        <p>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }} -
                                {{ Auth::user()->roles->first()->role }}</span>
                            <small>Registrado desde {{ $mostrarFechaFormato }}</small>
                        </p>
                    </li>

                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a class="btn btn-default btn-flat" href="{{ route('dashboard.profile') }}">Perfil</a>
                        <span class="btn btn-default btn-flat float-right" onclick="cerrarSesion();">Salir</span>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard.index') }}" class="brand-link">
            <img src="{{ asset('images/AmistAppIcon.png') }}" alt="AmistAppLogo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">AmistApp</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('images/' . session('imgPerfil')) }}" class="img-circle elevation-2"
                        alt="{{  session('imgPerfil') }}">
                </div>
                <div class="info">
                    <a href="{{ route('dashboard.profile') }}" class="d-block">
                        {{ Auth::user()->name }}
                    </a>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                {{ env('NOMBRE_DASHBOARD') }}
                            </p>
                        </a>
                    </li>
                    @foreach ($navDashboardAdmin as $modulo => $submodulos)
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon {{ $submodulos['icon'] }}"></i>
                                <p>
                                    {{ $modulo }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            @foreach ($submodulos['submodulos'] as $submodulo => $data)
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url($data['pagina']) }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>{{ $submodulo }}</p>
                                        </a>
                                    </li>
                                </ul>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <!-- /.sidebar -->
    </aside>
