@extends('layouts.admin')

@section('content')
<div class="card" style="margin:16px 0;">
	<div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;">
		<h2 class="title">مدیریت نام‌ها</h2>
	</div>
	@if (session('status'))
		<div class="alert alert-success" role="alert" style="margin-top:12px;">{{ session('status') }}</div>
	@endif
	@if ($errors->any())
		<div class="alert alert-error" role="alert" style="margin-top:12px;">{{ $errors->first() }}</div>
	@endif
</div>

<div class="card">
	<form method="GET" style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap;">
		<div class="form-group" style="min-width:200px;">
			<label for="q">جستجو</label>
			<input class="input-field" type="text" id="q" name="q" value="{{ request('q') }}" placeholder="نام ...">
		</div>
		<div class="form-group">
			<label for="sort">مرتب‌سازی</label>
			<select class="input-field" id="sort" name="sort">
				<option value="use_count_desc" {{ request('sort','use_count_desc')==='use_count_desc'?'selected':'' }}>بیشترین استفاده</option>
				<option value="name_asc" {{ request('sort')==='name_asc'?'selected':'' }}>الفبا (الف تا ی)</option>
				<option value="name_desc" {{ request('sort')==='name_desc'?'selected':'' }}>الفبا (ی تا الف)</option>
				<option value="latest" {{ request('sort')==='latest'?'selected':'' }}>جدیدترین</option>
			</select>
		</div>
		<button class="btn-secondary" type="submit">اعمال</button>
	</form>
</div>

<div class="card" style="margin-top:12px;">
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

<div class="card" style="margin-top:16px;">
	<h3 class="subtitle">افزودن نام جدید و ارسال برای درخواست‌ها</h3>
	<form id="addNameForm" method="POST" action="{{ route('admin.names.store') }}" enctype="multipart/form-data">
		@csrf
		<div class="grid" style="grid-template-columns: 1fr 1fr; gap:12px;">
			<div class="form-group" style="grid-column: span 2;">
				<label for="name">نام</label>
				<input type="text" id="name" name="name" class="input-field" placeholder="مثلاً امیر" required>
			</div>
			<div class="form-group">
				<label for="music">فایل موسیقی</label>
				<div class="dropzone">
					<div class="label">فایل را اینجا رها کنید یا کلیک کنید</div>
					<div class="hint">mp3, wav, ogg — حداکثر ۱۰ مگابایت</div>
					<input type="file" id="music" name="music" accept="audio/*" required>
					<div class="filename" id="filename">فایلی انتخاب نشده است</div>
				</div>
			</div>
			<div class="form-group">
				<label>درخواست‌های مطابق (انتخابی)</label>
				<div id="matches" class="list" style="max-height:220px;overflow:auto;border:1px solid #eee;border-radius:8px;padding:8px;"></div>
				<small>پس از وارد کردن نام، لیست درخواست‌های مطابق نمایش داده می‌شود.</small>
			</div>
		</div>
		<button type="submit" class="btn-primary">ثبت و ارسال پیامک</button>
	</form>
</div>

@push('scripts')
<script src="{{ asset('admin/js/dropzone.js') }}"></script>
<script>
$(function(){
	$('#music').on('change', function(){
		var f = this.files && this.files[0] ? this.files[0].name : 'فایلی انتخاب نشده است';
		$('#filename').text(f);
	});

	var fetchTimer = null;
	$('#name').on('input', function(){
		clearTimeout(fetchTimer);
		var name = $(this).val().trim();
		if (name.length === 0) {
			$('#matches').html('<div style="color:#888;">—</div>');
			return;
		}
		fetchTimer = setTimeout(function(){
			$.get("{{ route('admin.names.autocomplete') }}", { name: name })
				.done(function(resp){
					var items = resp.items || [];
					if (items.length === 0) {
						$('#matches').html('<div style="color:#888;">موردی یافت نشد</div>');
						return;
					}
					var html = items.map(function(it){
						return '<label style="display:flex;align-items:center;gap:8px;margin:4px 0;">'
							+ '<input type="checkbox" name="submits[]" value="'+it.id+'">'
							+ '<span style="direction:ltr;">'+it.mobile+'</span>'
							+ '<span style="color:#666;">('+it.name+')</span>'
							+ '</label>';
					}).join('');
					$('#matches').html(html);
				})
				.fail(function(){ $('#matches').html('<div style="color:#c00;">خطا در دریافت لیست</div>'); });
		}, 400);
	});
});
</script>
@endpush
@endsection