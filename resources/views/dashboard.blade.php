<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

</head>

<body class="hold-transition sidebar-mini layout-fixed" onload="cambiarFondo()">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60"
                width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>

                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline" action="{{ route('reportes.buscar') }}" method="POST">
                            <div class="input-group input-group-sm">
                                @csrf
                                <input class="form-control form-control-navbar" type="search" name="buscar"
                                    placeholder="Search" aria-label="Search">

                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>

                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>


                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->email }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">Perfil</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                <li class="dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Estilos<span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a id="" href="{{ route('cargarEstilo', ['estilo' => '1']) }}"
                                class="estilo1 dropdown-item">Azul</a></li>
                        <li><a id="" href="{{ route('cargarEstilo', ['estilo' => '2']) }}"
                                class="estilo1 dropdown-item">Claro</a></li>
                        <li><a id="" href="{{ route('cargarEstilo', ['estilo' => '3']) }}"
                                class="estilo1 dropdown-item">Infantil</a></li>
                        <li><a id="" href="{{ route('cargarEstilo', ['estilo' => '4']) }}"
                                class="estilo1 dropdown-item">Dark</a></li>
                        <li><a id="" href="{{ route('cargarEstilo', ['estilo' => '0']) }}"
                                class="estilo1 dropdown-item">Por defecto</a></li>
                    </ul>
                </li>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true"
                        href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light"><b>Restaurant</b> "La Ñ"</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                        <li class="nav-item">
                            @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                    function ($v, $k) {
                                        return in_array($v['funcionalidad'], ['Administracion']) && $v['state'] === 'a';
                                    },
                                    ARRAY_FILTER_USE_BOTH))
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-copy"></i>
                                    <p>
                                        Administracion
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                            @endif
                            <ul class="nav nav-treeview">
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Usuario']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('usuario.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Usuario</p>
                                        </a>
                                    </li>
                                @endif
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Rol']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('rol.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Rol</p>
                                        </a>
                                    </li>
                                @endif
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Privilegio']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('privilegio.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Privilegio</p>
                                        </a>
                                    </li>
                                @endif
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Administracion']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('empresa.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Configuracion</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <!-- Gestion clinica -->
                        <li class="nav-item">
                            @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                    function ($v, $k) {
                                        return in_array($v['funcionalidad'], ['Cliente', 'Cita', 'Venta', 'Pago', 'Servicio']) &&
                                            $v['state'] === 'a';
                                    },
                                    ARRAY_FILTER_USE_BOTH))
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        Gestion Clinica
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            @endif
                            <ul class="nav nav-treeview">
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Cliente']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('cliente.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Clientes</p>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a href="{{ route('cita.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Citas</p>
                                    </a>
                                </li>
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Servicio']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('servicio.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Servicios</p>
                                        </a>
                                    </li>
                                @endif
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Pago']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Pagos</p>
                                        </a>
                                    </li>
                                @endif
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Venta']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('venta.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Ventas</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <!-- Gestion de Recursos -->
                        <li class="nav-item">
                            @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                    function ($v, $k) {
                                        return in_array($v['funcionalidad'], ['Inventario', 'Producto', 'Categoria', 'Promocion']) &&
                                            $v['state'] === 'a';
                                    },
                                    ARRAY_FILTER_USE_BOTH))
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        Gestion de Recursos
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                            @endif
                            <ul class="nav nav-treeview">
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Inventario']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('inventario.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Inventario</p>
                                        </a>
                                    </li>
                                @endif
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Producto']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('producto.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Productos</p>
                                        </a>
                                    </li>
                                @endif
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Categoria']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('categoria.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Categorias</p>
                                        </a>
                                    </li>
                                @endif
                                @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                        function ($v, $k) {
                                            return in_array($v['funcionalidad'], ['Promocion']) && $v['state'] === 'a';
                                        },
                                        ARRAY_FILTER_USE_BOTH))
                                    <li class="nav-item">
                                        <a href="{{ route('promocion.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Promociones</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <!-- Reporte y estadisticas -->
                        <li class="nav-item">
                            @if (array_filter(auth()->user()->rol->privilegios->toArray(),
                                    function ($v, $k) {
                                        return in_array($v['funcionalidad'], ['Reportes']) && $v['state'] === 'a';
                                    },
                                    ARRAY_FILTER_USE_BOTH))
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-tree"></i>
                                    <p>
                                        Reportes y estadisticas
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                            @endif
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('estadisticas.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Estadisticas de acceso</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dashboard') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Reporte de ventas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            </br>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->

                        <!-- Custom tabs (Charts with tabs)-->


                        @yield('content')


                        <!-- /.card -->



                    </div>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="https://adminlte.io">Tecnoweb</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                @if (isset($num))
                    Visitas:
                    <strong> {{ $num }} </strong>
                @else
                    <br>
                @endif
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $('#usuarios').DataTable();
        $('#categorias').DataTable();
        $('#clientes').DataTable();
        $('#pagos').DataTable();
        $('#privilegios').DataTable();
        $('#productos').DataTable();
        $('#promociones').DataTable();
        $('#roles').DataTable();
        $('#servicios').DataTable();
        $('#ventas').DataTable();
        $('#citas').DataTable();
    </script>
    <script>
        function cambiarFondo() {

            document.body.className = 'fondo' + {{ auth()->user()->estilo }};

            if ({{ auth()->user()->estilo }} == 1) {
                console.log("Estilo 1 papurri")
                var hoja = document.createElement('style');
                hoja.innerHTML = ".sidebar{background-color: #455279 !important;} " +
                    ".main-sidebar{background: #455279 !important;}" +
                    ".nav-item p{color: #FFFF !important;}" +
                    ".form-inline input{background: #455279 !important;}" +
                    ".content-wrapper {background: #3C486C !important;}" +
                    ".card {background: #5C6C9C !important;color: #FFFF !important;}" +
                    ".table-hover {background: #5C6C9C !important;color: #FFFF !important;}" +
                    ".card-body {background: #5C6C9C !important;color: #000000 !important;}" +
                    ".modal-content {background: #3C486C !important; color: #FFFF !important;}" +
                    ".main-header {background: #41507D !important;color: #FFFF !important;}" +
                    ".main-header a{background: #41507D !important;color: #FFFF !important;}"
                document.body.appendChild(hoja);
            }
            if ({{ auth()->user()->estilo }} == 2) {
                console.log("Estilo 2 papurri")
                var hoja = document.createElement('style');
                hoja.innerHTML = ".sidebar{background-color: #FFFFFF !important;} " +
                    ".main-sidebar{background: #FFFFFF !important;}" +
                    ".nav-item p{color: #404040 !important;}" +
                    ".form-inline input{background: #FFFFFF !important;}"
                document.body.appendChild(hoja);
            }
            if ({{ auth()->user()->estilo }} == 3) {
                console.log("Estilo 3 papurri")
                var hoja = document.createElement('style');
                hoja.innerHTML = ".sidebar{background-color: #E7F37D !important;} " +
                    ".main-sidebar{background: #E7F37D !important;}" +
                    ".nav-item p{color: #F3513B !important;}" +
                    ".form-inline input{background: #E7F37D !important;}" +
                    ".content-wrapper {background: #EC7C3C !important;}" +
                    ".card {background: #3CD4EC !important;color: #FFFF !important;}" +
                    ".table-hover {background: #3CD4EC !important;color: #FFFF !important;}" +
                    ".card-body {background: #3CD4EC !important;color: #000000 !important;}" +
                    ".modal-content {background: #F3513B !important; color: #FFFF !important;}" +
                    ".main-header {background: #F3513B !important;color: #E7F37D !important;}" +
                    ".main-header a{background: #F3513B !important;color: #E7F37D !important;}"
                document.body.appendChild(hoja);
            }
            if ({{ auth()->user()->estilo }} == 4) {
                console.log("Estilo 4 papurri")
                var hoja = document.createElement('style');
                hoja.innerHTML = ".content-wrapper {background-color: #77818C !important;}" +
                    ".card {background: #51575D !important;color: #FFFF !important;}" +
                    ".card-body {color: #000000 !important;}" +
                    ".table-hover {color: #FFFF !important;}" +
                    ".modal-content {background: #51575D !important; color: #FFFF !important;}" +
                    ".main-header {background: #343a40 !important;color: #FFFF !important;}" +
                    ".main-header a{background: #343a40 !important;color: #FFFF !important;}"
                document.body.appendChild(hoja);
            }
        }
    </script>
</body>

</html>
