@extends('layouts.admin')

@section('content')
<div class="card" style="margin:16px 0;">
	<h2 class="title">تراکنش‌ها</h2>
	<form method="GET" style="display:flex;gap:8px;align-items:flex-end;margin-top:12px;flex-wrap:wrap;">
		<div class="form-group" style="min-width:200px;">
			<label for="mobile">شماره موبایل</label>
			<input class="input-field" type="text" id="mobile" name="mobile" value="{{ request('mobile') }}" placeholder="مثلاً 0912...">
		</div>
		<div class="form-group" style="min-width:160px;">
			<label for="status">وضعیت</label>
			<select class="input-field" id="status" name="status">
				<option value="">همه</option>
				@foreach(['init'=>'ایجاد شده','requested'=>'ارسال به درگاه','success'=>'موفق','failed'=>'ناموفق'] as $k=>$v)
					<option value="{{ $k }}" {{ request('status')===$k ? 'selected' : '' }}>{{ $v }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group" style="min-width:160px;">
			<label for="sort">مرتب‌سازی</label>
			<select class="input-field" id="sort" name="sort">
				<option value="created_desc" {{ request('sort','created_desc')==='created_desc'?'selected':'' }}>جدیدترین</option>
				<option value="created_asc" {{ request('sort')==='created_asc'?'selected':'' }}>قدیمی‌ترین</option>
				<option value="amount_desc" {{ request('sort')==='amount_desc'?'selected':'' }}>مبلغ (بیشترین)</option>
				<option value="amount_asc" {{ request('sort')==='amount_asc'?'selected':'' }}>مبلغ (کمترین)</option>
				<option value="status" {{ request('sort')==='status'?'selected':'' }}>وضعیت</option>
			</select>
		</div>
		<button class="btn-secondary" type="submit">اعمال فیلتر</button>
	</form>
</div>

<div class="card">
	<div class="table">
		<div class="table-row table-header">
			<div>شناسه</div>
			<div>موبایل</div>
			<div>مبلغ</div>
			<div>درگاه</div>
			<div>وضعیت</div>
			<div>کد رهگیری</div>
			<div>Authority</div>
			<div>تاریخ</div>
		</div>
		@foreach($transactions as $t)
			<div class="table-row" style="grid-template-columns: .6fr 1fr 1fr 1fr 1fr 1.4fr 1.8fr 1.6fr;">
				<div>#{{ $t->id }}</div>
				<div style="direction:ltr;">{{ $t->mobile }}</div>
				<div>{{ number_format($t->amount) }} ریال</div>
				<div>{{ $t->gateway }}</div>
				<div>{{ $t->status }}</div>
				<div style="direction:ltr;">{{ $t->ref_id ?? '—' }}</div>
				<div style="direction:ltr;">{{ $t->authority }}</div>
				<div>{{ $t->created_at->format('Y/m/d H:i') }}</div>
			</div>
		@endforeach
	</div>
	<div style="margin-top:8px;">
		{{ $transactions->links() }}
	</div>
</div>
@endsection