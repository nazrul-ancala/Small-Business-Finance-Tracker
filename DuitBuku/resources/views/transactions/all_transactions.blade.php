@extends('master_page.master_page')
@section('page_title', 'All Transactions')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
<link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
@endpush

@section('content')

{{-- Page title --}}
<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">All Transactions</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Transactions</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Income</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success" id="stat-income">RM {{ number_format(collect($transactions)->where('type','income')->sum('amount'), 2) }}</h4>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Expense</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger" id="stat-expense">RM {{ number_format(collect($transactions)->where('type','expense')->sum('amount'), 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger-subtle rounded fs-3">
                            <i class="ri-arrow-down-circle-line text-danger"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Net Balance</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary" id="stat-balance">RM {{ number_format(collect($transactions)->where('type','income')->sum('amount') - collect($transactions)->where('type','expense')->sum('amount'), 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-wallet-3-line text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Filter + Table card --}}
<div class="card">
    <div class="card-header">
        <div class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Month</label>
                <input type="month" class="form-control form-control-sm" id="filterMonth"
                    value="{{ $month ?? now()->format('Y-m') }}">
            </div>
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Category</label>
                <select class="form-select form-select-sm" id="filterCategory" style="min-width:160px;">
                    <option value="">All Categories</option>
                    @foreach($allCats as $cat)
                        <option value="{{ $cat->name }}" {{ ($category ?? '') === $cat->name ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ ucfirst($cat->type) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-auto">
                <button class="btn btn-primary btn-sm" id="btnFilter">
                    <i class="ri-search-line me-1"></i> Filter
                </button>
            </div>
            <div class="col-sm-auto ms-auto d-flex gap-2">
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddIncome">
                    <i class="ri-add-line me-1"></i> Add Income
                </button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddExpense">
                    <i class="ri-subtract-line me-1"></i> Add Expense
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="txnTable">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Date</th>
                        <th>Note</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th class="text-end">Amount (RM)</th>
                        <th width="130" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="txnTableBody">
                    @forelse($transactions as $i => $txn)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($txn->date)->format('d/m/Y') }}</td>
                        <td>{{ $txn->note ?? '—' }}</td>
                        <td>{{ $txn->category }}</td>
                        <td>
                            @if(strtolower($txn->type) === 'income')
                                <span class="badge bg-success-subtle text-success">Income</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Expense</span>
                            @endif
                        </td>
                        <td class="text-end fw-medium">{{ number_format($txn->amount, 2) }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info btn-view-txn"
                                data-id="{{ $txn->id }}"
                                data-date="{{ \Carbon\Carbon::parse($txn->date)->format('d/m/Y') }}"
                                data-note="{{ $txn->note }}"
                                data-category="{{ $txn->category }}"
                                data-type="{{ ucfirst($txn->type) }}"
                                data-amount="{{ number_format($txn->amount, 2) }}"
                                data-bs-toggle="modal" data-bs-target="#modalViewTransaction"
                                title="View">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning btn-edit-txn ms-1"
                                data-id="{{ $txn->id }}"
                                data-date="{{ \Carbon\Carbon::parse($txn->date)->format('d/m/Y') }}"
                                data-note="{{ $txn->note }}"
                                data-category="{{ $txn->category }}"
                                data-type="{{ strtolower($txn->type) }}"
                                data-amount="{{ $txn->amount }}"
                                data-bs-toggle="modal" data-bs-target="#modalEditTransaction"
                                title="Edit">
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-txn ms-1"
                                data-id="{{ $txn->id }}"
                                data-label="{{ \Carbon\Carbon::parse($txn->date)->format('d/m/Y') }} — {{ $txn->category }}"
                                title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="ri-file-forbid-line fs-2 d-block mb-2"></i>
                            No transactions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- ============================================================
     ADD INCOME MODAL
============================================================ --}}
<div class="modal fade" id="modalAddIncome" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-arrow-up-circle-line text-success me-2"></i>Add Income
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAddIncome">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" id="incomeDate" name="date"
                                placeholder="Select date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" id="incomeAmount"
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
                <button type="button" class="btn btn-success" id="btnSaveIncome">
                    <i class="ri-save-line me-1"></i> Save Income
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     ADD EXPENSE MODAL
============================================================ --}}
<div class="modal fade" id="modalAddExpense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-arrow-down-circle-line text-danger me-2"></i>Add Expense
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAddExpense">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" id="expenseDate" name="date"
                                placeholder="Select date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" id="expenseAmount"
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
                <button type="button" class="btn btn-danger" id="btnSaveExpense">
                    <i class="ri-save-line me-1"></i> Save Expense
                </button>
            </div>
        </div>
    </div>
</div>

{{-- VIEW TRANSACTION MODAL --}}
<div class="modal fade" id="modalViewTransaction" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 rounded mb-3 text-center" id="viewTxnBanner">
                    <div class="text-muted fs-12 mb-1">Amount</div>
                    <div class="fs-22 fw-bold" id="viewTxnAmount">RM —</div>
                    <span class="badge bg-white text-dark mt-1" id="viewTxnTypeBadge">—</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-4 text-muted fw-normal">Date</dt>
                    <dd class="col-8 fw-medium" id="viewTxnDate">—</dd>
                    <dt class="col-4 text-muted fw-normal">Category</dt>
                    <dd class="col-8 fw-medium" id="viewTxnCategory">—</dd>
                    <dt class="col-4 text-muted fw-normal">Note</dt>
                    <dd class="col-8 text-muted" id="viewTxnNote">—</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     EDIT TRANSACTION MODAL
============================================================ --}}
<div class="modal fade" id="modalEditTransaction" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-pencil-line me-2"></i>Edit Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditTransaction">
                    @csrf
                    <input type="hidden" name="id" id="editTxnId">
                    <input type="hidden" name="type" id="editTxnType">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" id="editTxnDate" name="date"
                                placeholder="Select date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" id="editTxnAmount"
                                placeholder="0.00" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" id="editTxnCategory" required>
                                <option value="" disabled>Select category…</option>
                                <optgroup label="Income" id="editCatIncome">
                                    @foreach($incomeCats as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Expense" id="editCatExpense">
                                    @foreach($expenseCats as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Note</label>
                            <input type="text" class="form-control" name="note" id="editTxnNote"
                                placeholder="e.g. Invoice #001 payment">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="btnUpdateTransaction">
                    <i class="ri-save-line me-1"></i> Update
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

        // Date pickers
        flatpickr('.flatpickr-date', {
            dateFormat: 'd/m/Y',
            defaultDate: 'today'
        });

        document.querySelectorAll('.btn-view-txn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var type = this.dataset.type || '—';
                var isIncome = type.toLowerCase() === 'income';
                var banner = document.getElementById('viewTxnBanner');
                var amountEl = document.getElementById('viewTxnAmount');
                banner.className = 'p-3 rounded mb-3 text-center ' + (isIncome ? 'bg-success-subtle' : 'bg-danger-subtle');
                amountEl.className = 'fs-22 fw-bold ' + (isIncome ? 'text-success' : 'text-danger');
                amountEl.textContent = 'RM ' + (this.dataset.amount || '0.00');
                document.getElementById('viewTxnTypeBadge').textContent = type;
                document.getElementById('viewTxnDate').textContent     = this.dataset.date     || '—';
                document.getElementById('viewTxnCategory').textContent = this.dataset.category || '—';
                document.getElementById('viewTxnNote').textContent     = this.dataset.note     || '—';
            });
        });

        // Edit — populate modal fields
        document.querySelectorAll('.btn-edit-txn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var type = this.dataset.type || 'income';
                document.getElementById('editTxnId').value     = this.dataset.id;
                document.getElementById('editTxnType').value   = type;
                document.getElementById('editTxnAmount').value = this.dataset.amount;
                document.getElementById('editTxnNote').value   = this.dataset.note || '';

                document.getElementById('editCatIncome').style.display  = type === 'income'  ? '' : 'none';
                document.getElementById('editCatExpense').style.display = type === 'expense' ? '' : 'none';
                document.getElementById('editTxnCategory').value = this.dataset.category || '';

                var fp = document.getElementById('editTxnDate')._flatpickr;
                if (fp) fp.setDate(this.dataset.date, true, 'd/m/Y');
                else document.getElementById('editTxnDate').value = this.dataset.date;
            });
        });

        document.getElementById('btnUpdateTransaction').addEventListener('click', function() {
            var form = document.getElementById('formEditTransaction');
            if (!form.checkValidity()) { form.reportValidity(); return; }
            fetch('/transactions/update', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: new FormData(form)
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.Success) {
                    Swal.fire({ icon: 'success', title: 'Updated!', text: res.Message, timer: 1500, showConfirmButton: false })
                        .then(function() { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.Message || 'Something went wrong.' });
                }
            })
            .catch(function() { Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' }); });
        });

        // Delete
        document.querySelectorAll('.btn-delete-txn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id    = this.dataset.id;
                var label = this.dataset.label;
                Swal.fire({
                    title: 'Delete transaction?',
                    text: label,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    var fd = new FormData();
                    fd.append('id', id);
                    fetch('/transactions/delete', {
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

        document.getElementById('btnSaveIncome').addEventListener('click', function() {
            postForm('/transactions/save', 'formAddIncome', 'income');
        });

        document.getElementById('btnSaveExpense').addEventListener('click', function() {
            postForm('/transactions/save', 'formAddExpense', 'expense');
        });

        document.getElementById('btnFilter').addEventListener('click', function() {
            var month    = document.getElementById('filterMonth').value;
            var category = document.getElementById('filterCategory').value;
            var url      = '/transactions?month=' + month;
            if (category) url += '&category=' + encodeURIComponent(category);
            window.location.href = url;
        });

    });
</script>
@endpush