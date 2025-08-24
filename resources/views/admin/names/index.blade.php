@extends('layouts.admin')

@section('content')
<div class="card" style="margin:16px 0;">
	<h2 class="title">مدیریت نام‌ها</h2>
	<form method="GET" style="display:flex;gap:8px;align-items:flex-end;margin-top:12px;flex-wrap:wrap;">
		<div class="form-group" style="min-width:220px;">
			<label for="q">جستجو</label>
			<input class="input-field" type="text" id="q" name="q" value="{{ $q }}" placeholder="نام را وارد کنید">
		</div>
		<div class="form-group" style="min-width:180px;">
			<label for="sort">مرتب‌سازی</label>
			<select class="input-field" id="sort" name="sort">
				<option value="use_count_desc" {{ $sort==='use_count_desc' ? 'selected' : '' }}>بیشترین استفاده</option>
				<option value="name_asc" {{ $sort==='name_asc' ? 'selected' : '' }}>الفبا (الف تا ی)</option>
				<option value="name_desc" {{ $sort==='name_desc' ? 'selected' : '' }}>الفبا (ی تا الف)</option>
				<option value="latest" {{ $sort==='latest' ? 'selected' : '' }}>جدیدترین</option>
			</select>
		</div>
		<button class="btn-secondary" type="submit">اعمال</button>
		<a class="btn-primary" href="{{ route('admin.names.create') }}">افزودن نام جدید</a>
	</form>
</div>

<div class="card">
	<div class="table">
		<div class="table-row table-header"><div>نام</div><div>تعداد استفاده</div><div>مسیر فایل</div></div>
		@foreach($names as $n)
			<div class="table-row"><div>{{ $n->name }}</div><div>{{ number_format($n->use_count) }}</div><div style="overflow:hidden;text-overflow:ellipsis;">{{ $n->path }}</div></div>
		@endforeach
	</div>
	<div style="margin-top:8px;">
		{{ $names->links() }}
	</div>
</div>
@endsection