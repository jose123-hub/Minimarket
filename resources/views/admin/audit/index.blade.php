<x-portal-layout
    title="Audit"
    subtitle="System activity monitoring"
    active="audit"
>
    <div class="metrics">
        <div class="metric-card">
            <span>Total events</span>
            <strong>{{ $totalAudits }}</strong>
        </div>

        <div class="metric-card">
            <span>Created</span>
            <strong>{{ $createdCount }}</strong>
        </div>

        <div class="metric-card">
            <span>Updated</span>
            <strong>{{ $updatedCount }}</strong>
        </div>

        <div class="metric-card">
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
                        <td colspan="6">No audit records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div>
            {{ $audits->links() }}
        </div>
    </div>
</x-portal-layout>