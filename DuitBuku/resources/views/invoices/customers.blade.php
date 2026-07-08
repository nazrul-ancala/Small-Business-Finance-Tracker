@extends('master_page.master_page')
@section('page_title', 'Customers')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
@endpush

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Customers</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invoices.all') }}">Invoices</a></li>
        <li class="breadcrumb-item active">Customers</li>
    </ol>
</div>

{{-- Summary card --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Customers</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary">{{ count($customers) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-group-line text-primary"></i>
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
        <form method="GET" action="{{ route('invoices.customers') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto flex-grow-1">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Search</label>
                <input type="text" class="form-control form-control-sm" name="search"
                    value="{{ $search ?? '' }}" placeholder="Search by name or company…" style="max-width:300px;">
            </div>
            <div class="col-sm-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="ri-search-line me-1"></i> Search
                </button>
            </div>
            <div class="col-sm-auto ms-auto">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddCustomer">
                    <i class="ri-add-line me-1"></i> Add Customer
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
                        <th>Name</th>
                        <th>Company</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-center" width="130" style="white-space:nowrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $i => $c)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-medium">{{ $c->name }}</td>
                        <td>{{ $c->company_name ?: '—' }}</td>
                        <td>{{ $c->email ?: '—' }}</td>
                        <td>{{ $c->phone ?: '—' }}</td>
                        <td class="text-center" style="white-space:nowrap">
                            <button class="btn btn-sm btn-outline-info btn-view-customer"
                                data-id="{{ $c->id }}"
                                data-name="{{ $c->name }}"
                                data-company="{{ $c->company_name }}"
                                data-email="{{ $c->email }}"
                                data-phone="{{ $c->phone }}"
                                data-address="{{ $c->address }}"
                                title="View"
                                data-bs-toggle="modal" data-bs-target="#modalViewCustomer">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning btn-edit-customer ms-1"
                                data-id="{{ $c->id }}"
                                data-name="{{ $c->name }}"
                                data-company="{{ $c->company_name }}"
                                data-email="{{ $c->email }}"
                                data-phone="{{ $c->phone }}"
                                data-address="{{ $c->address }}"
                                title="Edit"
                                data-bs-toggle="modal" data-bs-target="#modalEditCustomer">
                                <i class="ri-edit-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete-customer ms-1"
                                data-id="{{ $c->id }}"
                                data-name="{{ $c->name }}"
                                title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="ri-user-unfollow-line fs-2 d-block mb-2"></i>
                            No customers yet. Click <strong>Add Customer</strong> to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- VIEW CUSTOMER MODAL --}}
<div class="modal fade" id="modalViewCustomer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-primary-subtle rounded mb-3 text-center">
                    <div class="text-muted fs-12 mb-1">Customer</div>
                    <div class="fs-22 fw-bold text-primary" id="viewCustName">—</div>
                    <span class="badge bg-white text-dark mt-1" id="viewCustCompanyBadge">—</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-4 text-muted fw-normal">Email</dt>
                    <dd class="col-8 fw-medium" id="viewCustEmail">—</dd>
                    <dt class="col-4 text-muted fw-normal">Phone</dt>
                    <dd class="col-8 fw-medium" id="viewCustPhone">—</dd>
                    <dt class="col-4 text-muted fw-normal">Address</dt>
                    <dd class="col-8 text-muted" id="viewCustAddress">—</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ADD CUSTOMER MODAL --}}
<div class="modal fade" id="modalAddCustomer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-user-add-line text-primary me-2"></i>Add Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAddCustomer">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="Full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" name="company_name" placeholder="Company name (optional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+60 12-345 6789">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2" placeholder="Street, City, Postcode"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveCustomer">
                    <i class="ri-save-line me-1"></i> Save Customer
                </button>
            </div>
        </div>
    </div>
</div>

{{-- EDIT CUSTOMER MODAL --}}
<div class="modal fade" id="modalEditCustomer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-edit-line text-warning me-2"></i>Edit Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditCustomer">
                    @csrf
                    <input type="hidden" id="editCustId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editCustName" placeholder="Full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" class="form-control" id="editCustCompany" placeholder="Company name (optional)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editCustEmail" placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editCustPhone" placeholder="+60 12-345 6789">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="editCustAddress" rows="2" placeholder="Street, City, Postcode"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="btnUpdateCustomer">
                    <i class="ri-save-line me-1"></i> Update Customer
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ---- View ----
        document.querySelectorAll('.btn-view-customer').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('viewCustName').textContent         = this.dataset.name    || '—';
                document.getElementById('viewCustCompanyBadge').textContent = this.dataset.company || '—';
                document.getElementById('viewCustEmail').textContent        = this.dataset.email   || '—';
                document.getElementById('viewCustPhone').textContent        = this.dataset.phone   || '—';
                document.getElementById('viewCustAddress').textContent      = this.dataset.address || '—';
            });
        });

        // ---- Edit pre-fill ----
        document.querySelectorAll('.btn-edit-customer').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('editCustId').value      = this.dataset.id;
                document.getElementById('editCustName').value    = this.dataset.name    || '';
                document.getElementById('editCustCompany').value = this.dataset.company || '';
                document.getElementById('editCustEmail').value   = this.dataset.email   || '';
                document.getElementById('editCustPhone').value   = this.dataset.phone   || '';
                document.getElementById('editCustAddress').value = this.dataset.address || '';
            });
        });

        // ---- Save ----
        document.getElementById('btnSaveCustomer').addEventListener('click', function() {
            var form = document.getElementById('formAddCustomer');
            if (!form.checkValidity()) { form.reportValidity(); return; }
            var fd = new FormData(form);
            fetch('/invoices/customers/save', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: fd
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.Success) {
                    Swal.fire({ icon: 'success', title: 'Saved!', timer: 1200, showConfirmButton: false })
                        .then(function() { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.Message });
                }
            });
        });

        // ---- Update ----
        document.getElementById('btnUpdateCustomer').addEventListener('click', function() {
            var form = document.getElementById('formEditCustomer');
            if (!form.checkValidity()) { form.reportValidity(); return; }
            var fd = new FormData();
            fd.append('id',           document.getElementById('editCustId').value);
            fd.append('name',         document.getElementById('editCustName').value);
            fd.append('company_name', document.getElementById('editCustCompany').value);
            fd.append('email',        document.getElementById('editCustEmail').value);
            fd.append('phone',        document.getElementById('editCustPhone').value);
            fd.append('address',      document.getElementById('editCustAddress').value);
            fetch('/invoices/customers/update', {
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

        // ---- Delete ----
        document.querySelectorAll('.btn-delete-customer').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id   = this.dataset.id;
                var name = this.dataset.name;
                Swal.fire({
                    title: 'Delete "' + name + '"?',
                    text: 'This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Delete'
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    var fd = new FormData();
                    fd.append('id', id);
                    fetch('/invoices/customers/delete', {
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
