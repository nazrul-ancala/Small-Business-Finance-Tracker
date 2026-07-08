@extends('master_page.master_page')
@section('page_title', 'Categories')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
@endpush

@section('content')

{{-- Page title --}}
<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Categories</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('transactions.all') }}">Transactions</a></li>
        <li class="breadcrumb-item active">Categories</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Categories</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary" id="stat-total">{{ count($categories) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-price-tag-3-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Income Categories</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success" id="stat-income">{{ collect($categories)->where('type','income')->count() }}</h4>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Expense Categories</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger" id="stat-expense">{{ collect($categories)->where('type','expense')->count() }}</h4>
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
                <select class="form-select form-select-sm" id="filterType" style="min-width:150px;">
                    <option value="">All Types</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="col-sm-auto">
                <button class="btn btn-primary btn-sm" id="btnFilter">
                    <i class="ri-search-line me-1"></i> Filter
                </button>
            </div>
            <div class="col-sm-auto ms-auto">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddCategory">
                    <i class="ri-add-line me-1"></i> Add Category
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="categoryTable">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th width="80" class="text-center">Color</th>
                        <th width="80" class="text-center">Icon</th>
                        <th width="90" class="text-center">System</th>
                        <th width="90" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                    @forelse($categories as $i => $cat)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-medium">{{ $cat->name }}</td>
                        <td>
                            @if(strtolower($cat->type) === 'income')
                                <span class="badge bg-success-subtle text-success">Income</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Expense</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="d-inline-block rounded" style="width:30px;height:30px;background:{{ $cat->color }};"></span>
                        </td>
                        <td class="text-center"><i class="{{ $cat->icon }} fs-5"></i></td>
                        <td class="text-center">
                            @if($cat->is_system)
                                <span class="badge bg-warning-subtle text-warning">Yes</span>
                            @else
                                <span class="badge bg-light text-muted">No</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info btn-view-category"
                                data-name="{{ $cat->name }}"
                                data-type="{{ ucfirst($cat->type) }}"
                                data-color="{{ $cat->color }}"
                                data-icon="{{ $cat->icon }}"
                                data-bs-toggle="modal" data-bs-target="#modalViewCategory">
                                <i class="ri-eye-line"></i>
                            </button>
                            @if(!$cat->is_system)
                            <button class="btn btn-sm btn-outline-danger btn-delete-category ms-1"
                                data-id="{{ $cat->id }}" data-name="{{ $cat->name }}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="ri-price-tag-3-line fs-2 d-block mb-2"></i>
                            No categories yet. Click <strong>Add Category</strong> to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- ============================================================
     ADD CATEGORY MODAL
============================================================ --}}
<div class="modal fade" id="modalAddCategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-price-tag-3-line text-primary me-2"></i>Add Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAddCategory">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name"
                                placeholder="e.g. Freelance Income" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" required>
                                <option value="" disabled selected>Select type…</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Color</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color"
                                    name="color" id="categoryColor" value="#4f6ef7" title="Pick a color">
                                <span class="text-muted fs-12">Pick a color for this category</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Icon</label>
                            <div class="input-group">
                                <span class="input-group-text" id="iconPreview">
                                    <i class="ri-price-tag-3-line" id="iconPreviewEl"></i>
                                </span>
                                <input type="text" class="form-control" name="icon" id="iconInput"
                                    placeholder="ri-price-tag-3-line" value="ri-price-tag-3-line">
                            </div>
                            <div class="form-text">Enter a Tabler icon class, e.g. <code>ti-home</code>, <code>ti-car</code>, <code>ti-shopping-cart</code></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveCategory">
                    <i class="ri-save-line me-1"></i> Save Category
                </button>
            </div>
        </div>
    </div>
</div>

{{-- VIEW CATEGORY MODAL --}}
<div class="modal fade" id="modalViewCategory" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-primary-subtle rounded mb-3 text-center">
                    <div class="text-muted fs-12 mb-1">Category</div>
                    <div class="fs-22 fw-bold text-primary" id="viewCatName">—</div>
                    <span class="badge bg-white text-dark mt-1" id="viewCatTypeBadge">—</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-4 text-muted fw-normal">Icon</dt>
                    <dd class="col-8"><i class="fs-5" id="viewCatIconEl"></i> <span id="viewCatIcon" class="text-muted fs-12"></span></dd>
                    <dt class="col-4 text-muted fw-normal">Color</dt>
                    <dd class="col-8">
                        <span class="d-inline-block rounded me-2" id="viewCatColorSwatch"
                            style="width:18px;height:18px;vertical-align:middle;"></span>
                        <span id="viewCatColor" class="text-muted fs-12"></span>
                    </dd>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Live icon preview
        document.getElementById('iconInput').addEventListener('input', function() {
            var val = this.value.trim();
            var el = document.getElementById('iconPreviewEl');
            el.className = 'ti ' + val;
        });

        document.querySelectorAll('.btn-view-category').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var color = this.dataset.color || '#4f6ef7';
                var icon  = this.dataset.icon  || 'ri-price-tag-3-line';
                document.getElementById('viewCatName').textContent        = this.dataset.name || '—';
                document.getElementById('viewCatTypeBadge').textContent   = this.dataset.type || '—';
                document.getElementById('viewCatIconEl').className        = 'fs-5 ' + icon;
                document.getElementById('viewCatIcon').textContent        = icon;
                document.getElementById('viewCatColorSwatch').style.backgroundColor = color;
                document.getElementById('viewCatColor').textContent       = color;
            });
        });

        document.getElementById('btnSaveCategory').addEventListener('click', function() {
            var form = document.getElementById('formAddCategory');
            if (!form.checkValidity()) { form.reportValidity(); return; }

            fetch('/transactions/categories/save', {
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

        document.querySelectorAll('.btn-delete-category').forEach(function(btn) {
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
                    fetch('/transactions/categories/delete', {
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
            var type = document.getElementById('filterType').value;
            var url  = '/transactions/categories';
            if (type) url += '?type=' + encodeURIComponent(type);
            window.location.href = url;
        });

    });
</script>
@endpush
