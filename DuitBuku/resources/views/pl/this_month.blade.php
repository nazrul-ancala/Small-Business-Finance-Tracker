@extends('master_page.master_page')
@section('page_title', 'P&L — This Month')

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">P&L Snapshot — This Month</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">P&amp;L Snapshot</li>
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
                        <h4 class="fs-22 fw-semibold mb-0 text-success">RM {{ number_format($totalIncome, 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="ri-trending-up-line text-success"></i>
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
                        <h4 class="fs-22 fw-semibold mb-0 text-danger">RM {{ number_format($totalExpense, 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger-subtle rounded fs-3">
                            <i class="ri-trending-down-line text-danger"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Net Profit</p>
                        <h4 class="fs-22 fw-semibold mb-0 {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">
                            RM {{ number_format($netProfit, 2) }}
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-wallet-line text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter + Category Breakdown --}}
<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('pl.month') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Month</label>
                <input type="month" class="form-control form-control-sm" name="month" value="{{ $month }}">
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
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Category</th>
                        <th class="text-center">Type</th>
                        <th class="text-end">Income (RM)</th>
                        <th class="text-end">Expense (RM)</th>
                        <th class="text-end">Net (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($breakdown as $row)
                    @php $net = (float) $row->income - (float) $row->expense; @endphp
                    <tr>
                        <td class="fw-medium">{{ $row->category }}</td>
                        <td class="text-center">
                            @if($row->type === 'income')
                                <span class="badge bg-success-subtle text-success">Income</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Expense</span>
                            @endif
                        </td>
                        <td class="text-end text-success">{{ number_format($row->income, 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($row->expense, 2) }}</td>
                        <td class="text-end fw-medium {{ $net >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($net, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="ri-bar-chart-2-line fs-2 d-block mb-2"></i>
                            No transactions recorded for this month yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-semibold">
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td class="text-end text-success">{{ number_format($totalIncome, 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($totalExpense, 2) }}</td>
                        <td class="text-end {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($netProfit, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
