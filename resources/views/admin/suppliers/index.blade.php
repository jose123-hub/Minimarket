<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Express — Suppliers</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }

  .sidebar { width: 240px; min-height: 100vh; background: #111; display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; }
  .sidebar-logo { display: flex; align-items: center; gap: 12px; padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.06); }
  .logo-icon { width: 38px; height: 38px; background: #e8192c; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
  .logo-icon svg { width: 20px; height: 20px; fill: #fff; }
  .logo-text strong { font-size: 15px; font-weight: 700; color: #fff; display: block; }
  .logo-text span { font-size: 11px; color: #666; }
  .sidebar-nav { flex: 1; padding: 16px 12px; }
  .nav-item { display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 8px; color: #888; font-size: 14px; font-weight: 500; text-decoration: none; margin-bottom: 2px; transition: all 0.15s; }
  .nav-item:hover { background: rgba(255,255,255,0.06); color: #fff; }
  .nav-item.active { background: #e8192c; color: #fff; }
  .nav-item svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; flex-shrink: 0; }
  .sidebar-user { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 12px; }
  .user-avatar { width: 34px; height: 34px; background: #e8192c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; }
  .user-info strong { font-size: 13px; color: #fff; display: block; }
  .user-info span { font-size: 11px; color: #666; }
  .logout-btn { margin-left: auto; background: none; border: none; cursor: pointer; color: #555; }
  .logout-btn:hover { color: #e8192c; }
  .logout-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }

  .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }
  .topbar { background: #fff; padding: 16px 28px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 10; }
  .topbar-title h1 { font-size: 22px; font-weight: 800; color: #111; }
  .topbar-title p { font-size: 13px; color: #999; margin-top: 2px; }
  .topbar-right { display: flex; align-items: center; gap: 20px; }
  .topbar-date { font-size: 13px; color: #888; }

  .content { padding: 24px 28px; }

  .toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .search-box { display: flex; align-items: center; gap: 8px; background: #fff; border: 1px solid #e8e8e8; border-radius: 8px; padding: 9px 14px; width: 300px; }
  .search-box svg { width: 15px; height: 15px; stroke: #aaa; fill: none; stroke-width: 1.8; }
  .search-box input { border: none; background: transparent; font-size: 13px; color: #555; outline: none; width: 100%; }
  .btn-add { display: flex; align-items: center; gap: 8px; padding: 10px 20px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; transition: background 0.15s; }
  .btn-add:hover { background: #c41525; color: #fff; }
  .btn-add svg { width: 16px; height: 16px; stroke: #fff; fill: none; stroke-width: 2.5; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; }
  table { width: 100%; border-collapse: collapse; }
  thead { background: #fafafa; border-bottom: 1px solid #eee; }
  th { padding: 12px 16px; font-size: 11px; font-weight: 600; color: #999; text-align: left; letter-spacing: 0.05em; text-transform: uppercase; }
  td { padding: 14px 16px; font-size: 13px; color: #333; border-bottom: 1px solid #f5f5f5; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #fafafa; }

  .company-name { font-weight: 600; color: #111; }
  .ruc { font-family: monospace; font-size: 12px; color: #666; }
  .badge { display: inline-flex; padding: 4px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; }
  .badge-active { background: #f0fdf4; color: #16a34a; }
  .badge-inactive { background: #f5f5f5; color: #999; }

  .actions { display: flex; gap: 8px; }
  .btn-edit { padding: 6px 12px; background: #f5f5f5; color: #555; border: none; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 0.15s; }
  .btn-edit:hover { background: #e8192c; color: #fff; }
  .btn-delete { padding: 6px 12px; background: #fff0f0; color: #e8192c; border: none; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; transition: all 0.15s; }
  .btn-delete:hover { background: #e8192c; color: #fff; }

  .success-msg { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin-bottom: 16px; }
  .empty { text-align: center; padding: 48px; color: #aaa; font-size: 14px; }

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
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text"><strong>Express</strong><span>Minimarket POS</span></div>
  </div>
  <nav class="sidebar-nav">
    <a href="/dashboard" class="nav-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard
    </a>
    <a href="/admin/products" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Inventory
    </a>
    <a href="/admin/suppliers" class="nav-item active">
      <svg viewBox="0 0 24 24"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Suppliers
    </a>
    <a href="/admin/purchases" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>Purchases
    </a>
    <a href="/admin/promotions" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Promotions
    </a>
    <a href="/admin/rewards" class="nav-item">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Rewards
    </a>
    <a href="/admin/reports" class="nav-item">
      <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports
    </a>
  </nav>
  <div class="sidebar-user">
    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="user-info">
      <strong>{{ Auth::user()->name }}</strong>
      <span>{{ ucfirst(Auth::user()->role) }}</span>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">
      @csrf
      <button type="submit" class="logout-btn">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </button>
    </form>
  </div>
</aside>

<div class="main">
  <div class="topbar">
    <div class="topbar-title">
      <h1>Suppliers</h1>
      <p>Manage your product suppliers</p>
    </div>
    <div class="topbar-right">
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="success-msg">{{ session('success') }}</div>
    @endif

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
          <tr><td colspan="7" class="empty">No suppliers registered yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
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

</body>
</html>