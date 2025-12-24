<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\AuditLogArchive;
use App\Models\UserSession;
use App\Models\SchedulerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataCleanupController extends Controller
{
    public function index()
    {
        $tables = [
            [
                'name' => 'Audit Logs',
                'table' => 'audit_logs',
                'count' => AuditLog::count(),
                'description' => 'Track all model changes',
            ],
            [
                'name' => 'Audit Archives',
                'table' => 'audit_log_archives',
                'count' => AuditLogArchive::count(),
                'description' => 'Archived audit logs',
            ],
            [
                'name' => 'User Sessions',
                'table' => 'user_sessions',
                'count' => UserSession::count(),
                'description' => 'Active login sessions',
            ],
            [
                'name' => 'Scheduler Logs',
                'table' => 'scheduler_logs',
                'count' => SchedulerLog::count(),
                'description' => 'Scheduled task execution logs',
            ],
        ];

        return view('admin.cleanup.index', compact('tables'));
    }

    public function cleanup(Request $request)
    {
        $request->validate([
            'tables' => 'required|array|min:1',
            'tables.*' => 'in:audit_logs,audit_log_archives,user_sessions,scheduler_logs',
        ]);

        $deleted = [];

        foreach ($request->tables as $table) {
            $count = DB::table($table)->count();
            DB::table($table)->truncate();
            $deleted[$table] = $count;
        }

        $summary = collect($deleted)->map(fn($c, $t) => "{$t}: {$c}")->implode(', ');

        return back()->with('success', "Cleaned up: {$summary}");
    }
}
