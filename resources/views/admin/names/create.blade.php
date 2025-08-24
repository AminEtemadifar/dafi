@extends('layouts.admin')

@section('content')
<div class="card" style="margin:16px 0;">
	<h2 class="title">افزودن نام جدید</h2>
	@if (session('status'))
		<div class="alert alert-success" role="alert" style="margin-top:12px;">{{ session('status') }}</div>
	@endif
	@if ($errors->any())
		<div class="alert alert-error" role="alert" style="margin-top:12px;">{{ $errors->first() }}</div>
	@endif
</div>

<div class="card">
	<form method="POST" action="{{ route('admin.names.store.simple') }}" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
		@csrf
		<div class="form-group" style="grid-column: span 2;">
			<label for="name">نام</label>
			<input class="input-field" type="text" id="name" name="name" value="{{ old('name') }}" required>
		</div>
		<div class="form-group" style="grid-column: span 2;">
			<label for="path">مسیر فایل (URL یا مسیر عمومی)</label>
			<input class="input-field" type="text" id="path" name="path" value="{{ old('path') }}" required>
		</div>
		<div style="grid-column: span 2;display:flex;gap:8px;">
			<button class="btn-primary" type="submit">ذخیره</button>
			<a class="btn-secondary" href="{{ route('admin.names.index') }}">بازگشت</a>
		</div>
	</form>
</div>
@endsection