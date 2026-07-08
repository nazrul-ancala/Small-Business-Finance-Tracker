@extends('master_page.master_page')
@section('page_title', 'Dashboard')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
<link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
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
                        <h4 class="fs-22 fw-semibold mb-0 text-success">RM {{ number_format($incomeThisMonth, 2) }}</h4>
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
                        <h4 class="fs-22 fw-semibold mb-0 text-danger">RM {{ number_format($expenseThisMonth, 2) }}</h4>
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
                        <h4 class="fs-22 fw-semibold mb-0 {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">RM {{ number_format($netProfit, 2) }}</h4>
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
                        <h4 class="fs-22 fw-semibold mb-0 text-warning">{{ $pendingInvoicesCount }}</h4>
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
                            @forelse($recentTransactions as $txn)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($txn->date)->format('d/m/Y') }}</td>
                                <td>{{ $txn->note ?: '—' }}</td>
                                <td>{{ $txn->category }}</td>
                                <td class="text-center">
                                    @if(strtolower($txn->type) === 'income')
                                        <span class="badge bg-success-subtle text-success">Income</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Expense</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3 fw-medium">{{ number_format($txn->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="ti ti-receipt-off fs-2 d-block mb-2"></i>
                                    No transactions recorded yet.
                                </td>
                            </tr>
                            @endforelse
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
                    <button class="btn btn-outline-success text-start" data-bs-toggle="modal" data-bs-target="#modalQuickAddIncome">
                        <i class="ti ti-plus me-2"></i> Add Income
                    </button>
                    <button class="btn btn-outline-danger text-start" data-bs-toggle="modal" data-bs-target="#modalQuickAddExpense">
                        <i class="ti ti-minus me-2"></i> Add Expense
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
                <div class="fs-36 fw-bold lh-1 mb-2
                    @if($score >= 70) text-success
                    @elseif($score >= 40) text-warning
                    @else text-danger
                    @endif">
                    {{ $score }} <span class="fs-18 text-muted fw-normal">/ 100</span>
                </div>
                <div class="progress mx-auto mb-2" style="max-width:320px; height:8px; border-radius:6px;">
                    <div class="progress-bar
                        @if($score >= 70) bg-success
                        @elseif($score >= 40) bg-warning
                        @else bg-danger
                        @endif"
                        role="progressbar" style="width:{{ $score }}%;"
                        aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="badge fs-12 px-3 py-2 mb-3
                    @if($score >= 70) bg-success-subtle text-success
                    @elseif($score >= 40) bg-warning-subtle text-warning
                    @else bg-danger-subtle text-danger
                    @endif">
                    @if($score >= 70) <i class="ti ti-checkbox me-1"></i> Healthy
                    @elseif($score >= 40) <i class="ti ti-alert-circle me-1"></i> Fair
                    @else <i class="ti ti-alert-triangle me-1"></i> Needs Attention
                    @endif
                </span>
                <div class="row g-3 text-start border-top pt-3">
                    <div class="col-6">
                        <p class="text-muted fs-12 mb-0 text-uppercase fw-medium">Profit Margin</p>
                        <p class="fs-15 fw-semibold mb-0 {{ $profitMargin >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($profitMargin, 2) }}%</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted fs-12 mb-0 text-uppercase fw-medium">Cash Runway</p>
                        <p class="fs-15 fw-semibold mb-0 text-info">{{ $cashRunway }} days</p>
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
                            @forelse($upcomingBills as $bill)
                            @php $isOverdue = $bill->status === 'pending' && $bill->expected_date < date('Y-m-d'); @endphp
                            <tr>
                                <td class="ps-3 {{ $isOverdue ? 'text-danger fw-medium' : '' }}">
                                    {{ date('d/m/Y', strtotime($bill->expected_date)) }}
                                </td>
                                <td>{{ $bill->category_name ?: '—' }}</td>
                                <td class="text-end">{{ number_format($bill->amount, 2) }}</td>
                                <td class="text-center pe-3">
                                    @if($isOverdue)
                                        <span class="badge bg-danger-subtle text-danger">Overdue</span>
                                    @elseif($bill->status === 'realised')
                                        <span class="badge bg-success-subtle text-success">Realised</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="ti ti-receipt-off fs-2 d-block mb-2"></i>
                                    No upcoming bills.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================
     QUICK ADD INCOME MODAL
============================================================ --}}
<div class="modal fade" id="modalQuickAddIncome" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-arrow-up-circle-line text-success me-2"></i>Add Income
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formQuickAddIncome">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" name="date"
                                placeholder="Select date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount"
                                placeholder="0.00" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" required>
                                <option value="" disabled selected>Select category…</option>
                                @foreach($incomeCats as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Note</label>
                            <input type="text" class="form-control" name="note" placeholder="e.g. Invoice #001 payment">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btnQuickSaveIncome">
                    <i class="ri-save-line me-1"></i> Save Income
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     QUICK ADD EXPENSE MODAL
============================================================ --}}
<div class="modal fade" id="modalQuickAddExpense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-arrow-down-circle-line text-danger me-2"></i>Add Expense
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formQuickAddExpense">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" name="date"
                                placeholder="Select date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount"
                                placeholder="0.00" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" required>
                                <option value="" disabled selected>Select category…</option>
                                @foreach($expenseCats as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Note</label>
                            <input type="text" class="form-control" name="note" placeholder="e.g. Office rent June 2026">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="btnQuickSaveExpense">
                    <i class="ri-save-line me-1"></i> Save Expense
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="assets/libs/flatpickr/flatpickr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    flatpickr('.flatpickr-date', {
        dateFormat: 'd/m/Y',
        defaultDate: 'today'
    });

    function postForm(url, formId, type) {
        var form = document.getElementById(formId);
        if (!form.checkValidity()) { form.reportValidity(); return; }

        var data = new FormData(form);
        if (type) data.append('type', type);

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: data
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.Success) {
                Swal.fire({ icon: 'success', title: 'Saved!', text: res.Message, timer: 1500, showConfirmButton: false })
                    .then(function() { location.reload(); });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.Message || 'Something went wrong.' });
            }
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' });
        });
    }

    document.getElementById('btnQuickSaveIncome').addEventListener('click', function() {
        postForm('/transactions/save', 'formQuickAddIncome', 'income');
    });

    document.getElementById('btnQuickSaveExpense').addEventListener('click', function() {
        postForm('/transactions/save', 'formQuickAddExpense', 'expense');
    });

});
</script>
@endpush
