@extends('master_page.master_page')
@section('page_title', 'Expected Income')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
<link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
@endpush

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Expected Income</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cashflow.calendar') }}">Cashflow Forecast</a></li>
        <li class="breadcrumb-item active">Expected Income</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Expected</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success">RM {{ number_format($summary['total'], 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="ri-arrow-up-circle-line text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Realised</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary">RM {{ number_format($summary['realised'], 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-checkbox-circle-line text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Remaining</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-warning">RM {{ number_format($summary['remaining'], 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                            <i class="ri-time-line text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter + Table --}}
<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('cashflow.income') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Month</label>
                <input type="month" class="form-control form-control-sm" name="month" value="{{ $month }}" placeholder="All months">
            </div>
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Status</label>
                <select class="form-select form-select-sm" name="status" style="min-width:140px;">
                    <option value="" {{ $status === '' ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending"  {{ $status === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="realised" {{ $status === 'realised' ? 'selected' : '' }}>Realised</option>
                </select>
            </div>
            <div class="col-sm-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="ri-search-line me-1"></i> Filter
                </button>
            </div>
            <div class="col-sm-auto ms-auto">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddIncome">
                    <i class="ri-add-line me-1"></i> Add Income
                </button>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Expected Date</th>
                        <th>Category</th>
                        <th>Notes</th>
                        <th class="text-end">Amount (RM)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" width="120" style="white-space:nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomes as $i => $inc)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ date('d/m/Y', strtotime($inc->expected_date)) }}</td>
                        <td>{{ $inc->category_name ?: '—' }}</td>
                        <td class="text-muted">{{ $inc->notes ?: '—' }}</td>
                        <td class="text-end fw-medium">{{ number_format($inc->amount, 2) }}</td>
                        <td class="text-center">
                            @if($inc->status === 'realised')
                                <span class="badge bg-success-subtle text-success">Realised</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">Pending</span>
                            @endif
                        </td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-outline-info btn-view-income"
                                data-category="{{ $inc->category_name }}"
                                data-amount="{{ number_format($inc->amount, 2) }}"
                                data-date="{{ date('d/m/Y', strtotime($inc->expected_date)) }}"
                                data-status="{{ ucfirst($inc->status) }}"
                                data-recurring="{{ $inc->is_recurring ? 'Yes' : 'No' }}"
                                data-rule="{{ $inc->recurrence_rule }}"
                                data-notes="{{ $inc->notes }}"
                                title="View"
                                data-bs-toggle="modal" data-bs-target="#modalViewIncome">
                                <i class="ri-eye-line"></i>
                            </button>
                            @if($inc->status === 'pending')
                            <button class="btn btn-sm btn-outline-success btn-realise-income ms-1"
                                data-id="{{ $inc->id }}"
                                title="Mark as Realised">
                                <i class="ri-checkbox-circle-line"></i>
                            </button>
                            @endif
                            <button class="btn btn-sm btn-outline-danger btn-delete-income ms-1"
                                data-id="{{ $inc->id }}"
                                data-notes="{{ $inc->notes ?: $inc->category_name }}"
                                title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="ri-file-damage-line fs-2 d-block mb-2"></i>
                            No expected income. Click <strong>Add Income</strong> to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- VIEW INCOME MODAL --}}
<div class="modal fade" id="modalViewIncome" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Income Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-success-subtle rounded mb-3 text-center">
                    <div class="text-muted fs-12 mb-1">Expected Amount</div>
                    <div class="fs-22 fw-bold text-success" id="viewIncomeAmount">RM —</div>
                    <span class="badge bg-white text-dark mt-1">Income</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-4 text-muted fw-normal">Expected Date</dt>
                    <dd class="col-8 fw-medium" id="viewIncomeDate">—</dd>
                    <dt class="col-4 text-muted fw-normal">Category</dt>
                    <dd class="col-8 fw-medium" id="viewIncomeCategory">—</dd>
                    <dt class="col-4 text-muted fw-normal">Recurring</dt>
                    <dd class="col-8 fw-medium" id="viewIncomeRecurring">—</dd>
                    <dt class="col-4 text-muted fw-normal">Status</dt>
                    <dd class="col-8" id="viewIncomeStatus">—</dd>
                    <dt class="col-4 text-muted fw-normal">Notes</dt>
                    <dd class="col-8 text-muted" id="viewIncomeNotes">—</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ADD INCOME MODAL --}}
<div class="modal fade" id="modalAddIncome" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-arrow-up-circle-line text-success me-2"></i>Add Expected Income</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAddIncome">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" name="category_id" required>
                                <option value="" disabled selected>Select category…</option>
                                @forelse($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @empty
                                    <option disabled>No income categories found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount"
                                placeholder="0.00" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Expected Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" name="expected_date"
                                placeholder="Select date" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_recurring" id="incomeIsRecurring">
                                <label class="form-check-label" for="incomeIsRecurring">Recurring income</label>
                            </div>
                        </div>
                        <div class="col-12" id="incomeRecurrenceRow" style="display:none;">
                            <label class="form-label">Recurrence Rule</label>
                            <select class="form-select" name="recurrence_rule">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <input type="text" class="form-control" name="notes" placeholder="e.g. Client XYZ payment">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btnSaveIncome">
                    <i class="ri-save-line me-1"></i> Save Income
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

    flatpickr('.flatpickr-date', { dateFormat: 'd/m/Y', defaultDate: 'today' });

    document.getElementById('incomeIsRecurring').addEventListener('change', function() {
        document.getElementById('incomeRecurrenceRow').style.display = this.checked ? '' : 'none';
    });

    // ---- View ----
    document.querySelectorAll('.btn-view-income').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('viewIncomeAmount').textContent   = 'RM ' + (this.dataset.amount   || '0.00');
            document.getElementById('viewIncomeDate').textContent     = this.dataset.date     || '—';
            document.getElementById('viewIncomeCategory').textContent = this.dataset.category || '—';
            var rec = this.dataset.recurring === 'Yes' ? 'Yes (' + (this.dataset.rule || '') + ')' : 'No';
            document.getElementById('viewIncomeRecurring').textContent = rec;
            document.getElementById('viewIncomeStatus').textContent   = this.dataset.status   || '—';
            document.getElementById('viewIncomeNotes').textContent    = this.dataset.notes    || '—';
        });
    });

    // ---- Save ----
    document.getElementById('btnSaveIncome').addEventListener('click', function() {
        var form = document.getElementById('formAddIncome');
        if (!form.checkValidity()) { form.reportValidity(); return; }
        var fd = new FormData(form);
        fetch('/cashflow/income/save', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.Success) {
                Swal.fire({ icon: 'success', title: 'Income Saved!', text: res.Message, timer: 1500, showConfirmButton: false })
                    .then(function() { location.reload(); });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.Message || 'Failed to save income.' });
            }
        })
        .catch(function() { Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' }); });
    });

    // ---- Mark as Realised ----
    document.querySelectorAll('.btn-realise-income').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            Swal.fire({
                title: 'Mark as Realised?',
                text: 'This income will be marked as received.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Yes, Realise'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                var fd = new FormData();
                fd.append('id', id);
                fd.append('status', 'realised');
                fetch('/cashflow/income/update-status', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: fd
                })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    if (res.Success) {
                        Swal.fire({ icon: 'success', title: 'Realised!', timer: 1200, showConfirmButton: false })
                            .then(function() { location.reload(); });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.Message });
                    }
                });
            });
        });
    });

    // ---- Delete ----
    document.querySelectorAll('.btn-delete-income').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id    = this.dataset.id;
            var label = this.dataset.notes;
            Swal.fire({
                title: 'Delete "' + label + '"?',
                text: 'This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                var fd = new FormData();
                fd.append('id', id);
                fetch('/cashflow/income/delete', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: fd
                })
                .then(function(r) { return r.json(); })
                .then(function(res) {
                    if (res.Success) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1200, showConfirmButton: false })
                            .then(function() { location.reload(); });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.Message });
                    }
                });
            });
        });
    });

});
</script>
@endpush
