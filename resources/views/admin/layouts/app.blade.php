<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="پنل ادمین دافی">
    <meta name="keywords" content="admin panel, دافی, ادمین">
    <meta name="author" content="دافی">
    <title>@yield('title', 'پنل ادمین') - دافی</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{  asset('admin-assets/img/logo.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900%7CMontserrat:300,400,500,600,700,800,900" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/feather/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/simple-line-icons/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/font-awesome/css/font-awesome.min.css') }}">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/prism.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/dropzone.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/toastr.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/app.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/style-rtl.alpha6.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/admin-custom.css') }}">

    @stack('styles')
</head>
<body data-col="2-columns" class="2-columns">
    <div class="wrapper">
        <!-- Sidebar -->
        <div data-active-color="white" data-background-color="black" data-image="{{ asset('admin-assets/img/sidebar-bg/01.jpg') }}" class="app-sidebar">
            <div class="sidebar-header">
                <div class="logo clearfix">
                    <a href="{{ route('admin.dashboard') }}" class="logo-text float-right">
                        <div class="logo-img">
                            <img src="{{ asset('admin-assets/img/logo.png') }}" alt="دافی لوگو"/>
                        </div>
                    </a>
                    <a id="sidebarToggle" href="javascript:;" class="nav-toggle d-none d-sm-none d-md-none d-lg-block">
                        <i data-toggle="expanded" class="ft-disc toggle-icon"></i>
                    </a>
                    <a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none">
                        <i class="ft-circle"></i>
                    </a>
                </div>
            </div>

            <div class="sidebar-content">
                <div class="nav-container">
                    <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="icon-home"></i>
                                <span class="menu-title">داشبورد</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.names.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.names.index') }}">
                                <i class="icon-user"></i>
                                <span class="menu-title">نام ها</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.submits.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.submits.index') }}">
                                <i class="icon-list"></i>
                                <span class="menu-title">درخواست ها</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.transactions.index') }}">
                                <i class="icon-credit-card"></i>
                                <span class="menu-title">پرداخت ها</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i>
                                <span class="menu-title">خروج</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-panel">
           <div class="main-content">
               <div class="content-wrapper">
                   <div class="content-header row">
                       <div class="content-header-left col-md-6 col-12 mb-2">
                           <h3 class="content-header-title">@yield('page-title', 'پنل ادمین')</h3>
                       </div>

                   </div>

                   <div class="content-body">
                       @if(session('success'))
                           <div class="alert alert-success alert-dismissible mb-2" role="alert">
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">×</span>
                               </button>
                               {{ session('success') }}
                           </div>
                       @endif

                       @if(session('error'))
                           <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">×</span>
                               </button>
                               {{ session('error') }}
                           </div>
                       @endif

                       @if($errors->any())
                           <div class="alert alert-danger alert-dismissible mb-2" role="alert">
                               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">×</span>
                               </button>
                               <ul class="mb-0">
                                   @foreach($errors->all() as $error)
                                       <li>{{ $error }}</li>
                                   @endforeach
                               </ul>
                           </div>
                       @endif

                       @yield('content')
                   </div>
               </div>
           </div>
        </div>

        <!-- Footer -->
        <footer class="footer footer-static footer-light navbar-border navbar-shadow">
            <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
                <span class="float-md-left d-block d-md-inline-block">
                    کپی رایت &copy; {{ date('Y') }} <a class="text-bold-800 grey darken-2" href="#" target="_blank">دافی</a>، تمامی حقوق محفوظ است.
                </span>
            </p>
        </footer>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('admin-assets/vendors/js/core/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/prism.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/jquery.matchHeight-min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/screenfull.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/pace/pace.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('admin-assets/vendors/js/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/datatable/dataTables.responsive.min.js') }}"></script>

    <!-- Charts -->
    <script src="{{ asset('admin-assets/vendors/js/chart.min.js') }}"></script>

    <!-- Other Plugins -->
    <script src="{{ asset('admin-assets/vendors/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/toastr.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admin-assets/js/app-sidebar.js') }}"></script>
    <script src="{{ asset('admin-assets/js/admin-custom.js') }}"></script>

    @stack('scripts')
</body>
</html>
