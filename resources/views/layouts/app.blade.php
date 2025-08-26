<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/Kalameh%204/kalameh-fa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>موسیقی بهاری - دافی</title>
    <link rel="icon" type="image/png" href="{{  asset('admin-assets/img/logo.png') }}">

</head>
<body>
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner">
            <div class="spinner-ring"></div>
            <div class="loading-text">در حال بارگذاری...</div>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            beforeSend: function() {
                showLoading();
            },
            complete: function() {
                hideLoading();
            }
        });

        // Loading functions
        function showLoading() {
            $('#loadingOverlay').fadeIn(300);
        }

        function hideLoading() {
            $('#loadingOverlay').fadeOut(300);
        }

        // SweetAlert2 error handler - Small toast style
        function showError(message, title = 'خطا') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: '#fff',
                customClass: {
                    popup: 'swal-toast-rtl'
                }
            });
        }

        function animateComponent(componentName) {
            var $el = $('#' + componentName);
            $el.removeClass('animate-in');
            void $el[0].offsetWidth;
            $el.addClass('animate-in');
        }

        function showComponent(componentName) {
            $('.component').removeClass('active');
            var $target = $('#' + componentName);
            $target.addClass('active');
            history.pushState({component: componentName}, '', '/');
            animateComponent(componentName);
        }

        function loadComponent(componentName) {
            $.ajax({
                url: '/component/' + componentName,
                method: 'GET',
                success: function(response) {
                    $('#' + componentName).html(response);
                    showComponent(componentName);
                },
                error: function() {
                    showError('خطا در بارگذاری صفحه');
                }
            });
        }

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.component) {
                showComponent(event.state.component);
            }
        });

        // Initial warm animation for default visible component
        $(function(){
            animateComponent('welcome');
            // Hide loading on page load
            hideLoading();
        });
    </script>
</body>
</html>
