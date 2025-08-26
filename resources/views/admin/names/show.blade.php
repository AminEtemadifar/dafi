@extends('admin.layouts.app')

@section('title', 'مشاهده نام')
@section('page-title', 'مشاهده نام')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.names.index') }}">نام ها</a></li>
    <li class="breadcrumb-item active">مشاهده نام</li>
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">جزئیات نام</h4>
                <div class="float-left">
                    <a href="{{ route('admin.names.edit', $name->id) }}" class="btn btn-warning btn-sm">
                        <i class="icon-edit"></i> ویرایش
                    </a>
                    <a href="{{ route('admin.names.index') }}" class="btn btn-secondary btn-sm">
                        <i class="icon-arrow-left"></i> بازگشت
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">نام:</th>
                                    <td>{{ $name->name }}</td>
                                </tr>
                                <tr>
                                    <th>تعداد استفاده:</th>
                                    <td>
                                        <span class="badge badge-info">{{ number_format($name->use_count) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاریخ ایجاد:</th>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($name->created_at)->format('Y/m/d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>آخرین بروزرسانی:</th>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($name->updated_at)->format('Y/m/d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>فایل موسیقی:</label>
                                <div class="border rounded p-3">
                                    <p class="mb-2"><strong>مسیر:</strong> {{ $name->path }}</p>
                                    @if(Storage::disk('public')->exists($name->path))
                                        <audio controls class="w-100">
                                            <source src="{{ asset('storage/' . $name->path) }}" type="audio/mpeg">
                                            مرورگر شما از پخش صدا پشتیبانی نمی کند.
                                        </audio>
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $name->path) }}" class="btn btn-sm btn-primary" download>
                                                <i class="icon-download"></i> دانلود فایل
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="icon-alert-triangle"></i>
                                            فایل موسیقی یافت نشد!
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
