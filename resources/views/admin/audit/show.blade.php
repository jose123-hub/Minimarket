<x-portal-layout
    title="Audit Detail"
    subtitle="Detailed system activity record"
    active="audit"
>
    <div class="table-card" style="padding: 24px;">
        <h3 style="font-size:18px; font-weight:900; margin-bottom:18px;">
            {{ $audit->description ?? 'Audit record' }}
        </h3>

        <p><strong>User:</strong> {{ $audit->user?->name ?? 'System' }}</p>
        <p><strong>Module:</strong> {{ $audit->module ?? '-' }}</p>
        <p><strong>Action:</strong> {{ ucfirst($audit->action) }}</p>
        <p><strong>Table:</strong> {{ $audit->table_name ?? '-' }}</p>
        <p><strong>Record ID:</strong> {{ $audit->record_id ?? '-' }}</p>
        <p><strong>Date:</strong> {{ $audit->created_at->format('d/m/Y h:i A') }}</p>
        <p><strong>IP:</strong> {{ $audit->ip_address ?? '-' }}</p>

        <hr style="margin:20px 0; border:none; border-top:1px solid #eee;">

        <h4 style="margin-bottom:10px;">Old values</h4>
        <pre style="background:#fafafa; border:1px solid #eee; border-radius:10px; padding:14px; overflow:auto;">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

        <h4 style="margin:20px 0 10px;">New values</h4>
        <pre style="background:#fafafa; border:1px solid #eee; border-radius:10px; padding:14px; overflow:auto;">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

        <a href="{{ route('admin.audit.index') }}" class="btn" style="margin-top:18px;">
            Back to audit
        </a>
    </div>
</x-portal-layout>