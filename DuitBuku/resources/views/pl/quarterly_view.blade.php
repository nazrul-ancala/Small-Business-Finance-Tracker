@extends('master_page.master_page')
@section('page_title', 'P&L — Quarterly View')

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">P&L Snapshot — Quarterly View</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('pl.month') }}">P&amp;L Snapshot</a></li>
        <li class="breadcrumb-item active">Quarterly View</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Q Revenue</p>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Q Expenses</p>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Q Net</p>
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

{{-- Filter + Monthly Breakdown --}}
<div class="card">
    <div class="card-header">
        <form method="GET" action="{{ route('pl.quarterly') }}" class="row g-2 align-items-end">
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Year</label>
                <select class="form-select form-select-sm" name="year" style="min-width:100px;">
                    @for ($y = $year - 2; $y <= $year + 1; $y++)
                        <option value="{{ $y }}" {{ $y === $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-sm-auto">
                <label class="form-label mb-1 fs-12 text-muted text-uppercase">Quarter</label>
                <select class="form-select form-select-sm" name="quarter" style="min-width:110px;">
                    <option value="1" {{ $quarter === 1 ? 'selected' : '' }}>Q1 (Jan–Mar)</option>
                    <option value="2" {{ $quarter === 2 ? 'selected' : '' }}>Q2 (Apr–Jun)</option>
                    <option value="3" {{ $quarter === 3 ? 'selected' : '' }}>Q3 (Jul–Sep)</option>
                    <option value="4" {{ $quarter === 4 ? 'selected' : '' }}>Q4 (Oct–Dec)</option>
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
                        <th class="text-end">Gross Margin %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monthlyBreakdown as $row)
                    <tr>
                        <td class="fw-medium">{{ $row['month_label'] }}</td>
                        <td class="text-end text-success">{{ number_format($row['income'], 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($row['expense'], 2) }}</td>
                        <td class="text-end fw-medium {{ $row['net'] >= 0 ? '' : 'text-danger' }}">{{ number_format($row['net'], 2) }}</td>
                        <td class="text-end {{ $row['gross_margin'] === null ? 'text-muted' : '' }}">
                            {{ $row['gross_margin'] === null ? '—' : number_format($row['gross_margin'], 1) . '%' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-semibold">
                    <tr>
                        <td><strong>Quarterly Total</strong></td>
                        <td class="text-end text-success">{{ number_format($totalIncome, 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($totalExpense, 2) }}</td>
                        <td class="text-end {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">{{ number_format($netProfit, 2) }}</td>
                        <td class="text-end text-muted">
                            {{ $totalIncome > 0 ? number_format(($netProfit / $totalIncome) * 100, 1) . '%' : '—' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
