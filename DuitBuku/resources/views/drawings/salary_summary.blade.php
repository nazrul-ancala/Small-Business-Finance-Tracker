@extends('master_page.master_page')
@section('page_title', 'Salary Summary')

@push('styles')
<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
@endpush

@section('content')

{{-- Page title --}}
<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Salary Summary</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('drawings.all') }}">Owner Drawings</a></li>
        <li class="breadcrumb-item active">Salary Summary</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Total Salary Drawn</p>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Avg Monthly Salary</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary">RM {{ number_format($summary['avg'], 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-line-chart-line text-primary"></i>
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
        <form method="GET" action="{{ route('drawings.salary') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Year</label>
                <select class="form-select form-select-sm" name="year" style="min-width:120px;">
                    <option value="{{ now()->year }}"     {{ (string)$year === (string)now()->year     ? 'selected' : '' }}>{{ now()->year }}</option>
                    <option value="{{ now()->year - 1 }}" {{ (string)$year === (string)(now()->year-1) ? 'selected' : '' }}>{{ now()->year - 1 }}</option>
                    <option value="{{ now()->year - 2 }}" {{ (string)$year === (string)(now()->year-2) ? 'selected' : '' }}>{{ now()->year - 2 }}</option>
                </select>
            </div>
            <div class="col-sm-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="ri-search-line me-1"></i> Filter
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
                        <th>Month</th>
                        <th class="text-center">No. of Drawings</th>
                        <th class="text-end">Total Amount (RM)</th>
                        <th class="text-center" width="90">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlySummary as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-medium">{{ $row->month_label }}</td>
                        <td class="text-center">{{ $row->drawing_count }}</td>
                        <td class="text-end fw-medium">{{ number_format($row->total_amount, 2) }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info btn-view-month"
                                data-month="{{ $row->month_label }}"
                                data-count="{{ $row->drawing_count }}"
                                data-total="{{ number_format($row->total_amount, 2) }}"
                                title="View"
                                data-bs-toggle="modal" data-bs-target="#modalViewMonth">
                                <i class="ri-eye-line"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="ri-money-dollar-box-line fs-2 d-block mb-2"></i>
                            No salary drawings found for {{ $year }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- VIEW MONTH MODAL --}}
<div class="modal fade" id="modalViewMonth" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-eye-line me-2"></i>Monthly Salary Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 bg-danger-subtle rounded mb-3 text-center">
                    <div class="text-muted fs-12 mb-1">Total Salary</div>
                    <div class="fs-22 fw-bold text-danger" id="viewTotal">RM —</div>
                    <span class="badge bg-white text-dark mt-1" id="viewMonth">—</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-5 text-muted fw-normal">Month</dt>
                    <dd class="col-7 fw-medium" id="viewMonthDetail">—</dd>

                    <dt class="col-5 text-muted fw-normal">No. of Drawings</dt>
                    <dd class="col-7 fw-medium" id="viewCount">—</dd>
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
        document.querySelectorAll('.btn-view-month').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var month = this.dataset.month || '—';
                document.getElementById('viewMonth').textContent       = month;
                document.getElementById('viewMonthDetail').textContent = month;
                document.getElementById('viewCount').textContent       = this.dataset.count || '—';
                document.getElementById('viewTotal').textContent       = 'RM ' + (this.dataset.total || '0.00');
            });
        });
    });
</script>
@endpush
