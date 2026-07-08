@extends('master_page.master_page')
@section('page_title', 'All Invoices')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
<link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
@endpush

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">All Invoices</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Invoices</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Invoices</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary">{{ $summary['total'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-bill-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Paid</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success">{{ $summary['paid'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="ri-checkbox-circle-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Outstanding (RM)</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-warning">{{ number_format($summary['outstanding'], 2) }}</h4>
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
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Overdue</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger">{{ $summary['overdue'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger-subtle rounded fs-3">
                            <i class="ri-alert-circle-line text-danger"></i>
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
        <form method="GET" action="{{ route('invoices.all') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Status</label>
                <select class="form-select form-select-sm" name="status" style="min-width:150px;">
                    <option value="">All Statuses</option>
                    @foreach(['draft','sent','partial','paid','overdue'] as $s)
                        <option value="{{ $s }}" {{ ($status ?? '') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
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
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNewInvoice">
                    <i class="ri-add-line me-1"></i> New Invoice
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
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th class="text-end">Total (RM)</th>
                        <th class="text-center" width="100">Status</th>
                        <th class="text-center" width="130">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $i => $inv)
                    @php
                        $badgeClass = match($inv->status) {
                            'paid'    => 'success',
                            'sent'    => 'primary',
                            'partial' => 'warning',
                            'overdue' => 'danger',
                            default   => 'secondary',
                        };
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-medium">{{ $inv->invoice_number }}</td>
                        <td>{{ $inv->customer_name }}<br><small class="text-muted">{{ $inv->company_name }}</small></td>
                        <td>{{ date('d/m/Y', strtotime($inv->issue_date)) }}</td>
                        <td>{{ date('d/m/Y', strtotime($inv->due_date)) }}</td>
                        <td class="text-end fw-medium">{{ number_format($inv->grand_total, 2) }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $badgeClass }}-subtle text-{{ $badgeClass }} border border-{{ $badgeClass }}-subtle">
                                {{ ucfirst($inv->status) }}
                            </span>
                        </td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-outline-info btn-view-invoice"
                                data-id="{{ $inv->id }}"
                                data-number="{{ $inv->invoice_number }}"
                                data-customer="{{ $inv->customer_name }}"
                                data-issue="{{ date('d/m/Y', strtotime($inv->issue_date)) }}"
                                data-due="{{ date('d/m/Y', strtotime($inv->due_date)) }}"
                                data-total="{{ number_format($inv->grand_total, 2) }}"
                                data-status="{{ ucfirst($inv->status) }}"
                                data-notes="{{ $inv->notes }}"
                                title="View"
                                data-bs-toggle="modal" data-bs-target="#modalViewInvoice">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning btn-edit-invoice ms-1"
                                data-id="{{ $inv->id }}"
                                data-customer-id="{{ $inv->customer_id }}"
                                data-number="{{ $inv->invoice_number }}"
                                data-issue="{{ date('d/m/Y', strtotime($inv->issue_date)) }}"
                                data-due="{{ date('d/m/Y', strtotime($inv->due_date)) }}"
                                data-status="{{ $inv->status }}"
                                data-notes="{{ $inv->notes }}"
                                title="Edit Status"
                                data-bs-toggle="modal" data-bs-target="#modalEditStatus">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-invoice ms-1"
                                data-id="{{ $inv->id }}"
                                data-number="{{ $inv->invoice_number }}"
                                title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="ri-file-forbid-line fs-2 d-block mb-2"></i>
                            No invoices found. Click <strong>New Invoice</strong> to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- VIEW INVOICE MODAL --}}
<div class="modal fade" id="modalViewInvoice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Invoice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-primary-subtle rounded mb-3 text-center">
                    <div class="text-muted fs-12 mb-1">Total</div>
                    <div class="fs-22 fw-bold text-primary" id="viewInvTotal">RM —</div>
                    <span class="badge bg-white text-dark mt-1" id="viewInvStatusBadge">—</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-4 text-muted fw-normal">Invoice #</dt>
                    <dd class="col-8 fw-medium" id="viewInvNumber">—</dd>
                    <dt class="col-4 text-muted fw-normal">Customer</dt>
                    <dd class="col-8 fw-medium" id="viewInvCustomer">—</dd>
                    <dt class="col-4 text-muted fw-normal">Issue Date</dt>
                    <dd class="col-8 fw-medium" id="viewInvIssue">—</dd>
                    <dt class="col-4 text-muted fw-normal">Due Date</dt>
                    <dd class="col-8 fw-medium" id="viewInvDue">—</dd>
                    <dt class="col-4 text-muted fw-normal">Notes</dt>
                    <dd class="col-8 text-muted" id="viewInvNotes">—</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- EDIT STATUS MODAL --}}
<div class="modal fade" id="modalEditStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-edit-line me-2 text-warning"></i>Update Invoice Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editInvId">
                <div class="mb-3">
                    <label class="form-label">Invoice # <span class="fw-medium" id="editInvNumber"></span></label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="editInvStatus">
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="btnUpdateStatus">
                    <i class="ri-save-line me-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>

{{-- NEW INVOICE MODAL — XL with 3 tabs --}}
<div class="modal fade" id="modalNewInvoice" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-bill-line text-primary me-2"></i>New Invoice
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body pb-0">
                <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" id="invoiceTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-details-btn" data-bs-toggle="tab"
                            data-bs-target="#tab-details" type="button" role="tab">
                            <i class="ri-information-line me-1"></i> 1. Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-items-btn" data-bs-toggle="tab"
                            data-bs-target="#tab-items" type="button" role="tab">
                            <i class="ri-list-check me-1"></i> 2. Line Items
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-summary-btn" data-bs-toggle="tab"
                            data-bs-target="#tab-summary" type="button" role="tab">
                            <i class="ri-receipt-line me-1"></i> 3. Summary
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="invoiceTabContent">

                    {{-- TAB 1 --}}
                    <div class="tab-pane fade show active" id="tab-details" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Customer <span class="text-danger">*</span></label>
                                <select class="form-select" id="newInvCustomer" required>
                                    <option value="" disabled selected>Select customer…</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}@if($c->company_name) — {{ $c->company_name }}@endif</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Invoice Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="newInvNumber" value="INV-{{ str_pad(count($invoices)+1, 4, '0', STR_PAD_LEFT) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Issue Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control flatpickr-new" id="newInvIssue" placeholder="Select date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Due Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control flatpickr-new" id="newInvDue" placeholder="Select date" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary" id="btnNextToItems">
                                Next <i class="ri-arrow-right-line ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- TAB 2 --}}
                    <div class="tab-pane fade" id="tab-items" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="mb-0 text-muted">Add items to this invoice</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddItem">
                                <i class="ri-add-line me-1"></i> Add Item
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th width="100" class="text-center">Qty</th>
                                        <th width="160" class="text-end">Unit Price (RM)</th>
                                        <th width="150" class="text-end">Total (RM)</th>
                                        <th width="46" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="lineItemsBody">
                                    <tr class="line-item-row">
                                        <td><input type="text" class="form-control form-control-sm item-desc" placeholder="Item description"></td>
                                        <td><input type="number" class="form-control form-control-sm item-qty text-center" value="1" min="1" step="1"></td>
                                        <td><input type="number" class="form-control form-control-sm item-price text-end" value="0.00" min="0" step="0.01"></td>
                                        <td class="text-end fw-medium"><span class="item-total">0.00</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-ghost-danger remove-item-btn">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-light" id="btnBackToDetails">
                                <i class="ri-arrow-left-line me-1"></i> Back
                            </button>
                            <button class="btn btn-primary" id="btnNextToSummary">
                                Next <i class="ri-arrow-right-line ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- TAB 3 --}}
                    <div class="tab-pane fade" id="tab-summary" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted">Subtotal</td>
                                        <td class="text-end fw-medium" id="summarySubtotal">RM 0.00</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted">Tax</span>
                                                <div class="input-group input-group-sm" style="width:90px;">
                                                    <input type="number" class="form-control form-control-sm text-end"
                                                        id="taxPercent" value="0" min="0" max="100" step="0.5">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end" id="summaryTax">RM 0.00</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted">Discount</span>
                                                <input type="number" class="form-control form-control-sm text-end"
                                                    id="discountAmt" value="0.00" min="0" step="0.01" style="width:90px;">
                                            </div>
                                        </td>
                                        <td class="text-end" id="summaryDiscount">- RM 0.00</td>
                                    </tr>
                                    <tr class="border-top">
                                        <td class="fw-bold fs-15">Grand Total</td>
                                        <td class="text-end fw-bold fs-15 text-primary" id="summaryGrandTotal">RM 0.00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="newInvNotes" rows="3"
                                placeholder="Payment terms, bank details, or any notes for the customer…"></textarea>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-light" id="btnBackToItems">
                                <i class="ri-arrow-left-line me-1"></i> Back
                            </button>
                            <button class="btn btn-primary" id="btnSaveDraft">
                                <i class="ri-save-line me-1"></i> Save as Draft
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer d-none"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="assets/libs/flatpickr/flatpickr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    flatpickr('.flatpickr-new', { dateFormat: 'd/m/Y', defaultDate: 'today' });

    // ---- Line items ----
    function recalcRow(row) {
        var qty   = parseFloat(row.querySelector('.item-qty').value)   || 0;
        var price = parseFloat(row.querySelector('.item-price').value) || 0;
        row.querySelector('.item-total').textContent = (qty * price).toFixed(2);
    }

    function recalcTotals() {
        var subtotal = 0;
        document.querySelectorAll('.line-item-row').forEach(function(row) {
            subtotal += parseFloat(row.querySelector('.item-total').textContent) || 0;
        });
        var taxPct   = parseFloat(document.getElementById('taxPercent').value)  || 0;
        var discount = parseFloat(document.getElementById('discountAmt').value) || 0;
        var tax      = subtotal * (taxPct / 100);
        var grand    = subtotal + tax - discount;
        document.getElementById('summarySubtotal').textContent   = 'RM ' + subtotal.toFixed(2);
        document.getElementById('summaryTax').textContent        = 'RM ' + tax.toFixed(2);
        document.getElementById('summaryDiscount').textContent   = '- RM ' + discount.toFixed(2);
        document.getElementById('summaryGrandTotal').textContent = 'RM ' + grand.toFixed(2);
    }

    function makeItemRowHtml() {
        return '<td><input type="text" class="form-control form-control-sm item-desc" placeholder="Item description"></td>' +
               '<td><input type="number" class="form-control form-control-sm item-qty text-center" value="1" min="1" step="1"></td>' +
               '<td><input type="number" class="form-control form-control-sm item-price text-end" value="0.00" min="0" step="0.01"></td>' +
               '<td class="text-end fw-medium"><span class="item-total">0.00</span></td>' +
               '<td class="text-center"><button type="button" class="btn btn-sm btn-ghost-danger remove-item-btn"><i class="ri-close-line"></i></button></td>';
    }

    function bindRowEvents(row) {
        row.querySelectorAll('.item-qty, .item-price').forEach(function(inp) {
            inp.addEventListener('input', function() { recalcRow(row); recalcTotals(); });
        });
        row.querySelector('.remove-item-btn').addEventListener('click', function() {
            if (document.querySelectorAll('.line-item-row').length > 1) { row.remove(); recalcTotals(); }
        });
    }
    bindRowEvents(document.querySelector('.line-item-row'));

    document.getElementById('btnAddItem').addEventListener('click', function() {
        var row = document.createElement('tr');
        row.className = 'line-item-row';
        row.innerHTML = makeItemRowHtml();
        document.getElementById('lineItemsBody').appendChild(row);
        bindRowEvents(row);
    });

    document.getElementById('taxPercent').addEventListener('input', recalcTotals);
    document.getElementById('discountAmt').addEventListener('input', recalcTotals);

    // ---- Tab nav ----
    document.getElementById('btnNextToItems').addEventListener('click', function() {
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-items-btn')).show();
    });
    document.getElementById('btnBackToDetails').addEventListener('click', function() {
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-details-btn')).show();
    });
    document.getElementById('btnNextToSummary').addEventListener('click', function() {
        recalcTotals();
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-summary-btn')).show();
    });
    document.getElementById('btnBackToItems').addEventListener('click', function() {
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-items-btn')).show();
    });

    // Reset modal on close
    document.getElementById('modalNewInvoice').addEventListener('hidden.bs.modal', function() {
        bootstrap.Tab.getOrCreateInstance(document.getElementById('tab-details-btn')).show();
        var body = document.getElementById('lineItemsBody');
        body.innerHTML = '<tr class="line-item-row">' + makeItemRowHtml() + '</tr>';
        bindRowEvents(body.querySelector('.line-item-row'));
        document.getElementById('taxPercent').value = '0';
        document.getElementById('discountAmt').value = '0.00';
        document.getElementById('newInvNotes').value = '';
        recalcTotals();
    });

    // ---- Save Draft ----
    document.getElementById('btnSaveDraft').addEventListener('click', function() {
        var customerId = document.getElementById('newInvCustomer').value;
        var invNumber  = document.getElementById('newInvNumber').value;
        var issueDate  = document.getElementById('newInvIssue').value;
        var dueDate    = document.getElementById('newInvDue').value;

        if (!customerId || !invNumber || !issueDate || !dueDate) {
            Swal.fire({ icon: 'warning', title: 'Missing Fields', text: 'Please fill in customer, invoice number, issue date, and due date.' });
            return;
        }

        var items = [];
        document.querySelectorAll('.line-item-row').forEach(function(row) {
            var desc  = row.querySelector('.item-desc').value.trim();
            var qty   = parseFloat(row.querySelector('.item-qty').value)   || 0;
            var price = parseFloat(row.querySelector('.item-price').value) || 0;
            var total = parseFloat(row.querySelector('.item-total').textContent) || 0;
            if (desc) items.push({ description: desc, qty: qty, unit_price: price, total: total });
        });

        var subtotalText = document.getElementById('summarySubtotal').textContent.replace('RM ', '');
        var grandText    = document.getElementById('summaryGrandTotal').textContent.replace('RM ', '');
        var subtotal     = parseFloat(subtotalText) || 0;
        var grand        = parseFloat(grandText)    || 0;
        var taxPct       = parseFloat(document.getElementById('taxPercent').value)  || 0;
        var discount     = parseFloat(document.getElementById('discountAmt').value) || 0;

        var fd = new FormData();
        fd.append('customer_id',    customerId);
        fd.append('invoice_number', invNumber);
        fd.append('issue_date',     issueDate);
        fd.append('due_date',       dueDate);
        fd.append('notes',          document.getElementById('newInvNotes').value);
        fd.append('subtotal',       subtotal);
        fd.append('tax_percent',    taxPct);
        fd.append('discount',       discount);
        fd.append('grand_total',    grand);
        fd.append('status',         'draft');
        fd.append('items',          JSON.stringify(items));

        fetch('/invoices/save', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.Success) {
                Swal.fire({ icon: 'success', title: 'Saved!', text: res.Message, timer: 1500, showConfirmButton: false })
                    .then(function() { location.reload(); });
                bootstrap.Modal.getInstance(document.getElementById('modalNewInvoice')).hide();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.Message || 'Failed to save.' });
            }
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Request failed.' });
        });
    });

    // ---- View Invoice ----
    document.querySelectorAll('.btn-view-invoice').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('viewInvTotal').textContent       = 'RM ' + (this.dataset.total    || '0.00');
            document.getElementById('viewInvStatusBadge').textContent = this.dataset.status   || '—';
            document.getElementById('viewInvNumber').textContent      = this.dataset.number   || '—';
            document.getElementById('viewInvCustomer').textContent    = this.dataset.customer || '—';
            document.getElementById('viewInvIssue').textContent       = this.dataset.issue    || '—';
            document.getElementById('viewInvDue').textContent         = this.dataset.due      || '—';
            document.getElementById('viewInvNotes').textContent       = this.dataset.notes    || '—';
        });
    });

    // ---- Edit Status ----
    document.querySelectorAll('.btn-edit-invoice').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('editInvId').value         = this.dataset.id;
            document.getElementById('editInvNumber').textContent = this.dataset.number;
            document.getElementById('editInvStatus').value     = this.dataset.status;
        });
    });

    document.getElementById('btnUpdateStatus').addEventListener('click', function() {
        var id     = document.getElementById('editInvId').value;
        var status = document.getElementById('editInvStatus').value;
        var fd = new FormData();
        fd.append('id', id);
        fd.append('status', status);
        fetch('/invoices/update-status', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: fd
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.Success) {
                Swal.fire({ icon: 'success', title: 'Updated!', timer: 1200, showConfirmButton: false })
                    .then(function() { location.reload(); });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.Message });
            }
        });
    });

    // ---- Delete Invoice ----
    document.querySelectorAll('.btn-delete-invoice').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id     = this.dataset.id;
            var number = this.dataset.number;
            Swal.fire({
                title: 'Delete ' + number + '?',
                text: 'This will also delete all line items. Cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                var fd = new FormData();
                fd.append('id', id);
                fetch('/invoices/delete', {
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
