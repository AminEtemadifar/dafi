<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="ورود به پنل ادمین دافی">
    <meta name="keywords" content="admin login, دافی, ادمین">
    <meta name="author" content="دافی">
    <title>ورود به پنل ادمین - دافی</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900%7CMontserrat:300,400,500,600,700,800,900" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/feather/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/simple-line-icons/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/font-awesome/css/font-awesome.min.css') }}">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/prism.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css/style-rtl.alpha6.min.css') }}">
</head>
<body data-col="1-column" class="1-column blank-page blank-page">
    <div class="wrapper">
        <!-- Login Page Starts -->
        <section id="login">
            <div class="container-fluid">
                <div class="row full-height-vh">
                    <div class="col-12 d-flex align-items-center justify-content-center gradient-aqua-marine">
                        <div class="card px-4 py-2 box-shadow-2 width-400">
                            <div class="card-header text-center">
                                <img src="{{ asset('admin-assets/img/logo.png') }}" alt="company-logo" class="mb-3" width="80">
                                <h4 class="text-uppercase text-bold-400 grey darken-1">ورود به پنل ادمین</h4>
                            </div>
                            <div class="card-body">
                                <div class="card-block">
                                    <form method="POST" action="{{ route('admin.login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control form-control-lg @error('mobile') is-invalid @enderror"
                                                       name="mobile" id="mobile" placeholder="موبایل" required
                                                       value="{{ old('mobile') }}">
                                                @error('mobile')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                       name="password" id="password" placeholder="رمز عبور" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0 ml-5">
                                                        <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                                        <label class="custom-control-label float-left" for="remember">مرا به خاطر بسپار</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="text-center col-md-12">
                                                <button type="submit" class="btn btn-danger px-4 py-2 text-uppercase white font-small-4 box-shadow-2 border-0">
                                                    ورود
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Login Page Ends -->
    </div>

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('admin-assets/vendors/js/core/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/prism.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/jquery.matchHeight-min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/screenfull.min.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/pace/pace.min.js') }}"></script>
</body>
</html>
