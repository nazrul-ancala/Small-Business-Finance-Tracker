@extends('master_page.master_page')
@section('page_title', 'Cashflow Forecast')

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Cashflow Forecast</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Cashflow Forecast</li>
    </ol>
</div>

{{-- Summary cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Bills Due (Next 30 Days)</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-danger">RM {{ number_format($totalBills, 2) }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-danger-subtle rounded fs-3">
                            <i class="ri-bill-line text-danger"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Income Expected (Next 30 Days)</p>
                        <h4 class="fs-22 fw-semibold mb-0 text-success">RM {{ number_format($totalIncome, 2) }}</h4>
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
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Net Cashflow</p>
                        <h4 class="fs-22 fw-semibold mb-0 {{ $netCashflow >= 0 ? 'text-success' : 'text-danger' }}">
                            RM {{ number_format($netCashflow, 2) }}
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title {{ $netCashflow >= 0 ? 'bg-success-subtle' : 'bg-danger-subtle' }} rounded fs-3">
                            <i class="ri-exchange-dollar-line {{ $netCashflow >= 0 ? 'text-success' : 'text-danger' }}"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1 fs-12">Days of Runway</p>
                        <h4 class="fs-22 fw-semibold mb-0 {{ $daysRunway < 30 ? 'text-danger' : ($daysRunway < 90 ? 'text-warning' : 'text-primary') }}">
                            {{ $daysRunway }} days
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                            <i class="ri-time-line text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick links --}}
<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <a href="{{ route('cashflow.bills') }}" class="text-decoration-none">
            <div class="card card-animate h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <span class="avatar-title bg-danger-subtle rounded fs-1 p-3">
                        <i class="ri-bill-line text-danger"></i>
                    </span>
                    <div>
                        <h5 class="mb-1">Upcoming Bills</h5>
                        <p class="text-muted mb-0 fs-13">Track expected expenses and recurring bills</p>
                    </div>
                    <i class="ri-arrow-right-s-line ms-auto fs-4 text-muted"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-4 col-md-6">
        <a href="{{ route('cashflow.income') }}" class="text-decoration-none">
            <div class="card card-animate h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <span class="avatar-title bg-success-subtle rounded fs-1 p-3">
                        <i class="ri-arrow-up-circle-line text-success"></i>
                    </span>
                    <div>
                        <h5 class="mb-1">Expected Income</h5>
                        <p class="text-muted mb-0 fs-13">Track anticipated income and client payments</p>
                    </div>
                    <i class="ri-arrow-right-s-line ms-auto fs-4 text-muted"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-xl-4 col-md-6">
        <a href="{{ route('cashflow.days') }}" class="text-decoration-none">
            <div class="card card-animate h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <span class="avatar-title bg-primary-subtle rounded fs-1 p-3">
                        <i class="ri-time-line text-primary"></i>
                    </span>
                    <div>
                        <h5 class="mb-1">Days Cash Left</h5>
                        <p class="text-muted mb-0 fs-13">Cash runway and 4-week cashflow projection</p>
                    </div>
                    <i class="ri-arrow-right-s-line ms-auto fs-4 text-muted"></i>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Combined upcoming list --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <h5 class="card-title mb-0"><i class="ri-calendar-event-line me-2"></i>Upcoming 30 Days</h5>
            <p class="text-muted fs-12 mb-0 mt-1">All pending bills &amp; expected income — {{ date('d M Y') }} to {{ date('d M Y', strtotime('+30 days')) }}</p>
        </div>
        <span class="badge bg-secondary-subtle text-secondary">{{ count($combined) }} items</span>
    </div>
    <div class="card-body p-0">
        @if(count($combined) === 0)
        <div class="text-center py-5 text-muted">
            <i class="ri-calendar-check-line fs-2 d-block mb-2"></i>
            Nothing pending in the next 30 days.
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Notes</th>
                        <th class="text-end">Amount (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($combined as $i => $item)
                    @php
                        $isBill   = $item->type === 'bill';
                        $daysLeft = (int) ceil((strtotime($item->expected_date) - strtotime(date('Y-m-d'))) / 86400);
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-medium">{{ date('d M Y', strtotime($item->expected_date)) }}</div>
                            <div class="fs-12 text-muted">
                                {{ $daysLeft === 0 ? 'Today' : ($daysLeft === 1 ? 'Tomorrow' : 'in ' . $daysLeft . ' days') }}
                            </div>
                        </td>
                        <td>
                            @if($isBill)
                                <span class="badge bg-danger-subtle text-danger">
                                    <i class="ri-bill-line me-1"></i>Bill
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success">
                                    <i class="ri-arrow-up-circle-line me-1"></i>Income
                                </span>
                            @endif
                        </td>
                        <td>{{ $item->category_name ?: '—' }}</td>
                        <td class="text-muted">{{ $item->notes ?: '—' }}</td>
                        <td class="text-end fw-semibold {{ $isBill ? 'text-danger' : 'text-success' }}">
                            {{ $isBill ? '-' : '+' }}{{ number_format($item->amount, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection
