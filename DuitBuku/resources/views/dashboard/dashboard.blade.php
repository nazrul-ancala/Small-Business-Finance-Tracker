@extends('master_page.master_page')
@section('page_title', 'Dashboard')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
@endpush

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Dashboard</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
</div>

{{-- Section 1: KPI Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Income This Month</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success">RM 0.00</h4>
                        <p class="text-muted fs-12 mb-0 mt-1">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="ti ti-trending-up text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Expense This Month</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger">RM 0.00</h4>
                        <p class="text-muted fs-12 mb-0 mt-1">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger-subtle rounded fs-3">
                            <i class="ti ti-trending-down text-danger"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Net Profit</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary">RM 0.00</h4>
                        <p class="text-muted fs-12 mb-0 mt-1">Income − Expense</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ti ti-wallet text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Pending Invoices</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-warning">0</h4>
                        <p class="text-muted fs-12 mb-0 mt-1">Awaiting payment</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                            <i class="ti ti-file-invoice text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section 2: Recent Transactions + Quick Actions --}}
<div class="row mb-4">

    {{-- Recent Transactions --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0">Recent Transactions</h5>
                <a href="{{ route('transactions.all') }}" class="btn btn-sm btn-ghost-secondary ms-auto">
                    View All <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th class="text-center">Type</th>
                                <th class="text-end pe-3">Amount (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="ti ti-receipt-off fs-2 d-block mb-2"></i>
                                    No transactions recorded yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-success text-start" id="btnQuickTransaction">
                        <i class="ti ti-plus me-2"></i> Record Transaction
                    </button>
                    <a href="{{ route('invoices.all') }}" class="btn btn-outline-primary text-start">
                        <i class="ti ti-file-plus me-2"></i> New Invoice
                    </a>
                    <a href="{{ route('drawings.all') }}" class="btn btn-outline-danger text-start">
                        <i class="ti ti-user-dollar me-2"></i> Record Drawing
                    </a>
                    <a href="{{ route('cashflow.calendar') }}" class="btn btn-outline-info text-start">
                        <i class="ti ti-calendar-plus me-2"></i> Add Forecast Entry
                    </a>
                    <a href="{{ route('pl.month') }}" class="btn btn-outline-secondary text-start">
                        <i class="ti ti-chart-bar me-2"></i> View P&amp;L
                    </a>
                    <a href="{{ route('business.health') }}" class="btn btn-outline-dark text-start">
                        <i class="ti ti-heart-rate-monitor me-2"></i> Business Health
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Section 3: Business Health Mini + Upcoming Bills --}}
<div class="row">

    {{-- Business Health Mini --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0"><i class="ti ti-heart-rate-monitor me-2 text-danger"></i>Business Health</h5>
                <a href="{{ route('business.health') }}" class="btn btn-sm btn-ghost-secondary ms-auto">
                    Full Report <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body text-center">
                <div class="fs-36 fw-bold text-danger lh-1 mb-2">
                    0 <span class="fs-18 text-muted fw-normal">/ 100</span>
                </div>
                <div class="progress mx-auto mb-2" style="max-width:320px; height:8px; border-radius:6px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width:0%;"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="badge bg-danger-subtle text-danger fs-12 px-3 py-2 mb-3">
                    <i class="ti ti-alert-triangle me-1"></i> Needs Attention
                </span>
                <div class="row g-3 text-start border-top pt-3">
                    <div class="col-6">
                        <p class="text-muted fs-12 mb-0 text-uppercase fw-medium">Profit Margin</p>
                        <p class="fs-15 fw-semibold mb-0 text-success">0.00%</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted fs-12 mb-0 text-uppercase fw-medium">Cash Runway</p>
                        <p class="fs-15 fw-semibold mb-0 text-info">0 days</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming Bills --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <h5 class="card-title mb-0"><i class="ti ti-receipt me-2 text-danger"></i>Upcoming Bills</h5>
                <a href="{{ route('cashflow.bills') }}" class="btn btn-sm btn-ghost-secondary ms-auto">
                    View All <i class="ti ti-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Due Date</th>
                                <th>Category</th>
                                <th class="text-end">Amount (RM)</th>
                                <th class="text-center pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="ti ti-receipt-off fs-2 d-block mb-2"></i>
                                    No upcoming bills.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btnQuickTransaction').addEventListener('click', function() {
        Swal.fire({ icon: 'info', title: 'Coming Soon', text: 'Quick record will be available once the API is connected.' });
    });
});
</script>
@endpush
