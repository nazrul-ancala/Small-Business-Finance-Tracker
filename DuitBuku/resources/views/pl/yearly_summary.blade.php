@extends('master_page.master_page')
@section('page_title', 'P&L — Yearly Summary')

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">P&L Snapshot — Yearly Summary</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pl.month') }}">P&amp;L Snapshot</a></li>
        <li class="breadcrumb-item active">Yearly Summary</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Annual Revenue</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success">RM {{ number_format($totalIncome, 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="ri-coin-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Annual Expenses</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger">RM {{ number_format($totalExpense, 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger-subtle rounded fs-3">
                            <i class="ri-receipt-line text-danger"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Annual Net</p>
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

{{-- Chart placeholder --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="ri-bar-chart-line me-2 text-primary"></i>Revenue vs Expense</h5>
    </div>
    <div class="card-body">
        <div class="text-center py-5 text-muted">
            <i class="ri-bar-chart-line fs-1 d-block mb-2 opacity-25"></i>
            <p class="mb-0">Revenue vs Expense chart will appear here once data is available.</p>
        </div>
    </div>
</div>

{{-- Filter + Month-by-Month --}}
<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('pl.yearly') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Year</label>
                <select class="form-select form-select-sm" name="year" style="min-width:100px;">
                    @for ($y = $year - 2; $y <= $year + 1; $y++)
                        <option value="{{ $y }}" {{ $y === $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
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
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Month</th>
                        <th class="text-end">Income (RM)</th>
                        <th class="text-end">Expense (RM)</th>
                        <th class="text-end">Net (RM)</th>
                        <th class="text-end">Running Balance (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monthlyBreakdown as $row)
                    <tr {{ $row['month_num'] === now()->month && $year === now()->year ? 'class=table-primary' : '' }}>
                        <td class="fw-medium">{{ $row['month_label'] }}</td>
                        <td class="text-end text-success">{{ number_format($row['income'], 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($row['expense'], 2) }}</td>
                        <td class="text-end fw-medium {{ $row['net'] >= 0 ? '' : 'text-danger' }}">{{ number_format($row['net'], 2) }}</td>
                        <td class="text-end fw-medium {{ $row['running_balance'] >= 0 ? '' : 'text-danger' }}">{{ number_format($row['running_balance'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-semibold">
                    <tr>
                        <td><strong>Year Total</strong></td>
                        <td class="text-end text-success">{{ number_format($totalIncome, 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($totalExpense, 2) }}</td>
                        <td class="text-end {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($netProfit, 2) }}</td>
                        <td class="text-end text-muted">—</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
