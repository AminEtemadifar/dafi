@extends('admin.layouts.app')

@section('title', 'لیست پرداخت ها')
@section('page-title', 'لیست پرداخت ها')

@section('breadcrumbs')
    <li class="breadcrumb-item active">پرداخت ها</li>
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">لیست پرداخت ها</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search">جستجو</label>
                                    <input type="text" class="form-control" id="search" name="search"
                                           placeholder="جستجو بر اساس نام یا موبایل..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">وضعیت</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">همه</option>
                                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>موفق</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>ناموفق</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="gateway">درگاه پرداخت</label>
                                    <select class="form-control" id="gateway" name="gateway">
                                        <option value="">همه</option>
                                        <option value="zarinpal" {{ request('gateway') == 'zarinpal' ? 'selected' : '' }}>زرین پال</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sort">مرتب سازی</label>
                                    <select class="form-control" id="sort" name="sort">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>جدیدترین</option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>قدیمی ترین</option>
                                        <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>مبلغ (بیشترین)</option>
                                        <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>مبلغ (کمترین)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="icon-search"></i> جستجو
                                        </button>
                                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="icon-refresh"></i> پاک کردن
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Statistics Cards -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title pt-3">کل پرداخت ها</h5>
                                    <h3>{{ $totalTransactions }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title pt-3">پرداخت های موفق</h5>
                                    <h3>{{ $successfulTransactions }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title pt-3">پرداخت های ناموفق</h5>
                                    <h3>{{ $failedTransactions }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title pt-3">مجموع مبلغ</h5>
                                    <h3>{{ number_format($totalAmount) }} تومان</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="transactionsTable">
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>شماره موبایل</th>
                                    <th>مبلغ</th>
                                    <th>وضعیت</th>
                                    <th>درگاه پرداخت</th>
                                    <th>تاریخ پرداخت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->name }}</td>
                                    <td>
                                        <span class="font-family-monospace">{{ $transaction->mobile }}</span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold">{{ number_format($transaction->amount) }} تومان</span>
                                    </td>
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
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($transaction->gateway) }}</span>
                                    </td>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('Y/m/d H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-info" title="مشاهده">
                                                <i class="icon-eye"></i>
                                            </a>
                                            @if($transaction->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-warning" title="تغییر وضعیت"
                                                    onclick="changeTransactionStatus({{ $transaction->id }}, '{{ $transaction->name }}', '{{ $transaction->status }}')">
                                                <i class="icon-edit"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">هیچ پرداختی یافت نشد</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($transactions->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        <div class="pagination-wrapper">
                            {{ $transactions->appends(request()->query())->links() }}
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

@push('styles')
<style>
/* Responsive Pagination Styles */
.pagination-wrapper {
    width: 100%;
    overflow-x: auto;
}

.pagination {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 5px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.pagination li {
    margin: 0;
}

.pagination .page-link {
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    background-color: #fff;
    color: #007bff;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s ease;
    min-width: 40px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
    color: #0056b3;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .pagination .page-link {
        padding: 6px 8px;
        min-width: 35px;
        font-size: 14px;
    }

    .pagination .page-item:not(.active):not(.disabled) .page-link {
        display: none;
    }

    .pagination .page-item.active .page-link,
    .pagination .page-item.disabled .page-link,
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        display: flex;
    }

    .pagination .page-item:nth-child(2) .page-link,
    .pagination .page-item:nth-last-child(2) .page-link {
        display: flex;
    }
}

@media (max-width: 576px) {
    .pagination .page-link {
        padding: 5px 6px;
        min-width: 30px;
        font-size: 12px;
    }

    .pagination-wrapper {
        padding: 0 10px;
    }
}

/* Hide page numbers on very small screens, show only navigation */
@media (max-width: 480px) {
    .pagination .page-item:not(.active):not(.disabled):not(:first-child):not(:last-child) .page-link {
        display: none;
    }

    .pagination .page-item.active .page-link {
        min-width: 40px;
        padding: 6px 10px;
    }
}
</style>
@endpush

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

// Initialize DataTable
$(document).ready(function() {
    $('#transactionsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Persian.json"
        },
        "pageLength": {{ request('per_page', 10) }},
        "order": [[5, "desc"]], // Sort by date descending
        "responsive": true,
        "searching": false, // We're using our custom search form
        "paging" : false,
        "info": false // We're using our custom pagination
    });
});

// Auto-submit form when select values change
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('gateway').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('sort').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
