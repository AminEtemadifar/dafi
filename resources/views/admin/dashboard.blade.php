@extends('admin.layouts.app')

@section('title', 'داشبورد')
@section('page-title', 'داشبورد')

@section('breadcrumbs')
    <li class="breadcrumb-item active">داشبورد</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row match-height">
    <div class="col-xl-3 col-lg-6 col-12">
        <div class="card">
            <div class="card-content">
                <div class="media align-items-stretch">
                    <div class="p-2 text-center bg-primary bg-darken-2">
                        <i class="icon-credit-card text-white font-large-2"></i>
                    </div>
                    <div class="p-2 media-body">
                        <h5 class="text-bold-600">پرداخت های موفق</h5>
                        <h5 class="text-bold-600 text-primary">{{ $successfulPayments }}</h5>
                        <small class="text-muted">تعداد کل</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-12">
        <div class="card">
            <div class="card-content">
                <div class="media align-items-stretch">
                    <div class="p-2 text-center bg-success bg-darken-2">
                        <i class="icon-wallet text-white font-large-2"></i>
                    </div>
                    <div class="p-2 media-body">
                        <h5 class="text-bold-600">مجموع مبلغ</h5>
                        <h5 class="text-bold-600 text-success">{{ number_format($totalAmount) }} تومان</h5>
                        <small class="text-muted">کل پرداخت ها</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-12">
        <div class="card">
            <div class="card-content">
                <div class="media align-items-stretch">
                    <div class="p-2 text-center bg-warning bg-darken-2">
                        <i class="icon-check text-white font-large-2"></i>
                    </div>
                    <div class="p-2 media-body">
                        <h5 class="text-bold-600">درخواست های در انتظار</h5>
                        <h5 class="text-bold-600 text-info">{{ $requestedSubmits }}</h5>
                        <small class="text-muted">وضعیت requested</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-12">
        <div class="card">
            <div class="card-content">
                <div class="media align-items-stretch">
                    <div class="p-2 text-center bg-info bg-darken-2">
                        <i class="icon-clock text-white font-large-2"></i>
                    </div>
                    <div class="p-2 media-body">
                        <h5 class="text-bold-600">درخواست های انجام شده</h5>
                        <h5 class="text-bold-600 text-warning">{{ $doneSubmits }}</h5>
                        <small class="text-muted">وضعیت done</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row match-height">
    <div class="col-xl-8 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">آمار پرداخت های ماه جاری</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <canvas id="paymentChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">وضعیت درخواست ها</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <canvas id="submitStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Requested Submits -->
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">آخرین درخواست های در انتظار</h4>
                <a href="{{ route('admin.submits.index') }}" class="btn btn-sm btn-primary float-left">مشاهده همه</a>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>شماره موبایل</th>
                                    <th>تاریخ درخواست</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRequestedSubmits as $submit)
                                <tr>
                                    <td>{{ $submit->name }}</td>
                                    <td>{{ $submit->mobile }}</td>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($submit->created_at)->format('Y/m/d H:i') }}</td>
                                    <td>
                                        <span class="badge badge-warning">در انتظار</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.submits.show', $submit->id) }}" class="btn btn-sm btn-info">
                                            <i class="icon-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">هیچ درخواستی در انتظار نیست</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Names List with Pagination -->
<div class="row match-height">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">لیست نام ها</h4>
                <a href="{{ route('admin.names.create') }}" class="btn btn-sm btn-success float-left">
                    <i class="icon-plus"></i> افزودن نام جدید
                </a>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>مسیر فایل</th>
                                    <th>تعداد استفاده</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($names as $name)
                                <tr>
                                    <td>{{ $name->name }}</td>
                                    <td>{{ $name->path }}</td>
                                    <td>{{ $name->use_count }}</td>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($name->created_at)->format('Y/m/d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.names.edit', $name->id) }}" class="btn btn-sm btn-warning">
                                            <i class="icon-note"></i>
                                        </a>
                                        <a href="{{ route('admin.names.show', $name->id) }}" class="btn btn-sm btn-info">
                                            <i class="icon-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">هیچ نامی یافت نشد</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($names->hasPages())
                    <div class="d-flex justify-content-center mt-2">
                        {{ $names->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Pass PHP variables to JavaScript
window.dashboardData = {
    doneSubmits: @json($doneSubmits),
    requestedSubmits: @json($requestedSubmits),
    prepareSubmits: @json($prepareSubmits ?? 0),
    currentMonthPayments: @json($currentMonthPayments)
};

document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js is not loaded. Charts will not be displayed.');
        return;
    }

    // Payment Chart - Last Month Statistics
    const paymentChart = document.getElementById('paymentChart');
    if (paymentChart) {
        const paymentCtx = paymentChart.getContext('2d');
        new Chart(paymentCtx, {
            type: 'line',
            data: {
                labels: window.dashboardData.currentMonthPayments.labels,
                datasets: [{
                    label: 'پرداخت های موفق در ماه ' + window.dashboardData.currentMonthPayments.monthName,
                    data: window.dashboardData.currentMonthPayments.data,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'روز ماه'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'آمار پرداخت های موفق در ماه ' + window.dashboardData.currentMonthPayments.monthName
                    }
                }
            }
        });
    }

    // Submit Status Chart - Only Requested and Done
    const submitChart = document.getElementById('submitStatusChart');
    if (submitChart) {
        const submitCtx = submitChart.getContext('2d');
        new Chart(submitCtx, {
            type: 'doughnut',
            data: {
                labels: ['در انتظار', 'انجام شده'],
                datasets: [{
                    data: [window.dashboardData.requestedSubmits, window.dashboardData.doneSubmits],
                    backgroundColor: [
                        'rgb(255, 205, 86)', // Warning color for requested
                        'rgb(54, 162, 235)'  // Info color for done
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'وضعیت درخواست ها'
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endpush
