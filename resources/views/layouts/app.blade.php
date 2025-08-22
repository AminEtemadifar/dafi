<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/Kalameh%204/kalameh-fa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>موسیقی بهاری - دافی</title>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        function animateComponent(componentName) {
            var $el = $('#' + componentName);
            $el.removeClass('animate-in');
            // Force reflow to restart animation
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
                error: function() { alert('خطا در بارگذاری صفحه'); }
            });
        }

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.component) {
                showComponent(event.state.component);
            }
        });

        // Initial warm animation for default visible component
        $(function(){ animateComponent('welcome'); });
    </script>
</body>
</html>
