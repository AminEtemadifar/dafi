<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/Kalameh%204/kalameh-fa.css') }}">
    <title>موسیقی بهاری - دافی</title>
    
    <style>
        body {
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            text-align: center;
            color: #333;
            font-family: 'Kalameh', sans-serif;
        }

        .container {
            max-width: 320px;
            margin: 7% auto;
        }

        .hero-image {
            width: 100%;
        }

        h2 {
            font-size: 20px;
            margin: 20px 0;
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
            position: relative;
            display: inline-block;
            background: linear-gradient(90deg, #75559A 0%, #4A2A70 100%);
            color: #FFFFFF;
            padding: 12px 0px;
            border-radius: 9999px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(74,42,112,0.25);
            font-family: inherit;
            cursor: pointer;
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            width: 100%;
            display: inline-block;
            background: transparent;
            color: #4A2A70;
            border: 1px solid #4A2A70;
            padding: 12px 0px;
            border-radius: 9999px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            margin-top: 12px;
            cursor: pointer;
            font-family: inherit;
        }

        .steps-info {
            margin-top: 37px;
            text-align: right;
            font-size: 12px;
        }

        .steps {
            text-align: right;
            margin: 10px 0;
        }

        .step {
            background: #F2F3F7;
            padding: 12px;
            border-radius: 50px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .step label {
            display: block;
            color: #4A2A70;
            font-weight: bold;
            font-size: 11px;
            line-height: 8px;
        }

        .step span {
            font-size: 9px;
        }

        .footer {
            margin-top: 40px;
            background: #FACDD9;
            box-shadow: 0 1px 15px #B61146;
            border-radius: 50px;
            border: 1px dashed #4A2A70;
            padding: 10px 20px;
        }

        .footer img {
            vertical-align: middle;
        }

        .footer a {
            font-size: 14px !important;
            font-weight: bold;
        }

        .btn-instagram {
            color: #4A2A70;
            text-decoration: none;
            font-size: 12px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #4A2A70;
            margin-bottom: 6px;
            padding-right: 23px;
            justify-self: start;
        }

        .input-field {
            width: 100%;
            box-sizing: border-box;
            background: #F2F3F7;
            padding: 12px;
            padding-right: 23px !important;
            border-radius: 50px;
            font-size: 14px;
            border: none;
            outline: none;
            text-align: right;
            font-family: inherit;
        }

        .component {
            display: none;
        }

        .component.active {
            display: block;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #4A2A70;
        }

        /* Audio player specific styles */
        .audio-player-custom {
            margin: 0 auto 12px auto;
            width: 100%;
            direction: ltr;
            user-select: none;
        }

        .progress-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0 0 6px 0;
            padding: 0 8px;
        }

        .progress-time {
            font-size: 13px;
            color: #4A2A70;
            width: 44px;
            direction: ltr;
        }

        .progress-bar-wrap {
            flex: 1;
            margin: 0 8px;
            height: 24px;
            display: flex;
            align-items: center;
        }

        .progress-bar {
            width: 100%;
            height: 3px;
            background: #EEE6F6;
            border-radius: 8px;
            position: relative;
            cursor: pointer;
        }

        .progress-bar-filled {
            background: #4A2A70;
            height: 3px;
            border-radius: 8px;
            position: absolute;
            left: 0; top: 0;
        }

        .progress-knob {
            position: absolute;
            top: 50%;
            left: 0;
            width: 13px;
            height: 13px;
            background: #fff;
            border: 2px solid #4A2A70;
            border-radius: 50%;
            box-shadow: 0 0 6px #4A2A70aa;
            transform: translate(-50%,-50%);
            cursor: pointer;
            transition: box-shadow .12s;
        }

        .player-controls-row {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 0;
            gap: 28px;
        }

        .player-btn {
            background: none;
            border: none;
            outline: none;
            font-size: 2em;
            color: #4A2A70;
            cursor: pointer;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .player-btn:active, .player-btn:focus {
            color: #B61146;
        }

        .player-btn svg {
            display: block;
        }

        .player-actions-row {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 18px;
            gap: 32px;
        }

        .player-actions-row .player-btn {
            font-size: 1.6em;
            margin: 0 18px;
            color: #4A2A70;
        }

        .music-banner-box {
            width: 100%;
            height: 138px;
            position: relative;
            margin-bottom: 34px;
            background: none;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            align-items: stretch;
        }

        .banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5%;
            display: block;
        }

        .price {
            font-size: 30px;
            color: #4A2A70;
            margin: 20px 0;
        }

        .checkbox-container {
            display: flex;
            justify-content: start;
            align-items: center;
            margin: 20px 0 0 0;
            direction: rtl;
        }

        .checkbox-container input[type="checkbox"] {
            display: none;
        }

        .checkbox-container label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-family: inherit;
            position: relative;
        }

        .custom-checkbox {
            width: 17px;
            height: 16px;
            border: 2px solid #4A2A70;
            border-radius: 25%;
            background: #fff;
            box-shadow: 0 4px 18px #a38dcf55;
            display: inline-block;
            position: relative;
            margin-left: 10px;
            flex-shrink: 0;
        }

        .checkbox-label-text {
            color: #4A2A70;
            text-shadow: 0 3px 10px #4A2A70, 0 0px 0px #bca5e2;
            line-height: 1;
        }

        .custom-checkbox::after {
            content: '';
            position: absolute;
            display: none;
        }

        .checkbox-container input[type="checkbox"]:checked + label .custom-checkbox::after {
            display: block;
            left: 0px;
            top: -1px;
            width: 22px;
            height: 8px;
            border-left: 3px solid #B61146;
            border-bottom: 3px solid #B61146;
            border-radius: 2px;
            transform: rotate(-45deg);
            pointer-events: none;
        }

        .pay-box {
            display: none;
        }

        .show {
            display: block;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function showComponent(componentName) {
            $('.component').removeClass('active');
            $('#' + componentName).addClass('active');
            
            // Update URL without page reload
            history.pushState({component: componentName}, '', '/');
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
                    alert('خطا در بارگذاری صفحه');
                }
            });
        }

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.component) {
                showComponent(event.state.component);
            }
        });
    </script>
</body>
</html>
