@extends('admin.layouts.app')

@section('title', 'مشاهده پرداخت')
@section('page-title', 'مشاهده پرداخت')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.transactions.index') }}">پرداخت ها</a></li>
    <li class="breadcrumb-item active">مشاهده پرداخت</li>
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">جزئیات پرداخت</h4>
                <div class="float-left">
                    @if($transaction->status === 'pending')
                    <button type="button" class="btn btn-warning btn-sm" 
                            onclick="changeTransactionStatus({{ $transaction->id }}, '{{ $transaction->name }}', '{{ $transaction->status }}')">
                        <i class="icon-edit"></i> تغییر وضعیت
                    </button>
                    @endif
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">
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
                                    <td>{{ $transaction->name }}</td>
                                </tr>
                                <tr>
                                    <th>شماره موبایل:</th>
                                    <td>
                                        <span class="font-family-monospace">{{ $transaction->mobile }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>مبلغ:</th>
                                    <td>
                                        <span class="font-weight-bold text-success">{{ number_format($transaction->amount) }} تومان</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>وضعیت:</th>
                                    <td>
                                        @switch($transaction->status)
                                            @case('success')
                                                <span class="badge badge-success">موفق</span>
                                                @break
                                            @case('failed')
                                                <span class="badge badge-danger">ناموفق</span>
                                                @break
                                            @case('pending')
                                                <span class="badge badge-warning">در انتظار</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ $transaction->status }}</span>
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <th>درگاه پرداخت:</th>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($transaction->gateway) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاریخ پرداخت:</th>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('Y/m/d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>اطلاعات تکمیلی:</label>
                                <div class="border rounded p-3">
                                    @if($transaction->authority)
                                        <p><strong>Authority:</strong> {{ $transaction->authority }}</p>
                                    @endif
                                    
                                    @if($transaction->ref_id)
                                        <p><strong>Ref ID:</strong> {{ $transaction->ref_id }}</p>
                                    @endif
                                    
                                    @if($transaction->card_pan)
                                        <p><strong>Card PAN:</strong> {{ $transaction->card_pan }}</p>
                                    @endif
                                    
                                    @if($transaction->verified_at)
                                        <div class="alert alert-success">
                                            <i class="icon-check-circle"></i>
                                            <strong>تایید شده</strong>
                                            <br>
                                            <small>تاریخ تایید: {{ $transaction->verified_at->format('Y/m/d H:i:s') }}</small>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="icon-alert-triangle"></i>
                                            <strong>تایید نشده</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($transaction->raw_request || $transaction->raw_response)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">اطلاعات فنی</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if($transaction->raw_request)
                                        <div class="col-md-6">
                                            <h6>درخواست:</h6>
                                            <pre class="bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;">{{ json_encode($transaction->raw_request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                        @endif
                                        
                                        @if($transaction->raw_response)
                                        <div class="col-md-6">
                                            <h6>پاسخ:</h6>
                                            <pre class="bg-light p-2 rounded" style="max-height: 200px; overflow-y: auto;">{{ json_encode($transaction->raw_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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
                <h5 class="modal-title" id="changeStatusModalLabel">تغییر وضعیت پرداخت</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>پرداخت: <strong id="transactionNameText"></strong></p>
                <p>وضعیت فعلی: <span id="currentStatusText"></span></p>
                
                <div class="form-group">
                    <label for="newStatus">وضعیت جدید</label>
                    <select class="form-control" id="newStatus">
                        <option value="success">موفق</option>
                        <option value="failed">ناموفق</option>
                        <option value="pending">در انتظار</option>
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
function changeTransactionStatus(id, name, currentStatus) {
    document.getElementById('transactionNameText').textContent = name;
    document.getElementById('currentStatusText').textContent = getStatusText(currentStatus);
    document.getElementById('newStatus').value = currentStatus;
    document.getElementById('changeStatusForm').action = '{{ route("admin.transactions.index") }}/' + id + '/status';
    
    $('#changeStatusModal').modal('show');
}

function getStatusText(status) {
    const statusMap = {
        'success': 'موفق',
        'failed': 'ناموفق',
        'pending': 'در انتظار'
    };
    return statusMap[status] || status;
}
</script>
@endpush
