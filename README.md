# Laravel Starter Template

A reusable Laravel 11 starter template with admin panel, audit logging, PWA, push notifications, and more.

## Features

| Feature | Description |
|---------|-------------|
| **User Management** | Create/edit users, assign roles (admin/user/viewer) |
| **LDAP Integration** | Connect to LDAP/AD for authentication |
| **Database Backups** | Create, download, restore, auto-prune backups |
| **Session Manager** | View active sessions, terminate, auto-cleanup |
| **Scheduler** | Manage scheduled tasks with logs |
| **Audit Logs** | Track all model changes with archiving |
| **Data Cleanup** | Clean template-specific tables |
| **PWA Support** | Installable web app with service worker |
| **Push Notifications** | Web push with subscription management |
| **Live Notifications** | In-app notification bell |
| **Docker Ready** | docker-compose, Dockerfile, nginx config |

## Quick Start

```bash
# Clone repository
git clone https://github.com/your-username/laravel-starter-template.git my-app
cd my-app

# Install dependencies
composer install
npm install && npm run build

# Configure environment
cp .env.example .env
php artisan key:generate

# Set database in .env, then migrate
php artisan migrate

# Create admin user
php artisan tinker
>>> \App\Models\User::create(['name'=>'Admin', 'email'=>'admin@example.com', 'password'=>bcrypt('password'), 'role'=>'admin']);

# Start development server
php artisan serve
```

## Docker Deployment

```bash
docker-compose up -d
docker exec laravel-app php artisan migrate --force
```

## Directory Structure

```
app/
├── Console/Commands/     # ArchiveAuditLogs, CleanupInactiveSessions
├── Http/Controllers/Admin/
│   ├── AuditLogController.php
│   ├── BackupController.php
│   ├── DataCleanupController.php
│   ├── SchedulerController.php
│   ├── SessionController.php
│   └── UserController.php
├── Models/               # User, AuditLog, Notification, etc.
├── Services/             # BackupService
└── Traits/               # Auditable trait

resources/views/
├── admin/                # All admin views
├── layouts/app.blade.php # Main layout with sidebar
└── dashboard.blade.php

public/
├── manifest.json         # PWA manifest
└── sw.js                 # Service worker
```

## Adding Auditable to Models

To track changes for any model:

```php
use App\Traits\Auditable;

class YourModel extends Model
{
    use Auditable;
    // ...
}
```

## Scheduled Tasks

Default tasks (run `php artisan schedule:run`):
- `audit:archive` - Archive old audit logs (daily 2am)
- `sessions:cleanup` - Clean inactive sessions (daily 3am)

## Role Permission System

This template includes an ERPNext-style dynamic role permission system.

### Features
- **Dynamic Roles** - Create/edit/delete custom roles
- **DocType Permissions** - Read/Write/Create/Delete/Export per model
- **Field Permissions** - Control which fields each role can edit
- **Multiple Roles** - Users can have multiple roles (permissions combine)

### Default Roles
| Role | Description |
|------|-------------|
| Administrator | Full system access |
| Manager | Full data access, limited admin |
| Viewer | Read-only access |

### Adding a New DocType

When you add new features, register them in the permission system:

**Step 1: Add to `app/Models/Role.php`:**
```php
public static function getDocTypes(): array
{
    return [
        // ... existing ...
        'NewFeature' => 'My New Feature',
    ];
}
```

**Step 2: (Optional) Add field definitions in `app/Models/FieldPermission.php`:**
```php
'NewFeature' => [
    'title' => 'Title',
    'status' => 'Status',
],
```

**Step 3: Use in controllers:**
```php
if (!auth()->user()->canRead('NewFeature')) {
    abort(403);
}
```

**Step 4: Use in views:**
```blade
@if(auth()->user()->canWriteField('NewFeature', 'title'))
    <input name="title" ...>
@else
    <input name="title" disabled readonly>
@endif
```

### Permission Methods

| Method | Description |
|--------|-------------|
| `canDo($doctype, $action)` | Check read/write/create/delete/export |
| `canRead($doctype)` | Shortcut for read permission |
| `canWrite($doctype)` | Shortcut for write permission |
| `canWriteField($doctype, $field)` | Check field write permission |
| `canReadField($doctype, $field)` | Check field read permission |

## 🤖 AI-Assisted Development

This repository was created with the assistance of **Antigravity AI** (Google DeepMind). 
The AI helped with:
- Architecture design and implementation
- Code generation and best practices
- Documentation and testing

## License

MIT
