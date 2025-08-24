@extends('layouts.admin')

@section('content')
<div class="card" style="max-width:480px;margin:40px auto;">
	<h2 class="title text-center">ورود مدیر</h2>
	@if ($errors->any())
		<div class="alert alert-error" role="alert" style="margin-bottom:12px;">
			{{ $errors->first() }}
		</div>
	@endif
	<form method="POST" action="{{ route('admin.login.attempt') }}">
		@csrf
		<div class="form-group">
			<label for="mobile">شماره موبایل</label>
			<input type="text" id="mobile" name="mobile" class="input-field" placeholder="مثلاً 09123456789" value="{{ old('mobile') }}" required>
		</div>
		<div class="form-group">
			<label for="password">گذرواژه</label>
			<input type="password" id="password" name="password" class="input-field" placeholder="******" required>
		</div>
		<div class="form-group" style="display:flex;align-items:center;gap:8px;">
			<input type="checkbox" id="remember" name="remember" value="1">
			<label for="remember" style="margin:0;">مرا به خاطر بسپار</label>
		</div>
		<button type="submit" class="btn-primary" style="width:100%;">ورود</button>
	</form>
</div>
@endsection