@extends('master_page.master_page')
@section('page_title', 'Settings')

@section('content')

<div class="page-title-box bg-galaxy-transparent">
    <h4 class="mb-1">Settings</h4>
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Settings</li>
    </ol>
</div>

@if (session('success'))
<div class="alert alert-success py-2 mb-4">{{ session('success') }}</div>
@endif

@if ($errors->any())
<div class="alert alert-danger py-2 mb-4">
    @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
</div>
@endif

<div class="row justify-content-center">
    <div class="col-xl-6">

        {{-- Account Details --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-user-settings-line me-2 text-primary"></i>Account Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-control" name="role" value="{{ old('role', $user->role) }}" placeholder="e.g. Owner">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="ri-lock-password-line me-2 text-warning"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.password') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="ri-key-2-line me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
