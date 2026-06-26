@push('portal-styles')
<style>
  .form-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 28px; max-width: 700px; }
  .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .form-grid .form-group.full { grid-column: 1 / -1; }
  .form-grid .form-group label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 8px; }
  .required { color: #e8192c; }

  .form-actions { display: flex; gap: 12px; margin-top: 24px; }
  .btn-save { padding: 11px 28px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
  .btn-save:hover { background: #c41525; }
  .btn-cancel { padding: 11px 28px; background: #f5f5f5; color: #555; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; }
  .btn-cancel:hover { background: #eee; }
</style>
@endpush

<x-portal-layout
    title="Edit Supplier"
    subtitle="Update supplier information"
    active="suppliers"
>
    <div class="form-card">
      <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-grid">
          <div class="form-group full">
            <label>Company Name <span class="required">*</span></label>
            <input type="text" name="company_name" value="{{ $supplier->company_name }}">
          </div>
          <div class="form-group">
            <label>RUC <span class="required">*</span></label>
            <input type="text" name="ruc" value="{{ $supplier->ruc }}" maxlength="11">
          </div>
          <div class="form-group">
            <label>Contact Name</label>
            <input type="text" name="contact_name" value="{{ $supplier->contact_name }}">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ $supplier->phone }}">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ $supplier->email }}">
          </div>
          <div class="form-group full">
            <label>Address</label>
            <input type="text" name="address" value="{{ $supplier->address }}">
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status">
              <option value="active" {{ $supplier->status === 'active' ? 'selected' : '' }}>Active</option>
              <option value="inactive" {{ $supplier->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn-save">Update Supplier</button>
          <a href="/admin/suppliers" class="btn-cancel">Cancel</a>
        </div>
      </form>
    </div>

</x-portal-layout>