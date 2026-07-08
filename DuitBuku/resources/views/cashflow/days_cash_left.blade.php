@extends('master_page.master_page')
@section('page_title', 'Days Cash Left')

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Days Cash Left</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cashflow.calendar') }}">Cashflow Forecast</a></li>
        <li class="breadcrumb-item active">Days Cash Left</li>
    </ol>
</div>

{{-- Info alert --}}
<div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="ri-information-line fs-5 flex-shrink-0"></i>
    <span><strong>Days of Runway</strong> = Current Balance ÷ Average Daily Expense (last 30 days). Read-only — based on recorded transactions.</span>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    @php
        $runwayColor = $daysRunway < 30 ? 'danger' : ($daysRunway < 90 ? 'warning' : 'primary');
    @endphp
    <div class="col-xl-4 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Days of Runway</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-{{ $runwayColor }}">{{ $daysRunway }} days</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-{{ $runwayColor }}-subtle rounded fs-3">
                            <i class="ri-time-line text-{{ $runwayColor }}"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Current Balance</p>
                        <h4 class="fs-22 fw-semibold mb-0 {{ $currentBalance >= 0 ? 'text-success' : 'text-danger' }}">
                            RM {{ number_format($currentBalance, 2) }}
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="ri-wallet-3-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Avg Daily Expense</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger">
                            RM {{ number_format($avgDailyExpense, 2) }}
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger-subtle rounded fs-3">
                            <i class="ri-bar-chart-line text-danger"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 4-week projection table --}}
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="ri-calendar-line me-2"></i>4-Week Cashflow Projection</h5>
        <p class="text-muted fs-12 mb-0 mt-1">Base = historical weekly average. Adjusted for pending bills & expected income.</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Week</th>
                        <th class="text-end">Projected Income (RM)</th>
                        <th class="text-end">Projected Expense (RM)</th>
                        <th class="text-end">Net (RM)</th>
                        <th class="text-end">Running Balance (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projection as $row)
                    @php $netPos = $row['net'] >= 0; @endphp
                    <tr>
                        <td class="fw-medium">{{ $row['week'] }}</td>
                        <td class="text-end text-success">{{ number_format($row['proj_income'], 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($row['proj_expense'], 2) }}</td>
                        <td class="text-end fw-semibold {{ $netPos ? 'text-success' : 'text-danger' }}">
                            {{ $netPos ? '+' : '' }}{{ number_format($row['net'], 2) }}
                        </td>
                        <td class="text-end fw-bold {{ $row['running_balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                            RM {{ number_format($row['running_balance'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No transaction data available for projection.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
