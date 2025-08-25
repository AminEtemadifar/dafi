<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{ asset('assets/fonts/Kalameh%204/kalameh-fa.css') }}">
	<link rel="stylesheet" href="{{ asset('admin/css/style-rtl.alpha6.min.css') }}">
	<link rel="stylesheet" href="{{ asset('admin/css/app.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
	<title>پنل مدیریت</title>
	<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
	<script>
		window.csrfToken = '{{ csrf_token() }}';
		$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': window.csrfToken }});
	</script>

</head>
<body>
	<div class="admin-wrapper">
		<header class="admin-header" style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#1e1e2d;color:#fff;">
			<div class="brand" style="font-weight:700;">پنل مدیریت</div>
			<nav class="nav-actions" style="display:flex;gap:16px;align-items:center;">
				@auth
					<a href="{{ route('admin.dashboard') }}" style="color:#fff;text-decoration:none;">داشبورد</a>
					<a href="{{ route('admin.names.index') }}" style="color:#fff;text-decoration:none;">نام‌ها</a>
					<a href="{{ route('admin.payments') }}" style="color:#fff;text-decoration:none;">پرداخت‌ها</a>
					<a href="{{ route('admin.submits') }}" style="color:#fff;text-decoration:none;">درخواست‌ها</a>
				@endauth
				@auth
					<form method="POST" action="{{ route('admin.logout') }}">
						@csrf
						<button type="submit" class="btn-ghost" style="background:#ff5252;color:#fff;border:none;border-radius:6px;padding:6px 12px;">خروج</button>
					</form>
				@endauth
			</nav>
		</header>
		<main class="admin-container">
			@yield('content')
		</main>
	</div>

	<script src="{{ asset('admin/js/app-sidebar.js') }}"></script>
	@stack('scripts')
</body>
</html>
