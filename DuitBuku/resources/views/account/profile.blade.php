@extends('master_page.master_page')
@section('page_title', 'Profile')

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Profile</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>
</div>

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body text-center py-4">
                <img src="assets/images/users/avatar-1.jpg" alt="Avatar" class="rounded-circle avatar-lg mb-3">
                <h4 class="mb-1">{{ $user->name }}</h4>
                <span class="badge bg-primary-subtle text-primary fs-12 px-3 py-1 mb-3">{{ $user->role ?? 'Owner' }}</span>

                <dl class="row text-start mt-3 mb-0">
                    <dt class="col-5 text-muted fw-normal"><i class="ri-mail-line me-1"></i>Email</dt>
                    <dd class="col-7 fw-medium">{{ $user->email }}</dd>

                    <dt class="col-5 text-muted fw-normal"><i class="ri-shield-user-line me-1"></i>Role</dt>
                    <dd class="col-7 fw-medium">{{ $user->role ?? 'Owner' }}</dd>

                    <dt class="col-5 text-muted fw-normal"><i class="ri-calendar-line me-1"></i>Member since</dt>
                    <dd class="col-7 fw-medium">{{ $user->created_at?->format('F Y') ?? '—' }}</dd>
                </dl>

                <a href="{{ route('settings') }}" class="btn btn-primary mt-3">
                    <i class="ri-settings-3-line me-1"></i> Edit in Settings
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
