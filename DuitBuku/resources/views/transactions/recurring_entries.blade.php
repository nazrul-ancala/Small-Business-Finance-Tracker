@extends('master_page.master_page')
@section('page_title', 'Recurring Entries')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
<link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
@endpush

@section('content')

{{-- Page title --}}
<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Recurring Entries</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('transactions.all') }}">Transactions</a></li>
        <li class="breadcrumb-item active">Recurring Entries</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Active Entries</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary" id="stat-active">{{ collect($recurring)->where('status','active')->count() }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-repeat-2-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Monthly Income</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success" id="stat-income">RM {{ number_format(collect($recurring)->where('type','income')->sum('amount'), 2) }}</h4>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Monthly Expense</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger" id="stat-expense">RM {{ number_format(collect($recurring)->where('type','expense')->sum('amount'), 2) }}</h4>
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
</div>

{{-- Filter + Table card --}}
<div class="card">
    <div class="card-header">
        <div class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Type</label>
                <select class="form-select form-select-sm" id="filterType" style="min-width:130px;">
                    <option value="">All Types</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Frequency</label>
                <select class="form-select form-select-sm" id="filterFrequency" style="min-width:140px;">
                    <option value="">All Frequencies</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="col-sm-auto">
                <button class="btn btn-primary btn-sm" id="btnFilter">
                    <i class="ri-search-line me-1"></i> Filter
                </button>
            </div>
            <div class="col-sm-auto ms-auto">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddRecurring">
                    <i class="ri-add-line me-1"></i> Add Recurring Entry
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="recurringTable">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th class="text-end">Amount (RM)</th>
                        <th>Frequency</th>
                        <th>Next Date</th>
                        <th class="text-center">Status</th>
                        <th width="160" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="recurringTableBody">
                    @forelse($recurring as $i => $entry)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $entry->description }}</td>
                        <td>
                            @if(strtolower($entry->type) === 'income')
                                <span class="badge bg-success-subtle text-success">Income</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Expense</span>
                            @endif
                        </td>
                        <td>{{ $entry->category }}</td>
                        <td class="text-end fw-medium">{{ number_format($entry->amount, 2) }}</td>
                        <td>{{ ucfirst($entry->frequency) }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($entry->next_date)->format('d/m/Y') }}
                            @if($entry->status === 'active' && (\Carbon\Carbon::parse($entry->next_date)->isPast() || \Carbon\Carbon::parse($entry->next_date)->isToday()))
                                <span class="badge bg-warning-subtle text-warning ms-1">Due</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($entry->status === 'active')
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info btn-view-recurring"
                                data-desc="{{ $entry->description }}"
                                data-type="{{ ucfirst($entry->type) }}"
                                data-category="{{ $entry->category }}"
                                data-amount="{{ number_format($entry->amount, 2) }}"
                                data-frequency="{{ ucfirst($entry->frequency) }}"
                                data-next="{{ \Carbon\Carbon::parse($entry->next_date)->format('d/m/Y') }}"
                                data-status="{{ ucfirst($entry->status) }}"
                                data-bs-toggle="modal" data-bs-target="#modalViewRecurring"
                                title="View">
                                <i class="ri-eye-line"></i>
                            </button>
                            @if($entry->status === 'active')
                            <button class="btn btn-sm btn-outline-success btn-apply-recurring ms-1"
                                data-id="{{ $entry->id }}"
                                data-desc="{{ $entry->description }}"
                                data-amount="{{ number_format($entry->amount, 2) }}"
                                title="Apply now — creates a transaction for today">
                                <i class="ri-play-circle-line"></i>
                            </button>
                            @endif
                            <button class="btn btn-sm btn-outline-danger btn-delete-recurring ms-1"
                                data-id="{{ $entry->id }}"
                                data-desc="{{ $entry->description }}"
                                title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="ri-stop-circle-line fs-2 d-block mb-2"></i>
                            No recurring entries yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- ============================================================
     ADD RECURRING ENTRY MODAL
============================================================ --}}
<div class="modal fade" id="modalAddRecurring" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-repeat-2-line text-primary me-2"></i>Add Recurring Entry
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAddRecurring">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="description"
                                placeholder="e.g. Monthly office rent" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="recurringType" required>
                                <option value="" disabled selected>Select type…</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" name="category" id="recurringCategory" required>
                                <option value="" disabled selected>Select type first…</option>
                                <optgroup label="Income" id="recurCatIncome" style="display:none">
                                    @foreach($incomeCats as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Expense" id="recurCatExpense" style="display:none">
                                    @foreach($expenseCats as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount"
                                placeholder="0.00" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Frequency <span class="text-danger">*</span></label>
                            <select class="form-select" name="frequency" required>
                                <option value="" disabled selected>Select frequency…</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" name="start_date"
                                placeholder="Select start date" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveRecurring">
                    <i class="ri-save-line me-1"></i> Save Entry
                </button>
            </div>
        </div>
    </div>
</div>

{{-- VIEW RECURRING ENTRY MODAL --}}
<div class="modal fade" id="modalViewRecurring" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Recurring Entry Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 rounded mb-3 text-center" id="viewRecBanner">
                    <div class="text-muted fs-12 mb-1">Amount</div>
                    <div class="fs-22 fw-bold" id="viewRecAmount">RM —</div>
                    <span class="badge bg-white text-dark mt-1" id="viewRecFreqBadge">—</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-4 text-muted fw-normal">Description</dt>
                    <dd class="col-8 fw-medium" id="viewRecDesc">—</dd>
                    <dt class="col-4 text-muted fw-normal">Type</dt>
                    <dd class="col-8 fw-medium" id="viewRecType">—</dd>
                    <dt class="col-4 text-muted fw-normal">Category</dt>
                    <dd class="col-8 fw-medium" id="viewRecCategory">—</dd>
                    <dt class="col-4 text-muted fw-normal">Next Date</dt>
                    <dd class="col-8 fw-medium" id="viewRecNext">—</dd>
                    <dt class="col-4 text-muted fw-normal">Status</dt>
                    <dd class="col-8" id="viewRecStatus">—</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
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

        // Filter category optgroups when type changes
        document.getElementById('recurringType').addEventListener('change', function() {
            var type = this.value;
            var catSel = document.getElementById('recurringCategory');
            catSel.value = '';
            document.getElementById('recurCatIncome').style.display  = type === 'income'  ? '' : 'none';
            document.getElementById('recurCatExpense').style.display = type === 'expense' ? '' : 'none';
        });

        document.querySelectorAll('.btn-view-recurring').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var type = (this.dataset.type || '').toLowerCase();
                var banner = document.getElementById('viewRecBanner');
                var amountEl = document.getElementById('viewRecAmount');
                banner.className = 'p-3 rounded mb-3 text-center ' + (type === 'income' ? 'bg-success-subtle' : 'bg-danger-subtle');
                amountEl.className = 'fs-22 fw-bold ' + (type === 'income' ? 'text-success' : 'text-danger');
                amountEl.textContent = 'RM ' + (this.dataset.amount || '0.00');
                document.getElementById('viewRecFreqBadge').textContent  = this.dataset.frequency || '—';
                document.getElementById('viewRecDesc').textContent       = this.dataset.desc      || '—';
                document.getElementById('viewRecType').textContent       = this.dataset.type      || '—';
                document.getElementById('viewRecCategory').textContent   = this.dataset.category  || '—';
                document.getElementById('viewRecNext').textContent       = this.dataset.next      || '—';
                document.getElementById('viewRecStatus').textContent     = this.dataset.status    || '—';
            });
        });

        document.getElementById('btnSaveRecurring').addEventListener('click', function() {
            var form = document.getElementById('formAddRecurring');
            if (!form.checkValidity()) { form.reportValidity(); return; }

            fetch('/transactions/recurring/save', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: new FormData(form)
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
            .catch(function() { Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' }); });
        });

        document.querySelectorAll('.btn-apply-recurring').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id     = this.dataset.id;
                var desc   = this.dataset.desc;
                var amount = this.dataset.amount;
                Swal.fire({
                    title: 'Apply recurring entry?',
                    html: '<b>' + desc + '</b><br>RM ' + amount + ' will be added to today\'s transactions.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Apply'
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    var fd = new FormData();
                    fd.append('id', id);
                    fetch('/transactions/recurring/apply', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: fd
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        if (res.Success) {
                            Swal.fire({ icon: 'success', title: 'Applied!', text: res.Message, timer: 1500, showConfirmButton: false })
                                .then(function() { location.reload(); });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.Message });
                        }
                    });
                });
            });
        });

        document.querySelectorAll('.btn-delete-recurring').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id   = this.dataset.id;
                var desc = this.dataset.desc;
                Swal.fire({
                    title: 'Delete "' + desc + '"?',
                    text: 'This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    var fd = new FormData();
                    fd.append('id', id);
                    fetch('/transactions/recurring/delete', {
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

        document.getElementById('btnFilter').addEventListener('click', function() {
            var type      = document.getElementById('filterType').value;
            var frequency = document.getElementById('filterFrequency').value;
            var url       = '/transactions/recurring?';
            if (type)      url += 'type='      + encodeURIComponent(type)      + '&';
            if (frequency) url += 'frequency=' + encodeURIComponent(frequency) + '&';
            window.location.href = url;
        });

    });
</script>
@endpush
