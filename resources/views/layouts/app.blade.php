<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel Starter') }}</title>
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root { --sidebar-width: 250px; }
        body { min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); position: fixed; top: 0; left: 0; height: 100vh; background: #212529; padding-top: 1rem; overflow-y: auto; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 0.5rem 1rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,0.1); }
        .sidebar .nav-section { color: rgba(255,255,255,0.5); padding: 0.5rem 1rem; font-size: 0.8rem; text-transform: uppercase; cursor: pointer; }
        .main-content { margin-left: var(--sidebar-width); padding: 1.5rem; }
        .sidebar-brand { color: #fff; font-size: 1.25rem; font-weight: 600; padding: 0 1rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1rem; }
    </style>
</head>
<body>
    @auth
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-box me-2"></i>{{ config('app.name') }}
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
            </li>

            @if(auth()->user()->role === 'admin')
            <div class="nav-section">Administration</div>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people me-2"></i>Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                    <i class="bi bi-shield-lock me-2"></i>Role Permissions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.ldap.*') ? 'active' : '' }}" href="{{ route('admin.ldap.index') }}">
                    <i class="bi bi-diagram-3 me-2"></i>LDAP Servers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}" href="{{ route('admin.backups.index') }}">
                    <i class="bi bi-database me-2"></i>Backups
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}" href="{{ route('admin.sessions.index') }}">
                    <i class="bi bi-display me-2"></i>Sessions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.scheduler.*') ? 'active' : '' }}" href="{{ route('admin.scheduler.index') }}">
                    <i class="bi bi-clock-history me-2"></i>Scheduler
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}" href="{{ route('admin.audit-logs.index') }}">
                    <i class="bi bi-journal-text me-2"></i>Audit Logs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cleanup.*') ? 'active' : '' }}" href="{{ route('admin.cleanup.index') }}">
                    <i class="bi bi-trash3 me-2"></i>Data Cleanup
                </a>
            </li>
            @endif

            <hr class="my-2 border-secondary">
            <li class="nav-item">
                <span class="nav-link text-muted"><i class="bi bi-person me-2"></i>{{ auth()->user()->name }}</span>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>
    @else
        @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- PWA Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
    @stack('scripts')
</body>
</html>
