@extends('master_page.master_page')
@section('page_title', 'Payment Records')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
<link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
@endpush

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Payment Records</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.all') }}">Invoices</a></li>
        <li class="breadcrumb-item active">Payment Records</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Collected</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success">RM {{ number_format($summary['total_collected'], 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="ri-money-dollar-circle-line text-success"></i>
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
                        <h4 class="fs-22 fw-semibold mb-0 text-primary">RM {{ number_format($summary['this_month'], 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-calendar-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Pending Invoices</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-warning">{{ $summary['pending_count'] }}</h4>
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
        <form method="GET" action="{{ route('invoices.payments') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Month</label>
                <input type="month" class="form-control form-control-sm" name="month" value="{{ $month }}">
            </div>
            <div class="col-sm-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="ri-search-line me-1"></i> Filter
                </button>
            </div>
            <div class="col-sm-auto ms-auto">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalRecordPayment">
                    <i class="ri-add-line me-1"></i> Record Payment
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
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th class="text-end">Amount (RM)</th>
                        <th>Method</th>
                        <th>Paid At</th>
                        <th>Notes</th>
                        <th class="text-center" width="100" style="white-space:nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $methodLabels = [
                            'cash'          => 'Cash',
                            'bank_transfer' => 'Bank Transfer',
                            'online'        => 'Online',
                            'cheque'        => 'Cheque',
                        ];
                    @endphp
                    @forelse($payments as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-medium">{{ $p->invoice_number }}</td>
                        <td>{{ $p->customer_name }}</td>
                        <td class="text-end fw-medium">{{ number_format($p->amount, 2) }}</td>
                        <td>{{ $methodLabels[$p->payment_method] ?? $p->payment_method }}</td>
                        <td>{{ date('d/m/Y', strtotime($p->paid_at)) }}</td>
                        <td class="text-muted">{{ $p->notes ?: '—' }}</td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-outline-info btn-view-payment"
                                data-id="{{ $p->id }}"
                                data-invoice="{{ $p->invoice_number }}"
                                data-customer="{{ $p->customer_name }}"
                                data-amount="{{ number_format($p->amount, 2) }}"
                                data-method="{{ $methodLabels[$p->payment_method] ?? $p->payment_method }}"
                                data-paid="{{ date('d/m/Y', strtotime($p->paid_at)) }}"
                                data-notes="{{ $p->notes }}"
                                title="View"
                                data-bs-toggle="modal" data-bs-target="#modalViewPayment">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-payment ms-1"
                                data-id="{{ $p->id }}"
                                data-invoice="{{ $p->invoice_number }}"
                                title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="ri-file-damage-line fs-2 d-block mb-2"></i>
                            No payment records for this month. Click <strong>Record Payment</strong> to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- VIEW PAYMENT MODAL --}}
<div class="modal fade" id="modalViewPayment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-success-subtle rounded mb-3 text-center">
                    <div class="text-muted fs-12 mb-1">Amount Paid</div>
                    <div class="fs-22 fw-bold text-success" id="viewPayAmount">RM —</div>
                    <span class="badge bg-white text-dark mt-1" id="viewPayMethodBadge">—</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-4 text-muted fw-normal">Invoice #</dt>
                    <dd class="col-8 fw-medium" id="viewPayInvoice">—</dd>
                    <dt class="col-4 text-muted fw-normal">Customer</dt>
                    <dd class="col-8 fw-medium" id="viewPayCustomer">—</dd>
                    <dt class="col-4 text-muted fw-normal">Paid At</dt>
                    <dd class="col-8 fw-medium" id="viewPayPaid">—</dd>
                    <dt class="col-4 text-muted fw-normal">Notes</dt>
                    <dd class="col-8 text-muted" id="viewPayNotes">—</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- RECORD PAYMENT MODAL --}}
<div class="modal fade" id="modalRecordPayment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-money-dollar-circle-line text-success me-2"></i>Record Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRecordPayment">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Invoice <span class="text-danger">*</span></label>
                            <select class="form-select" name="invoice_id" required>
                                <option value="" disabled selected>Select invoice…</option>
                                @forelse($invoices as $inv)
                                    <option value="{{ $inv->id }}">{{ $inv->invoice_number }} — {{ $inv->customer_name }} (RM {{ number_format($inv->grand_total - $inv->amount_paid, 2) }} remaining)</option>
                                @empty
                                    <option disabled>No open invoices available</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount"
                                placeholder="0.00" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select" name="payment_method" required>
                                <option value="" disabled selected>Select method…</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="online">Online (DuitNow / FPX)</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Paid At <span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpickr-date" name="paid_at"
                                placeholder="Select payment date" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <input type="text" class="form-control" name="notes"
                                placeholder="e.g. Ref: TXN123456">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="btnSavePayment">
                    <i class="ri-save-line me-1"></i> Save Payment
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

        // ---- View ----
        document.querySelectorAll('.btn-view-payment').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('viewPayAmount').textContent      = 'RM ' + (this.dataset.amount   || '0.00');
                document.getElementById('viewPayMethodBadge').textContent = this.dataset.method   || '—';
                document.getElementById('viewPayInvoice').textContent     = this.dataset.invoice  || '—';
                document.getElementById('viewPayCustomer').textContent    = this.dataset.customer || '—';
                document.getElementById('viewPayPaid').textContent        = this.dataset.paid     || '—';
                document.getElementById('viewPayNotes').textContent       = this.dataset.notes    || '—';
            });
        });

        // ---- Save Payment ----
        document.getElementById('btnSavePayment').addEventListener('click', function() {
            var form = document.getElementById('formRecordPayment');
            if (!form.checkValidity()) { form.reportValidity(); return; }
            var fd = new FormData(form);
            fetch('/invoices/payments/save', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: fd
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.Success) {
                    Swal.fire({ icon: 'success', title: 'Payment Recorded!', text: res.Message, timer: 1500, showConfirmButton: false })
                        .then(function() { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.Message || 'Failed to record payment.' });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' });
            });
        });

        // ---- Delete Payment ----
        document.querySelectorAll('.btn-delete-payment').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id      = this.dataset.id;
                var invoice = this.dataset.invoice;
                Swal.fire({
                    title: 'Delete payment for ' + invoice + '?',
                    text: 'Invoice status will be recalculated. Cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    var fd = new FormData();
                    fd.append('id', id);
                    fetch('/invoices/payments/delete', {
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
