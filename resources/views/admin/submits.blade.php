@extends('layouts.admin')

@section('content')
<div class="card" style="margin:16px 0;">
	<h2 class="title">مدیریت درخواست‌ها</h2>
</div>

<div class="card">
	<form method="GET" style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;">
		<div class="form-group">
			<label for="name">نام</label>
			<input class="input-field" type="text" id="name" name="name" value="{{ request('name') }}">
		</div>
		<div class="form-group">
			<label for="mobile">شماره موبایل</label>
			<input class="input-field" type="text" id="mobile" name="mobile" value="{{ request('mobile') }}">
		</div>
		<div class="form-group">
			<label for="status">وضعیت</label>
			<select class="input-field" id="status" name="status">
				<option value="">همه</option>
				@foreach(['prepare'=>'در حال آماده‌سازی','requested'=>'درخواست شده','done'=>'انجام شده'] as $k=>$v)
					<option value="{{ $k }}" {{ request('status')===$k ? 'selected' : '' }}>{{ $v }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<label for="sort">مرتب‌سازی</label>
			<select class="input-field" id="sort" name="sort">
				<option value="created_desc" {{ request('sort','created_desc')==='created_desc'?'selected':'' }}>جدیدترین</option>
				<option value="created_asc" {{ request('sort')==='created_asc'?'selected':'' }}>قدیمی‌ترین</option>
				<option value="name_asc" {{ request('sort')==='name_asc'?'selected':'' }}>نام (الف تا ی)</option>
				<option value="name_desc" {{ request('sort')==='name_desc'?'selected':'' }}>نام (ی تا الف)</option>
			</select>
		</div>
		<button class="btn-secondary" type="submit">اعمال</button>
	</form>
</div>

<div class="card" style="margin-top:12px;">
	<div class="table">
		<div class="table-row table-header">
			<div>شناسه</div>
			<div>نام</div>
			<div>موبایل</div>
			<div>وضعیت</div>
			<div>تاریخ</div>
		</div>
		@foreach($submits as $s)
			<div class="table-row" style="grid-template-columns: .6fr 1fr 1fr 1fr 1.4fr;">
				<div>#{{ $s->id }}</div>
				<div>{{ $s->name }}</div>
				<div style="direction:ltr;">{{ $s->mobile }}</div>
				<div>{{ $s->request_status }}</div>
				<div>{{ $s->created_at?->format('Y/m/d H:i') }}</div>
			</div>
		@endforeach
	</div>
	<div style="margin-top:8px;">
		{{ $submits->links() }}
	</div>
</div>
@endsection