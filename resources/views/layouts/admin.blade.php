<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{ asset('assets/fonts/Kalameh%204/kalameh-fa.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
	<title>پنل مدیریت</title>
</head>
<body class="theme-light">
	<div class="admin-wrapper">
		<header class="admin-header">
			<div class="brand">پنل مدیریت</div>
			<nav class="nav-actions">
				<a class="btn-ghost" href="{{ route('admin.dashboard') }}">داشبورد</a>
				<a class="btn-ghost" href="{{ route('admin.transactions') }}">تراکنش‌ها</a>
				<a class="btn-ghost" href="{{ route('admin.names.index') }}">نام‌ها</a>
				<a class="btn-ghost" href="{{ route('admin.submits.index') }}">درخواست‌ها</a>
				<button type="button" id="themeToggle" class="btn-secondary" title="تغییر تم">🌓</button>
				@auth
					<form method="POST" action="{{ route('admin.logout') }}">
						@csrf
						<button type="submit" class="btn-ghost">خروج</button>
					</form>
				@endauth
			</nav>
		</header>
		<main class="admin-container">
			@yield('content')
		</main>
	</div>

	<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
	<script>
	(function(){
		var key = 'admin_theme';
		function applyTheme(t){
			document.body.classList.remove('theme-light','theme-dark');
			document.body.classList.add(t);
		}
		var saved = localStorage.getItem(key) || 'theme-light';
		applyTheme(saved);
		document.getElementById('themeToggle').addEventListener('click', function(){
			var cur = document.body.classList.contains('theme-dark') ? 'theme-dark' : 'theme-light';
			var next = cur === 'theme-dark' ? 'theme-light' : 'theme-dark';
			localStorage.setItem(key, next);
			applyTheme(next);
		});
	})();
	</script>
	@stack('scripts')
</body>
</html>