<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ReportLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $audits = $query->paginate(12)->withQueryString();

        $totalAudits = AuditLog::count();
        $createdCount = AuditLog::where('action', 'created')->count();
        $updatedCount = AuditLog::where('action', 'updated')->count();
        $deletedCount = AuditLog::where('action', 'deleted')->count();

        return view('admin.audit.index', compact(
            'audits',
            'totalAudits',
            'createdCount',
            'updatedCount',
            'deletedCount'
        ));
    }

    public function show(AuditLog $audit)
    {
        $audit->load('user');

        return view('admin.audit.show', compact('audit'));
    }
}