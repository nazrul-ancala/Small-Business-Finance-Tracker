@extends('master_page.master_page')
@section('page_title', 'Business Health')

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Business Health</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Business Health</li>
    </ol>
</div>

{{-- Section A: Health Score --}}
<div class="card mb-4">
    <div class="card-body text-center py-4">
        <p class="text-uppercase fw-medium text-muted fs-12 mb-1">Overall Business Health Score</p>
        <div class="fs-48 fw-bold
            @if($score >= 70) text-success
            @elseif($score >= 40) text-warning
            @else text-danger
            @endif
            lh-1 mb-2">
            {{ $score }} <span class="fs-22 text-muted fw-normal">/ 100</span>
        </div>
        <span class="badge fs-12 px-3 py-2 mb-3
            @if($score >= 70) bg-success-subtle text-success
            @elseif($score >= 40) bg-warning-subtle text-warning
            @else bg-danger-subtle text-danger
            @endif">
            @if($score >= 70) <i class="ri-checkbox-circle-line me-1"></i> Healthy
            @elseif($score >= 40) <i class="ri-error-warning-line me-1"></i> Fair
            @else <i class="ri-alert-line me-1"></i> Needs Attention
            @endif
        </span>
        <div class="progress mx-auto mb-3" style="max-width:480px; height:10px; border-radius:8px;">
            <div class="progress-bar
                @if($score >= 70) bg-success
                @elseif($score >= 40) bg-warning
                @else bg-danger
                @endif"
                role="progressbar"
                style="width:{{ $score }}%;"
                aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
        <p class="text-muted fs-13 mb-0">Score is calculated from profit margin, expense ratio, and cash runway.</p>
    </div>
</div>

{{-- Section B: 4 Metric Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Profit Margin</p>
                        <h4 class="fs-22 fw-semibold mb-0 {{ $profitMargin >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($profitMargin, 2) }}%</h4>
                        <p class="text-muted fs-12 mb-0 mt-1">Net ÷ Revenue</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                            <i class="ri-percent-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Expense Ratio</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-warning">{{ number_format($expenseRatio, 2) }}%</h4>
                        <p class="text-muted fs-12 mb-0 mt-1">Expenses ÷ Revenue</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                            <i class="ri-scales-3-line text-warning"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Revenue (This Month)</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-primary">RM {{ number_format($revenueThisMonth, 2) }}</h4>
                        <p class="text-muted fs-12 mb-0 mt-1">{{ now()->format('F Y') }}</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-coin-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Cash Runway</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-info">{{ $cashRunway }} days</h4>
                        <p class="text-muted fs-12 mb-0 mt-1">Balance ÷ Daily Expense</p>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle rounded fs-3">
                            <i class="ri-calendar-time-line text-info"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Section C: Income vs Expense + Key Insights --}}
<div class="row">
    {{-- Income vs Expense --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-bar-chart-2-line me-2 text-primary"></i>Income vs Expense</h5>
            </div>
            <div class="card-body">
                @php
                    $maxAmount      = max($totalIncome, $totalExpense, 1);
                    $incomeBarWidth = min(100, ($totalIncome / $maxAmount) * 100);
                    $expenseBarWidth = min(100, ($totalExpense / $maxAmount) * 100);
                @endphp
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-medium fs-13"><i class="ri-trending-up-line text-success me-1"></i>Total Income</span>
                        <span class="text-success fw-semibold">RM {{ number_format($totalIncome, 2) }}</span>
                    </div>
                    <div class="progress" style="height:10px; border-radius:6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width:{{ $incomeBarWidth }}%;" aria-valuenow="{{ $incomeBarWidth }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-medium fs-13"><i class="ri-trending-down-line text-danger me-1"></i>Total Expense</span>
                        <span class="text-danger fw-semibold">RM {{ number_format($totalExpense, 2) }}</span>
                    </div>
                    <div class="progress" style="height:10px; border-radius:6px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width:{{ $expenseBarWidth }}%;" aria-valuenow="{{ $expenseBarWidth }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="fw-medium fs-13 text-muted">Net</span>
                    <span class="fw-bold fs-15 {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">RM {{ number_format($netProfit, 2) }}</span>
                </div>
                <p class="text-muted fs-12 mt-3 mb-0"><i class="ri-information-line me-1"></i>Based on all-time transactions.</p>
            </div>
        </div>
    </div>

    {{-- Key Insights --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-lightbulb-line me-2 text-warning"></i>Key Insights</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($insights as $i => $insight)
                    <li class="d-flex gap-3 {{ $i < count($insights) - 1 ? 'mb-3' : '' }}">
                        <span class="flex-shrink-0 mt-1"><i class="{{ $insight['icon'] }} fs-5 text-{{ $insight['color'] }}"></i></span>
                        <div>
                            <p class="fw-medium mb-0 fs-14">{{ $insight['title'] }}</p>
                            <p class="text-muted fs-13 mb-0">{{ $insight['text'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
