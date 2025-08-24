@extends('layouts.admin')

@section('content')
<div class="card" style="margin:16px 0;">
	<div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;">
		<h2 class="title">داشبورد مدیریت</h2>
	</div>
	@if (session('status'))
		<div class="alert alert-success" role="alert" style="margin-top:12px;">{{ session('status') }}</div>
	@endif
	@if ($errors->any())
		<div class="alert alert-error" role="alert" style="margin-top:12px;">{{ $errors->first() }}</div>
	@endif
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:12px;">
	<div class="card info-card">
		<div class="info-title">پراستفاده‌ترین نام</div>
		<div class="info-value">{{ $mostUsedName?->name ?? '—' }}</div>
		<div class="info-sub">تعداد استفاده: {{ number_format($mostUsedName?->use_count ?? 0) }}</div>
	</div>
	<div class="card info-card">
		<div class="info-title">درخواست‌های در انتظار</div>
		<div class="info-value">{{ number_format($pendingCount) }}</div>
	</div>
</div>

<div class="card" style="margin-top:16px;">
	<h3 class="subtitle">درخواست‌های در انتظار (۵۰ مورد آخر)</h3>
	<div class="table">
		<div class="table-row table-header"><div>شماره موبایل</div><div>نام</div></div>
		@forelse($pendingSubmits as $s)
			<div class="table-row"><div>{{ $s->mobile }}</div><div>{{ $s->name }}</div></div>
		@empty
			<div class="table-row"><div colspan="2">موردی یافت نشد</div></div>
		@endforelse
	</div>
</div>

<div class="card" style="margin-top:16px;">
	<div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;">
		<h3 class="subtitle">فهرست نام‌ها</h3>
		<form method="GET" action="{{ route('admin.dashboard') }}" style="display:flex;gap:8px;align-items:center;">
			<label for="sort" style="white-space:nowrap;">مرتب‌سازی:</label>
			<select name="sort" id="sort" class="input-field" onchange="this.form.submit()">
				<option value="use_count_desc" {{ $sort==='use_count_desc' ? 'selected' : '' }}>بیشترین استفاده</option>
				<option value="name_asc" {{ $sort==='name_asc' ? 'selected' : '' }}>الفبا (الف تا ی)</option>
				<option value="name_desc" {{ $sort==='name_desc' ? 'selected' : '' }}>الفبا (ی تا الف)</option>
				<option value="latest" {{ $sort==='latest' ? 'selected' : '' }}>جدیدترین</option>
			</select>
		</form>
	</div>
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
				<div class="dropzone" id="dropzone">
					<div class="label">فایل را اینجا رها کنید یا کلیک کنید</div>
					<div class="hint">mp3, wav, ogg — حداکثر ۱۰ مگابایت</div>
					<input type="file" id="music" name="music" accept="audio/*" required>
					<div class="filename" id="filename">فایلی انتخاب نشده است</div>
				</div>
			</div>
			<div class="form-group">
				<label>درخواست‌های مطابق (انتخابی)</label>
				<div class="selectlike" id="matchesSelect">
					<div class="select-display"><span>انتخاب درخواست‌ها</span><span>▾</span></div>
					<div class="options" id="matches"></div>
				</div>
				<small>پس از وارد کردن نام، لیست درخواست‌های مطابق نمایش داده می‌شود.</small>
			</div>
		</div>
		<button type="submit" class="btn-primary">ثبت و ارسال پیامک</button>
	</form>
</div>

<script>
$(function(){
	// filename display
	$('#music').on('change', function(){
		var f = this.files && this.files[0] ? this.files[0].name : 'فایلی انتخاب نشده است';
		$('#filename').text(f);
	});

	// dropdown open/close
	$('#matchesSelect .select-display').on('click', function(){
		$('#matchesSelect').toggleClass('open');
	});
	$(document).on('click', function(e){
		if(!$(e.target).closest('#matchesSelect').length){ $('#matchesSelect').removeClass('open'); }
	});

	var fetchTimer = null;
	$('#name').on('input', function(){
		clearTimeout(fetchTimer);
		var name = $(this).val().trim();
		$('#matches').empty();
		if (name.length === 0) { return; }
		fetchTimer = setTimeout(function(){
			$.get("{{ route('admin.submits.byName') }}", { name: name })
				.done(function(resp){
					var items = resp.items || [];
					if (items.length === 0) {
						$('#matches').html('<div class="option" style="color:#888;">موردی یافت نشد</div>');
						return;
					}
					var html = items.map(function(it){
						var id = 'submit_'+it.id;
						return '<label class="option" for="'+id+'">'
							+ '<input type="checkbox" id="'+id+'" name="submits[]" value="'+it.id+'">'
							+ '<span style="direction:ltr;">'+it.mobile+'</span>'
							+ '<span style="color:#666;">('+it.name+')</span>'
							+ '</label>';
					}).join('');
					$('#matches').html(html);
				})
				.fail(function(){ alert('خطا در دریافت درخواست‌های مطابق'); });
		}, 500);
	});
});
</script>
@endsection