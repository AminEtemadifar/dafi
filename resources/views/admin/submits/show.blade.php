@extends('admin.layouts.app')

@section('title', 'مشاهده درخواست')
@section('page-title', 'مشاهده درخواست')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.submits.index') }}">درخواست ها</a></li>
    <li class="breadcrumb-item active">مشاهده درخواست</li>
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">جزئیات درخواست</h4>
                <div class="float-left">
                    <button type="button" class="btn btn-warning btn-sm"
                            onclick="changeStatus({{ $submit->id }}, '{{ $submit->name }}', '{{ $submit->request_status }}')">
                        <i class="icon-edit"></i> تغییر وضعیت
                    </button>
                    <a href="{{ route('admin.submits.index') }}" class="btn btn-secondary btn-sm">
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
                                    <td>{{ $submit->name }}</td>
                                </tr>
                                <tr>
                                    <th>شماره موبایل:</th>
                                    <td>
                                        <span class="font-family-monospace">{{ $submit->mobile }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>وضعیت:</th>
                                    <td>
                                        @switch($submit->request_status)
                                            @case('prepare')
                                                <span class="badge badge-info">تکمیل نشده</span>
                                                @break
                                            @case('requested')
                                                <span class="badge badge-warning">درخواست شده</span>
                                                @break
                                            @case('done')
                                                <span class="badge badge-success">انجام شده</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $submit->request_status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاریخ درخواست:</th>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($submit->created_at)->format('Y/m/d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>آخرین بروزرسانی:</th>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($submit->updated_at)->format('Y/m/d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>اطلاعات تایید موبایل:</label>
                                <div class="border rounded p-3">
                                    @if($submit->mobile_verified_at)
                                        <div class="">
                                            <i class="icon-check-circle"></i>
                                            <strong>تایید شده</strong>
                                            <br>
                                            <small>تاریخ تایید: {{ $submit->mobile_verified_at->format('Y/m/d H:i:s') }}</small>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="icon-alert-triangle"></i>
                                            <strong>تایید نشده</strong>
                                        </div>
                                    @endif

                                    @if($submit->otp_code)
                                        <div class="mt-2">
                                            <strong>کد OTP:</strong> {{ $submit->otp_code }}
                                            @if($submit->otp_expires_at)
                                                <br>
                                                <small class="text-muted">
                                                    منقضی می شود: {{ $submit->otp_expires_at->format('Y/m/d H:i:s') }}
                                                </small>
                                            @endif
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

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeStatusModalLabel">تغییر وضعیت درخواست</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>درخواست: <strong id="submitNameText"></strong></p>
                <p>وضعیت فعلی: <span id="currentStatusText"></span></p>

                <div class="form-group">
                    <label for="newStatus">وضعیت جدید</label>
                    <select class="form-control" id="newStatus">
                        <option value="prepare">تکمیل نشده</option>
                        <option value="requested">درخواست شده</option>
                        <option value="done">انجام شده</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
                <form id="changeStatusForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary">تغییر وضعیت</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function changeStatus(id, name, currentStatus) {
    document.getElementById('submitNameText').textContent = name;
    document.getElementById('currentStatusText').textContent = getStatusText(currentStatus);
    document.getElementById('newStatus').value = currentStatus;
    document.getElementById('changeStatusForm').action = '{{ route("admin.submits.index") }}/' + id + '/status';

    $('#changeStatusModal').modal('show');
}

function getStatusText(status) {
    const statusMap = {
        'prepare': 'تکمیل نشده',
        'requested': 'درخواست شده',
        'done': 'انجام شده'
    };
    return statusMap[status] || status;
}
</script>
@endpush
