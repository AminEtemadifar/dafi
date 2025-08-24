@extends('layouts.admin')

@section('content')
<div class="card" style="margin:16px 0;">
	<h2 class="title">درخواست‌ها</h2>
	<form method="GET" style="display:flex;gap:8px;align-items:flex-end;margin-top:12px;flex-wrap:wrap;">
		<div class="form-group" style="min-width:200px;">
			<label for="mobile">شماره موبایل</label>
			<input class="input-field" type="text" id="mobile" name="mobile" value="{{ $mobile }}" placeholder="مثلاً 0912...">
		</div>
		<div class="form-group" style="min-width:200px;">
			<label for="name">نام</label>
			<input class="input-field" type="text" id="name" name="name" value="{{ $name }}" placeholder="مثلاً امیر">
		</div>
		<div class="form-group" style="min-width:160px;">
			<label for="status">وضعیت</label>
			<select class="input-field" id="status" name="status">
				<option value="">همه</option>
				@foreach($requestStatuses as $k=>$v)
					<option value="{{ $k }}" {{ $status===$k ? 'selected' : '' }}>{{ $v }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group" style="min-width:160px;">
			<label for="sort">مرتب‌سازی</label>
			<select class="input-field" id="sort" name="sort">
				<option value="latest" {{ $sort==='latest' ? 'selected' : '' }}>جدیدترین</option>
				<option value="oldest" {{ $sort==='oldest' ? 'selected' : '' }}>قدیمی‌ترین</option>
			</select>
		</div>
		<button class="btn-secondary" type="submit">اعمال فیلتر</button>
	</form>
</div>

<div class="card">
	<div class="table">
		<div class="table-row table-header"><div>نام</div><div>شماره موبایل</div><div>وضعیت</div><div>تاریخ</div></div>
		@foreach($submits as $s)
			<div class="table-row"><div>{{ $s->name }}</div><div style="direction:ltr;">{{ $s->mobile }}</div><div>{{ $s->request_status }}</div><div>{{ $s->created_at->format('Y/m/d H:i') }}</div></div>
		@endforeach
	</div>
	<div style="margin-top:8px;">
		{{ $submits->links() }}
	</div>
</div>
@endsection