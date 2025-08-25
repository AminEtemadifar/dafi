<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{ asset('assets/fonts/Kalameh%204/kalameh-fa.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
	<title>پنل مدیریت</title>
	<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

</head>
<body>
	<div class="admin-wrapper">
		<header class="admin-header">
			<div class="brand">پنل مدیریت</div>
			<nav class="nav-actions">
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

	@stack('scripts')
</body>
</html>
