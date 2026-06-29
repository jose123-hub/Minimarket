@push('portal-styles')
<style>
    .audit-metrics {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .audit-metric-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 14px;
        padding: 20px;
    }

    .audit-metric-card span {
        display: block;
        font-size: 13px;
        color: #999;
        margin-bottom: 8px;
    }

    .audit-metric-card strong {
        display: block;
        font-size: 30px;
        font-weight: 900;
        color: #111;
    }

    .table-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 14px;
        overflow: hidden;
    }

    .card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #eee;
    }

    .card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 900;
        color: #111;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card th {
        padding: 16px 22px;
        font-size: 12px;
        color: #999;
        text-align: left;
        text-transform: uppercase;
        border-bottom: 1px solid #eee;
    }

    .table-card td {
        padding: 16px 22px;
        font-size: 14px;
        color: #111;
        border-bottom: 1px solid #f5f5f5;
    }

    .audit-empty {
        padding: 28px 22px;
        color: #999;
        font-size: 14px;
        text-align: center;
    }

    .audit-action {
        display: inline-flex;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 900;
        background: #f5f5f5;
        color: #333;
    }

    .audit-action.created {
        background: #ecfdf5;
        color: #16a34a;
    }

    .audit-action.updated {
        background: #eff6ff;
        color: #2563eb;
    }

    .audit-action.deleted {
        background: #fef2f2;
        color: #dc2626;
    }

    .audit-detail-link {
        color: #e8192c;
        text-decoration: none;
        font-weight: 800;
    }
</style>
@endpush
<x-portal-layout
    title="Audit"
    subtitle="System activity monitoring"
    active="audit"
>
    <div class="audit-metrics">
    <div class="audit-metric-card">
        <span>Total events</span>
        <strong>{{ $totalAudits }}</strong>
    </div>

    <div class="audit-metric-card">
        <span>Created</span>
        <strong>{{ $createdCount }}</strong>
    </div>

    <div class="audit-metric-card">
        <span>Updated</span>
        <strong>{{ $updatedCount }}</strong>
    </div>

    <div class="audit-metric-card">
        <span>Deleted</span>
        <strong>{{ $deletedCount }}</strong>
     </div>
    </div>
    <div class="table-card">
        <div class="card-header">
            <h3>System activity</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Table</th>
                    <th>Detail</th>
                </tr>
            </thead>

            <tbody>
                @forelse($audits as $audit)
                    <tr>
                        <td>{{ $audit->created_at->format('d/m/Y h:i A') }}</td>
                        <td>{{ $audit->user?->name ?? 'System' }}</td>
                        <td>{{ $audit->module ?? '-' }}</td>
                        <td>{{ ucfirst($audit->action) }}</td>
                        <td>{{ $audit->table_name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.audit.show', $audit) }}">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                <td colspan="6">
                 <div class="audit-empty">
                  No audit records found.
                 </div>
                 </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div>
            {{ $audits->links() }}
        </div>
    </div>
</x-portal-layout>