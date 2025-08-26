@extends('admin.layouts.app')

@section('title', 'لیست درخواست ها')
@section('page-title', 'لیست درخواست ها')

@section('breadcrumbs')
    <li class="breadcrumb-item active">درخواست ها</li>
@endsection

@section('content')
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">لیست درخواست ها</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('admin.submits.index') }}" class="mb-3">
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
                                        <option value="prepare" {{ request('status') == 'prepare' ? 'selected' : '' }}>تکمیل نشده</option>
                                        <option value="requested" {{ request('status') == 'requested' ? 'selected' : '' }}>درخواست شده</option>
                                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>انجام شده</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sort">مرتب سازی</label>
                                    <select class="form-control" id="sort" name="sort">
                                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>جدیدترین</option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>قدیمی ترین</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>نام (الف تا ی)</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>نام (ی تا الف)</option>
                                        <option value="mobile_asc" {{ request('sort') == 'mobile_asc' ? 'selected' : '' }}>موبایل (صعودی)</option>
                                        <option value="mobile_desc" {{ request('sort') == 'mobile_desc' ? 'selected' : '' }}>موبایل (نزولی)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="per_page">تعداد در صفحه</label>
                                    <select class="form-control" id="per_page" name="per_page">
                                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
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
                                        <a href="{{ route('admin.submits.index') }}" class="btn btn-secondary btn-sm">
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
                                <div class="card-body text-center ">
                                    <h5 class="card-title pt-3">کل درخواست ها</h5>
                                    <h3>{{ $totalSubmits }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title pt-3">در انتظار</h5>
                                    <h3>{{ $requestedSubmits }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title pt-3">انجام شده</h5>
                                    <h3>{{ $doneSubmits }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title pt-3">تکمیل نشده</h5>
                                    <h3>{{ $prepareSubmits }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submits Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="submitsTable">
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>شماره موبایل</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ درخواست</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submits as $submit)
                                <tr>
                                    <td>{{ $submit->name }}</td>
                                    <td>
                                        <span class="font-family-monospace">{{ $submit->mobile }}</span>
                                    </td>
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
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($submit->created_at)->format('Y/m/d H:i') }}</td>

                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.submits.show', $submit->id) }}" class="btn btn-sm btn-info" title="مشاهده">
                                                <i class="icon-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-warning" title="تغییر وضعیت"
                                                    onclick="changeStatus({{ $submit->id }}, '{{ $submit->name }}', '{{ $submit->request_status }}')">
                                                <i class="icon-note"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">هیچ درخواستی یافت نشد</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($submits->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        <div class="pagination-wrapper">
                            {{ $submits->appends(request()->query())->links() }}
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

// Initialize DataTable
$(document).ready(function() {
    $('#submitsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Persian.json"
        },
        "pageLength": {{ request('per_page', 10) }},
        "order": [[3, "desc"]], // Sort by date descending
        "responsive": true,
        "paging" : false,
        "searching": false, // We're using our custom search form
        "info": false // We're using our custom pagination
    });
});

// Auto-submit form when select values change
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('sort').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('per_page').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
