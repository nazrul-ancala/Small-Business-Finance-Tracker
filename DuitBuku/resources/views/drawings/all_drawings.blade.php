@extends('master_page.master_page')
@section('page_title', 'Owner Drawings')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
<link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
@endpush

@section('content')

{{-- Page title --}}
<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Owner Drawings</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Owner Drawings</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Drawn (Filtered)</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger">RM {{ number_format($summary['total'], 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger-subtle rounded fs-3">
                            <i class="ri-money-dollar-circle-line text-danger"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">This Month</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-warning">RM {{ number_format($summary['this_month'], 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                            <i class="ri-calendar-line text-warning"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">No. of Drawings</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary">{{ $summary['count'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-list-ordered text-primary"></i>
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
        <form method="GET" action="{{ route('drawings.all') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Month</label>
                <input type="month" class="form-control form-control-sm" name="month" value="{{ $month }}">
            </div>
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Type</label>
                <select class="form-select form-select-sm" name="type" style="min-width:160px;">
                    <option value="" {{ $type === '' ? 'selected' : '' }}>All Types</option>
                    <option value="cash"          {{ $type === 'cash'          ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ $type === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="salary"        {{ $type === 'salary'        ? 'selected' : '' }}>Salary</option>
                    <option value="goods"         {{ $type === 'goods'         ? 'selected' : '' }}>Goods / In-Kind</option>
                </select>
            </div>
            <div class="col-sm-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="ri-search-line me-1"></i> Filter
                </button>
            </div>
            <div class="col-sm-auto ms-auto">
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalRecordDrawing">
                    <i class="ri-add-line me-1"></i> Record Drawing
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
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th class="text-end">Amount (RM)</th>
                        <th>Notes</th>
                        <th class="text-center" width="90" style="white-space:nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $typeBadge = [
                            'salary'        => 'bg-primary-subtle text-primary',
                            'cash'          => 'bg-danger-subtle text-danger',
                            'bank_transfer' => 'bg-warning-subtle text-warning',
                            'goods'         => 'bg-success-subtle text-success',
                        ];
                        $typeLabel = [
                            'salary'        => 'Salary',
                            'cash'          => 'Cash',
                            'bank_transfer' => 'Bank Transfer',
                            'goods'         => 'Goods / In-Kind',
                        ];
                    @endphp
                    @forelse($drawings as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ date('d/m/Y', strtotime($d->drawn_at)) }}</td>
                        <td class="fw-medium">{{ $d->description }}</td>
                        <td>
                            <span class="badge {{ $typeBadge[$d->type] ?? 'bg-secondary-subtle text-secondary' }}">
                                {{ $typeLabel[$d->type] ?? $d->type }}
                            </span>
                        </td>
                        <td class="text-end fw-medium">{{ number_format($d->amount, 2) }}</td>
                        <td class="text-muted">{{ $d->notes ?: '—' }}</td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-outline-info btn-view-drawing"
                                data-id="{{ $d->id }}"
                                data-date="{{ date('d/m/Y', strtotime($d->drawn_at)) }}"
                                data-desc="{{ $d->description }}"
                                data-type="{{ $typeLabel[$d->type] ?? $d->type }}"
                                data-amount="{{ number_format($d->amount, 2) }}"
                                data-notes="{{ $d->notes }}"
                                title="View"
                                data-bs-toggle="modal" data-bs-target="#modalViewDrawing">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-drawing ms-1"
                                data-id="{{ $d->id }}"
                                data-desc="{{ $d->description }}"
                                title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="ri-user-unfollow-line fs-2 d-block mb-2"></i>
                            No drawings recorded yet. Click <strong>Record Drawing</strong> to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- VIEW DRAWING MODAL --}}
<div class="modal fade" id="modalViewDrawing" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Drawing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-danger-subtle rounded mb-3 text-center">
                    <div class="text-muted fs-12 mb-1">Amount</div>
                    <div class="fs-22 fw-bold text-danger" id="viewAmount">RM —</div>
                    <span class="badge fs-12 bg-white text-dark mt-1" id="viewTypeBadge">—</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-4 text-muted fw-normal">Date</dt>
                    <dd class="col-8 fw-medium" id="viewDate">—</dd>

                    <dt class="col-4 text-muted fw-normal">Description</dt>
                    <dd class="col-8 fw-medium" id="viewDesc">—</dd>

                    <dt class="col-4 text-muted fw-normal">Type</dt>
                    <dd class="col-8" id="viewType">—</dd>

                    <dt class="col-4 text-muted fw-normal">Notes</dt>
                    <dd class="col-8 text-muted" id="viewNotes">—</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- RECORD DRAWING MODAL --}}
<div class="modal fade" id="modalRecordDrawing" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-money-dollar-circle-line text-danger me-2"></i>Record Drawing
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRecordDrawing">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" name="drawn_at"
                                placeholder="Select date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount"
                                placeholder="0.00" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" required>
                                <option value="" disabled selected>Select type…</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="salary">Salary</option>
                                <option value="goods">Goods / In-Kind</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="description"
                                placeholder="e.g. Monthly salary withdrawal" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <input type="text" class="form-control" name="notes"
                                placeholder="Optional remarks">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="btnSaveDrawing">
                    <i class="ri-save-line me-1"></i> Save Drawing
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

        var typeBadgeClass = {
            'Salary':          'bg-primary-subtle text-primary',
            'Cash':            'bg-danger-subtle text-danger',
            'Bank Transfer':   'bg-warning-subtle text-warning',
            'Goods / In-Kind': 'bg-success-subtle text-success',
        };

        // ---- View ----
        document.querySelectorAll('.btn-view-drawing').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var type = this.dataset.type || '—';
                document.getElementById('viewDate').textContent   = this.dataset.date   || '—';
                document.getElementById('viewDesc').textContent   = this.dataset.desc   || '—';
                document.getElementById('viewType').textContent   = type;
                document.getElementById('viewAmount').textContent = 'RM ' + (this.dataset.amount || '0.00');
                document.getElementById('viewNotes').textContent  = this.dataset.notes  || '—';
                var badge = document.getElementById('viewTypeBadge');
                badge.textContent = type;
                badge.className = 'badge fs-12 ' + (typeBadgeClass[type] || 'bg-secondary-subtle text-secondary');
            });
        });

        // ---- Save ----
        document.getElementById('btnSaveDrawing').addEventListener('click', function() {
            var form = document.getElementById('formRecordDrawing');
            if (!form.checkValidity()) { form.reportValidity(); return; }
            var fd = new FormData(form);
            fetch('/drawings/save', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: fd
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.Success) {
                    Swal.fire({ icon: 'success', title: 'Drawing Saved!', text: res.Message, timer: 1500, showConfirmButton: false })
                        .then(function() { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.Message || 'Failed to save drawing.' });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' });
            });
        });

        // ---- Delete ----
        document.querySelectorAll('.btn-delete-drawing').forEach(function(btn) {
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
                    fetch('/drawings/delete', {
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
