@push('portal-styles')
<style>
  .btn-add { display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; transition: background 0.15s; }
  .btn-add:hover { background: #c41525; color: #fff; }
  .btn-add svg { width: 16px; height: 16px; stroke: #fff; fill: none; stroke-width: 2.5; }

  .company-name { font-weight: 600; color: #111; }
  .ruc { font-family: monospace; font-size: 12px; color: #666; }
  .badge-active { background: #f0fdf4; color: #16a34a; }
  .badge-inactive { background: #f5f5f5; color: #999; }

  .btn-edit { padding: 6px 12px; background: #f5f5f5; color: #555; border: none; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 0.15s; }
  .btn-edit:hover { background: #e8192c; color: #fff; }
  .btn-delete { padding: 6px 12px; background: #fff0f0; color: #e8192c; border: none; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; transition: all 0.15s; }
  .btn-delete:hover { background: #e8192c; color: #fff; }

  .hash-card { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 20px 22px; margin-bottom: 20px; }
  .hash-card-header { display: flex; align-items: center; gap: 10px; margin-bottom: 4px; }
  .hash-icon { width: 30px; height: 30px; background: #fff0f2; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
  .hash-icon svg { width: 16px; height: 16px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .hash-card h3 { font-size: 15px; font-weight: 800; color: #111; }
  .hash-card p.subtitle { font-size: 12px; color: #999; margin: 2px 0 14px 40px; }
  .hash-search-row { display: flex; gap: 10px; margin-bottom: 16px; }
  .hash-search-row input { flex: 1; border: 1px solid #e5e5e5; border-radius: 9px; padding: 10px 14px; font-size: 13px; font-family: monospace; }
  .hash-search-row button { background: #111; color: #fff; border: none; border-radius: 9px; padding: 10px 18px; font-size: 13px; font-weight: 600; cursor: pointer; }
  .hash-search-row button:hover { background: #e8192c; }

  .hash-result { display: none; border-radius: 10px; padding: 14px 16px; margin-bottom: 16px; }
  .hash-result.found { background: #f0fdf4; border: 1px solid #bbf7d0; }
  .hash-result.notfound { background: #fef2f2; border: 1px solid #fecaca; }
  .hash-result .result-title { font-size: 13px; font-weight: 700; margin-bottom: 6px; }
  .hash-result.found .result-title { color: #16a34a; }
  .hash-result.notfound .result-title { color: #dc2626; }
  .hash-steps { font-size: 12px; color: #555; line-height: 1.6; }
  .hash-steps code { background: rgba(0,0,0,0.05); padding: 1px 5px; border-radius: 4px; }

  .buckets-viz { display: flex; gap: 4px; align-items: flex-end; height: 60px; margin-top: 4px; }
  .bucket-col { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; gap: 4px; }
  .bucket-bar { width: 100%; background: #e5e5e5; border-radius: 3px 3px 0 0; min-height: 4px; transition: background 0.2s; }
  .bucket-bar.hit { background: #e8192c; }
  .bucket-label { font-size: 9px; color: #aaa; }
</style>
@endpush

<x-portal-layout
    title="Suppliers"
    subtitle="Manage your product suppliers"
    active="suppliers"
>

    <div class="hash-card">
      <div class="hash-card-header">
        <div class="hash-icon">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        </div>
        <h3>Search provider by RUC</h3>
      </div>

      <div class="hash-search-row">
        <input type="text" id="ruc-search" placeholder="Enter the exact RUC, e.g.: {{ $suppliers->first()->ruc ?? '20123456789' }}">
        <button type="button" id="ruc-search-btn">Search</button>
      </div>

      <div class="hash-result" id="hash-result">
        <div class="result-title" id="result-title"></div>
        <div class="hash-steps" id="result-steps"></div>
        <div class="table-card" id="result-table-wrap" style="margin-top:10px; display:none;">
          <table>
            <thead>
              <tr>
                <th>Company</th><th>RUC</th><th>Contact</th><th>Phone</th><th>Email</th><th>Status</th><th>Actions</th>
              </tr>
            </thead>
            <tbody id="result-table-body"></tbody>
          </table>
        </div>
      </div>

      <div class="buckets-viz" id="buckets-viz"></div>
    </div>

    <div class="toolbar" style="justify-content:flex-end">
      <a href="/admin/suppliers/create" class="btn-add">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Supplier
      </a>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Company</th>
            <th>RUC</th>
            <th>Contact</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="suppliers-table">
          @forelse($suppliers as $supplier)
          <tr data-name="{{ strtolower($supplier->company_name) }}">
            <td><span class="company-name">{{ $supplier->company_name }}</span></td>
            <td><span class="ruc">{{ $supplier->ruc }}</span></td>
            <td>{{ $supplier->contact_name ?? '—' }}</td>
            <td>{{ $supplier->phone ?? '—' }}</td>
            <td>{{ $supplier->email ?? '—' }}</td>
            <td>
              @if($supplier->status === 'active')
                <span class="badge badge-active">Active</span>
              @else
                <span class="badge badge-inactive">Inactive</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <a href="/admin/suppliers/{{ $supplier->id }}/edit" class="btn-edit">Edit</a>
                <form action="/admin/suppliers/{{ $supplier->id }}" method="POST" style="display:inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-delete" onclick="return confirm('Delete this supplier?')">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr class="empty-row"><td colspan="7">No suppliers registered yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <script type="application/json" id="suppliers-json">
{!! json_encode($suppliers->map(fn($s) => [
    'id' => $s->id,
    'ruc' => $s->ruc,
    'company_name' => $s->company_name,
    'contact_name' => $s->contact_name,
    'phone' => $s->phone,
    'email' => $s->email,
    'status' => $s->status,
])->toArray()) !!}
    </script>

    <script>
    const suppliersData = JSON.parse(document.getElementById('suppliers-json').textContent);
    </script>

    <script>
    class HashTable {
      constructor(size = 13) {
        this.size = size;
        this.buckets = Array.from({ length: size }, () => []);
      }

      hash(key) {
        let h = 0;
        for (let i = 0; i < key.length; i++) {
          h = (h * 31 + key.charCodeAt(i)) % this.size;
        }
        return h;
      }

      insert(key, value) {
        const index = this.hash(key);
        this.buckets[index].push({ key, value });
      }

      search(key) {
        const index = this.hash(key);
        const bucket = this.buckets[index];
        let comparisons = 0;

        for (const entry of bucket) {
          comparisons++;
          if (entry.key === key) {
            return { found: true, value: entry.value, index, comparisons, bucketSize: bucket.length };
          }
        }
        return { found: false, value: null, index, comparisons, bucketSize: bucket.length };
      }
    }

    const supplierHashTable = new HashTable(13);
    suppliersData.forEach(s => supplierHashTable.insert(s.ruc, s));

    function renderBuckets(highlightIndex = null) {
      const viz = document.getElementById('buckets-viz');
      viz.innerHTML = '';
      supplierHashTable.buckets.forEach((bucket, i) => {
        const col = document.createElement('div');
        col.className = 'bucket-col';
        const bar = document.createElement('div');
        bar.className = 'bucket-bar' + (i === highlightIndex ? ' hit' : '');
        bar.style.height = Math.max(4, bucket.length * 16) + 'px';
        const label = document.createElement('div');
        label.className = 'bucket-label';
        label.textContent = i;
        col.appendChild(bar);
        col.appendChild(label);
        viz.appendChild(col);
      });
    }
    renderBuckets();

    function searchSupplierByRuc() {
      const ruc = document.getElementById('ruc-search').value.trim();
      const resultBox = document.getElementById('hash-result');
      const title = document.getElementById('result-title');
      const steps = document.getElementById('result-steps');
      const tableWrap = document.getElementById('result-table-wrap');
      const tableBody = document.getElementById('result-table-body');

      if (!ruc) return;

      const result = supplierHashTable.search(ruc);
      resultBox.style.display = 'block';
      renderBuckets(result.index);

      if (result.found) {
        const s = result.value;
        resultBox.className = 'hash-result found';
        title.textContent = `✓ Encontrado en bucket ${result.index} (${result.comparisons} comparación${result.comparisons > 1 ? 'es' : ''})`;
        steps.innerHTML = `<div>RUC <code>${ruc}</code> → hash → bucket <code>${result.index}</code> de <code>${supplierHashTable.size}</code> · tamaño del bucket: ${result.bucketSize}</div>`;

        tableWrap.style.display = 'block';
        tableBody.innerHTML = `
          <tr>
            <td><span class="company-name">${s.company_name}</span></td>
            <td><span class="ruc">${s.ruc}</span></td>
            <td>${s.contact_name ?? '—'}</td>
            <td>${s.phone ?? '—'}</td>
            <td>${s.email ?? '—'}</td>
            <td>${s.status === 'active' ? '<span class="badge badge-active">Active</span>' : '<span class="badge badge-inactive">Inactive</span>'}</td>
            <td>
              <div class="actions">
                <a href="/admin/suppliers/${s.id}/edit" class="btn-edit">Edit</a>
                <form action="/admin/suppliers/${s.id}" method="POST" style="display:inline" onsubmit="return confirm('Delete this supplier?')">
                  <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]')?.content ?? ''}">
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn-delete">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        `;
      } else {
        resultBox.className = 'hash-result notfound';
        title.textContent = `✗ Ningún proveedor con RUC ${ruc}`;
        steps.innerHTML = `<div>RUC <code>${ruc}</code> → hash → bucket <code>${result.index}</code> de <code>${supplierHashTable.size}</code> · ${result.comparisons} comparación${result.comparisons === 1 ? '' : 'es'} sin coincidencias</div>`;
        tableWrap.style.display = 'none';
        tableBody.innerHTML = '';
      }
    }

    document.getElementById('ruc-search-btn').addEventListener('click', searchSupplierByRuc);
    document.getElementById('ruc-search').addEventListener('keydown', (e) => { if (e.key === 'Enter') searchSupplierByRuc(); });
    </script>

</x-portal-layout>