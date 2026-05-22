<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $auditLogs = AuditLog::with('user')->latest()->paginate(30);

        return view('pages.finance.audit-logs.index', compact('auditLogs'));
    }
}
