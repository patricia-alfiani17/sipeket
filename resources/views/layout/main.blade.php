<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title id="dynamic-title">@yield('page_title', 'Dashboard') - Sanggar Seni Dharmo Yuwono</title>
    <link rel="icon" type="png" href="{{ asset('images/logo1.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/dropzone/min/dropzone.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    {{-- <link rel="stylesheet" href="{{ asset('lte/plugins/daterangepicker/daterangepicker.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/css/app.css')
    <style>
    /* Navbar responsif */
    .main-header #datetime {
        white-space: nowrap;
    }

    .main-header .navbar-nav.ml-auto {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
    }

    .main-header .nav-item.dropdown {
        white-space: nowrap;
    }

    @media (max-width: 576px) {
        /* Perkecil ukuran tanggal dan jam di HP */
        .main-header #datetime {
            font-size: 11px;
            padding-left: 4px;
            padding-right: 4px;
        }

        /* Nama pengguna jangan turun ke baris berikutnya */
         font-size: 12px;
            padding-left: 6px;
            padding-right: 6px;
            white-space: nowrap;
        }

</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="" src="{{ asset('images/logo.svg') }}" alt="Logo Duniatex" height="200" width="200">
    </div> --}}

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto align-items-center">
    <li class="nav-item">
        <span id="datetime" class="nav-link"></span>
    </li>

    <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @if(Auth::user()->role === 'siswa')
                        <a href="{{ route('siswa.profil') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profil Saya
                        </a>
                        <a href="{{ route('profil') }}" class="dropdown-item">
                            <i class="fas fa-key mr-2"></i> Ubah Password
                        </a>
                        @else
                        <a href="{{ route('profil') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <img src="{{ asset('images/logo1.png') }}" alt="Duniatex Logo" class="brand-image" style="opacity: .8">
                <span class="brand-text font-weight-bold">
                    <span style="color: #08332c;">SSDY Dashboard</span>
                </span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ asset('images/av1.png') }}" class="img-circle elevation-2" alt="User Image"
                            style="width: 50px; height: 50px;">
                    </div>
                    <div class="info">
                        @auth
                            <label style="font-size: 20px;" class="d-block"><b>{{ Auth::user()->name }}</b></label>
                        @else
                            <label class="d-block">Guest</label>
                        @endauth
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Dashboard Menu -->
                        <li class="nav-item menu">
                            @if(Auth::user()->role == 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            @elseif(Auth::user()->role == 'pelatih')
                                <a href="{{ route('pelatih.dashboard') }}"
                                    class="nav-link {{ Request::routeIs('pelatih.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            @else
                                <a href="{{ route('siswa.dashboard') }}"
                                    class="nav-link {{ Request::routeIs('siswa.dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            @endif
                        </li>

                        @if(Auth::user()->role == 'siswa')
                            <li class="nav-item menu">
                                <a href="{{ route('siswa.profil') }}"
                                    class="nav-link {{ Request::routeIs('siswa.profil') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-id-card"></i>
                                    <p>Profil Saya</p>
                                </a>
                            </li>
                            <li class="nav-item menu">
                                <a href="{{ route('siswa.evaluasi') }}"
                                    class="nav-link {{ Request::routeIs('siswa.evaluasi') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-chart-line"></i>
                                    <p>Hasil Evaluasi</p>
                                </a>
                            </li>
                            <li class="nav-item menu">
                                <a href="{{ route('siswa.riwayat') }}"
                                    class="nav-link {{ Request::routeIs('siswa.riwayat') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>Riwayat Tingkat</p>
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->role == 'pelatih')
                            <li class="nav-item menu">
                                <a href="{{ route('pelatih.data-siswa') }}"
                                    class="nav-link {{ Request::routeIs('pelatih.data-siswa') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Data Siswa</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('pelatih.input-nilai-harian') }}"
                                    class="nav-link {{ Request::routeIs('pelatih.input-nilai-harian') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-pen-square"></i>
                                    <p>Input Nilai Harian</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('pelatih.input-nilai-ujian') }}"
                                    class="nav-link {{ Request::routeIs('pelatih.input-nilai-ujian') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    <p>Input Nilai Ujian</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('pelatih.evaluasi-kenaikan-tingkat') }}"
                                    class="nav-link {{ Request::routeIs('pelatih.evaluasi-kenaikan-tingkat') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-chart-line"></i>
                                    <p>Eval Kenaikan Tingkat</p>
                                </a>
                            </li>

                            @php
                                $pelatihPendingPengajuan = 0;
                                if (Auth::user()->role === 'pelatih' && Auth::user()->pelatihProfile) {
                                    $pelatihPendingPengajuan = app(\App\Services\PengajuanMengulangService::class)
                                        ->countPendingForPelatih(Auth::user()->pelatihProfile);
                                }
                            @endphp
                            <li class="nav-item menu">
                                <a href="{{ route('pelatih.pengajuan-mengulang') }}"
                                    class="nav-link {{ Request::routeIs('pelatih.pengajuan-mengulang*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-redo"></i>
                                    <p>
                                        Pengajuan Mengulang
                                        @if($pelatihPendingPengajuan > 0)
                                        <span class="badge badge-warning right">{{ $pelatihPendingPengajuan }}</span>
                                        @endif
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->role == 'admin')
                            <li class="nav-item menu">
                                <a href="{{ route('admin.users') }}"
                                    class="nav-link {{ Request::routeIs('admin.users') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>User</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('admin.pendaftaran') }}"
                                    class="nav-link {{ Request::routeIs('admin.pendaftaran*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>Data Pendaftaran</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('admin.siswa.index') }}"
                                    class="nav-link {{ Request::routeIs('admin.siswa.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Data Siswa</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('admin.pelatih.index') }}"
                                    class="nav-link {{ Request::routeIs('admin.pelatih.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    <p>Data Pelatih</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('admin.tingkat.index') }}"
                                    class="nav-link {{ Request::routeIs('admin.tingkat.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-layer-group"></i>
                                    <p>Data Tingkat</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('admin.tahun-periode.index') }}"
                                    class="nav-link {{ Request::routeIs('admin.tahun-periode.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-calendar-alt"></i>
                                    <p>Data Tahun Periode</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('admin.materi-latihan.index') }}"
                                    class="nav-link {{ Request::routeIs('admin.materi-latihan.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Data Materi Latihan</p>
                                </a>
                            </li>

                            <li class="nav-item menu">
                                <a href="{{ route('admin.laporan') }}"
                                    class="nav-link {{ Request::routeIs('admin.laporan*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    <p>Laporan</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->

            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer text-center">
            <strong>
                Copyright &copy; {{ date('Y') }} <!-- Menggunakan Blade untuk mendapatkan tahun saat ini -->
                <a href="#" target="_blank">
                    <span style="color: #08332C;">Sanggar Seni Dharmo Yuwono</span>
                </a>.
            </strong>
            All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('lte/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('lte/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('lte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('lte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{ asset('lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('lte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{ asset('lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('lte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('lte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- dropzonejs -->
    <script src="{{ asset('lte/plugins/dropzone/min/dropzone.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('lte/dist/js/pages/dashboard3.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

    <script src="https://kit.fontawesome.com/de9d16bb0f.js" crossorigin="anonymous"></script>

    <!-- JavaScript to update version with real-time date and time -->
    <script>
        function updateDateTime() {
            const dateTimeElement = document.getElementById('datetime');
            if (dateTimeElement) {
                const now = new Date();
                const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const dayOfWeek = daysOfWeek[now.getDay()];
                const dayOfMonth = now.getDate().toString().padStart(2, '0');
                const month = (now.getMonth() + 1).toString().padStart(2, '0');
                const year = now.getFullYear();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');

                const dateTimeString = `${dayOfWeek}, ${dayOfMonth}-${month}-${year} | ${hours}:${minutes}:${seconds}`;
                dateTimeElement.textContent = dateTimeString;
            }
        }

        // Call the function initially when the page loads
        updateDateTime();

        // Update the date and time every second
        setInterval(updateDateTime, 1000); // 1000ms = 1 second

        document.addEventListener("DOMContentLoaded", function() {
            // Dapatkan elemen input pencarian
            var searchInput = document.getElementById("searchInput");

            if (searchInput) {
                // Dapatkan semua baris data dalam tabel
                var rows = document.querySelectorAll(".table tbody tr");

                // Tambahkan event listener untuk input pencarian
                searchInput.addEventListener("input", function() {
                    var searchText = searchInput.value.toLowerCase();

                    // Loop melalui setiap baris dalam tabel
                    rows.forEach(function(row) {
                        var rowData = row.textContent.toLowerCase();

                        // Periksa apakah teks pencarian ada dalam data baris
                        if (rowData.includes(searchText)) {
                            // Tampilkan baris jika ada kecocokan
                            row.style.display = "";
                        } else {
                            // Sembunyikan baris jika tidak ada kecocokan
                            row.style.display = "none";
                        }
                    });
                });
            }
        });
        $(document).ready(function() {
            function initializeDataTable(tableId) {
                $(tableId).DataTable({
                    "paging": true,
                    "responsive": false,
                    "lengthChange": true,
                    "autoWidth": false,
                    "scrollX": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "buttons": ["copy", "colvis"]
                }).buttons().container().appendTo($(tableId + '_wrapper .col-md-6:eq(0)'));
            }
            initializeDataTable('#user');
            initializeDataTable('#packing_list');
        });
      
        $(document).ready(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        });

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })

        $(function() {
            $('#reservation').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY' // Set the format to dd/mm/yyyy
                }
            });
        });

        $(function() {
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        })

        @if (session('success'))
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
            });

            Toast.fire({
                title: "{{ session('success') }}"
            });
        @endif

        @if (session('error'))
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'error',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
            });

            Toast.fire({
                title: "{{ session('error') }}"
            });
        @endif

        @if (session('info'))
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'info',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
            });

            Toast.fire({
                title: "{{ session('info') }}"
            });
        @endif
    </script>

    @yield('scripts')

</body>

</html>
