@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5><i class="bi bi-people me-2"></i>Users</h5>
                    <h2>{{ \App\Models\User::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5><i class="bi bi-display me-2"></i>Active Sessions</h5>
                    <h2>{{ \App\Models\UserSession::where('last_active_at', '>=', now()->subMinutes(5))->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5><i class="bi bi-journal-text me-2"></i>Audit Logs Today</h5>
                    <h2>{{ \App\Models\AuditLog::whereDate('created_at', today())->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Quick Actions</div>
        <div class="card-body">
            <a href="{{ route('admin.backups.index') }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-database me-1"></i>Manage Backups
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-people me-1"></i>Manage Users
            </a>
            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-journal-text me-1"></i>View Audit Logs
            </a>
        </div>
    </div>
</div>
@endsection
