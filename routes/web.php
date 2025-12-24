<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LdapServerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication (use Laravel Breeze or UI)
Route::middleware('guest')->group(function () {
    Route::get('login', fn() => view('auth.login'))->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    
    // 2FA Challenge (during login)
    Route::get('2fa/challenge', [\App\Http\Controllers\TwoFactorController::class, 'challenge'])->name('2fa.challenge')->withoutMiddleware('guest');
    Route::post('2fa/verify', [\App\Http\Controllers\TwoFactorController::class, 'verify'])->name('2fa.verify')->withoutMiddleware('guest');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // Push Subscriptions
    Route::post('push/subscribe', [NotificationController::class, 'subscribe'])->name('push.subscribe');
    Route::delete('push/unsubscribe', [NotificationController::class, 'unsubscribe'])->name('push.unsubscribe');

    // Two-Factor Authentication
    Route::get('2fa', [\App\Http\Controllers\TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('2fa/enable', [\App\Http\Controllers\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('2fa/confirm', [\App\Http\Controllers\TwoFactorController::class, 'confirm'])->name('2fa.confirm');
    Route::post('2fa/disable', [\App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');
    Route::post('2fa/regenerate', [\App\Http\Controllers\TwoFactorController::class, 'regenerateCodes'])->name('2fa.regenerate');
    Route::delete('sessions/{session}', [\App\Http\Controllers\TwoFactorController::class, 'terminateSession'])->name('sessions.terminate');
    Route::post('sessions/other', [\App\Http\Controllers\TwoFactorController::class, 'terminateOtherSessions'])->name('sessions.terminate-others');

    // Admin routes (requires admin role)
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        
        // User Management
        Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // LDAP Servers
        Route::get('ldap', [LdapServerController::class, 'index'])->name('ldap.index');
        Route::get('ldap/create', [LdapServerController::class, 'create'])->name('ldap.create');
        Route::post('ldap', [LdapServerController::class, 'store'])->name('ldap.store');
        Route::put('ldap/{ldapServer}', [LdapServerController::class, 'update'])->name('ldap.update');
        Route::delete('ldap/{ldapServer}', [LdapServerController::class, 'destroy'])->name('ldap.destroy');
        Route::get('ldap/{ldapServer}/test', [LdapServerController::class, 'testConnection'])->name('ldap.test');

        // Database Backups
        Route::get('backups', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
        Route::post('backups', [\App\Http\Controllers\Admin\BackupController::class, 'create'])->name('backups.create');
        Route::get('backups/{filename}/download', [\App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backups.download');
        Route::post('backups/{filename}/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backups.restore');
        Route::post('backups/restore-file', [\App\Http\Controllers\Admin\BackupController::class, 'restoreFromFile'])->name('backups.restore-file');
        Route::delete('backups/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('backups.destroy');
        Route::post('backups/schedule', [\App\Http\Controllers\Admin\BackupController::class, 'updateSchedule'])->name('backups.schedule');
        Route::post('backups/delete-batch', [\App\Http\Controllers\Admin\BackupController::class, 'deleteBatch'])->name('backups.delete-batch');
        Route::post('backups/prune', [\App\Http\Controllers\Admin\BackupController::class, 'prune'])->name('backups.prune');

        // Session Manager
        Route::get('sessions', [\App\Http\Controllers\Admin\SessionController::class, 'index'])->name('sessions.index');
        Route::post('sessions/settings', [\App\Http\Controllers\Admin\SessionController::class, 'updateSettings'])->name('sessions.settings');
        Route::post('sessions/cleanup', [\App\Http\Controllers\Admin\SessionController::class, 'cleanup'])->name('sessions.cleanup');
        Route::delete('sessions/{session}', [\App\Http\Controllers\Admin\SessionController::class, 'terminate'])->name('sessions.terminate');

        // Scheduler
        Route::get('scheduler', [\App\Http\Controllers\Admin\SchedulerController::class, 'index'])->name('scheduler.index');
        Route::post('scheduler/run', [\App\Http\Controllers\Admin\SchedulerController::class, 'runNow'])->name('scheduler.run');
        Route::patch('scheduler/{setting}/toggle', [\App\Http\Controllers\Admin\SchedulerController::class, 'toggle'])->name('scheduler.toggle');
        Route::put('scheduler/{setting}', [\App\Http\Controllers\Admin\SchedulerController::class, 'update'])->name('scheduler.update');
        Route::get('scheduler/logs', [\App\Http\Controllers\Admin\SchedulerController::class, 'logs'])->name('scheduler.logs');

        // Role Management
        Route::get('roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store');
        Route::get('roles/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy');
        Route::get('roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleController::class, 'permissions'])->name('roles.permissions');
        Route::post('roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
        Route::get('roles/{role}/fields/{doctype}', [\App\Http\Controllers\Admin\RoleController::class, 'fieldPermissions'])->name('roles.field-permissions');
        Route::post('roles/{role}/fields/{doctype}', [\App\Http\Controllers\Admin\RoleController::class, 'updateFieldPermissions'])->name('roles.update-field-permissions');

        // Audit Logs
        Route::get('audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('audit-logs/archives', [\App\Http\Controllers\Admin\AuditLogController::class, 'archives'])->name('audit-logs.archives');
        Route::post('audit-logs/archive', [\App\Http\Controllers\Admin\AuditLogController::class, 'archive'])->name('audit-logs.archive');
        Route::post('audit-logs/settings', [\App\Http\Controllers\Admin\AuditLogController::class, 'updateSettings'])->name('audit-logs.settings');
        Route::get('audit-logs/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'exportArchives'])->name('audit-logs.export');

        // Data Cleanup
        Route::get('cleanup', [\App\Http\Controllers\Admin\DataCleanupController::class, 'index'])->name('cleanup.index');
        Route::post('cleanup', [\App\Http\Controllers\Admin\DataCleanupController::class, 'cleanup'])->name('cleanup.run');
    });
});
