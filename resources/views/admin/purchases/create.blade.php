<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — New Purchase</title>
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

  .content { padding: 24px 28px; }

  .top-cards { display: grid; grid-template-columns: 280px 1fr; gap: 20px; margin-bottom: 20px; }

  .supplier-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 20px; }
  .supplier-card h3 { font-size: 14px; font-weight: 700; color: #111; display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
  .supplier-card h3 svg { width: 16px; height: 16px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .supplier-card select { width: 100%; padding: 10px 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 13px; color: #333; outline: none; margin-bottom: 14px; }
  .supplier-card select:focus { border-color: #e8192c; }
  .info-row { display: flex; justify-content: space-between; font-size: 12px; padding: 4px 0; }
  .info-row .label { color: #999; }
  .info-row .value { color: #333; font-weight: 500; }

  .add-product-card { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 20px; }
  .add-product-card h3 { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 16px; }
  .add-row { display: flex; gap: 10px; align-items: center; }
  .add-row select { flex: 1; padding: 10px 12px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 13px; color: #333; outline: none; }
  .add-row select:focus { border-color: #e8192c; }
  .btn-add-product { padding: 10px 20px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 6px; }
  .btn-add-product:hover { background: #c41525; }
  .btn-add-product svg { width: 14px; height: 14px; stroke: #fff; fill: none; stroke-width: 2.5; }

  .table-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; }
  thead { background: #fafafa; border-bottom: 1px solid #eee; }
  th { padding: 12px 16px; font-size: 11px; font-weight: 600; color: #999; text-align: left; letter-spacing: 0.05em; text-transform: uppercase; }
  td { padding: 12px 16px; font-size: 13px; color: #333; border-bottom: 1px solid #f5f5f5; }
  tr:last-child td { border-bottom: none; }
  .product-name { font-weight: 600; color: #111; }
  .qty-input, .cost-input { width: 80px; padding: 7px 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 13px; text-align: center; outline: none; }
  .qty-input:focus, .cost-input:focus { border-color: #e8192c; }
  .subtotal { font-weight: 700; color: #111; }
  .btn-remove { background: none; border: none; cursor: pointer; color: #ddd; padding: 4px; }
  .btn-remove:hover { color: #e8192c; }
  .btn-remove svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .total-row td { font-weight: 700; font-size: 14px; }
  .grand-total { color: #e8192c; font-size: 18px; }
  .empty-row td { text-align: center; color: #ccc; padding: 32px; font-size: 13px; }

  .form-actions { display: flex; justify-content: flex-end; gap: 12px; }
  .btn-save { padding: 12px 28px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; }
  .btn-save:hover { background: #c41525; }
  .btn-save svg { width: 16px; height: 16px; stroke: #fff; fill: none; stroke-width: 1.8; }
  .btn-cancel { padding: 12px 28px; background: #f5f5f5; color: #555; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; }
  .btn-cancel:hover { background: #eee; }

  .error-msg { background: #fff0f0; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c00; margin-bottom: 16px; }
</style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
    <div class="logo-text"><strong>Express</strong><span>Minimarket POS</span></div>
  </div>
  <nav class="sidebar-nav">
    <a href="/dashboard" class="nav-item"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>Sales (POS)</a>
    <a href="/admin/products" class="nav-item"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>Inventory</a>
    <a href="/admin/suppliers" class="nav-item"><svg viewBox="0 0 24 24"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Suppliers</a>
    <a href="/admin/purchases" class="nav-item active"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>Purchases</a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Promotions</a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Loyalty</a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Reports</a>
    <a href="/admin/categories" class="nav-item"><svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>Categories</a>
  </nav>
  <div class="sidebar-user">
    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="user-info"><strong>{{ Auth::user()->name }}</strong><span>{{ ucfirst(Auth::user()->role) }}</span></div>
    <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">
      @csrf
      <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></button>
    </form>
  </div>
</aside>

<div class="main">
  <div class="topbar">
    <div class="topbar-title">
      <h1>New Purchase Order</h1>
      <p>Register new order to supplier</p>
    </div>
    <div class="topbar-right">
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
    </div>
  </div>

  <div class="content">

    @if(session('error'))
      <div class="error-msg">{{ session('error') }}</div>
    @endif

    <form id="purchase-form" method="POST" action="{{ route('admin.purchases.store') }}">
      @csrf

      <div class="top-cards">
        <div class="supplier-card">
          <h3>
            <svg viewBox="0 0 24 24"><path d="M1 3h15v13H1z"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            Supplier
          </h3>
          <select name="supplier_id" id="supplier-select" onchange="updateSupplierInfo()">
            <option value="">— Select supplier —</option>
            @foreach($suppliers as $supplier)
              <option value="{{ $supplier->id }}"
                data-ruc="{{ $supplier->ruc }}"
                data-contact="{{ $supplier->email ?? '—' }}">
                {{ $supplier->company_name }}
              </option>
            @endforeach
          </select>
          <div class="info-row"><span class="label">RUC</span><span class="value" id="info-ruc">—</span></div>
          <div class="info-row"><span class="label">Contact</span><span class="value" id="info-contact">—</span></div>
          <div class="info-row"><span class="label">Date</span><span class="value">{{ now()->format('d/m/Y') }}</span></div>
          <div class="info-row"><span class="label">Order #</span><span class="value">OC-{{ str_pad(rand(1000,9999), 4, '0', STR_PAD_LEFT) }}</span></div>
        </div>

        <div class="add-product-card">
          <h3>Add product</h3>
          <div class="add-row">
            <select id="product-select">
              <option value="">— Select product —</option>
              @foreach($products as $product)
                <option value="{{ $product->id }}"
                  data-name="{{ $product->name }}"
                  data-cost="{{ $product->cost ?? 0 }}">
                  {{ $product->name }} — S/ {{ number_format($product->cost ?? 0, 2) }}
                </option>
              @endforeach
            </select>
            <button type="button" class="btn-add-product" onclick="addProduct()">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Add
            </button>
          </div>
        </div>
      </div>

      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Unit Cost</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="products-tbody">
            <tr class="empty-row" id="empty-row">
              <td colspan="5">No products added yet</td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="3" style="text-align:right; color:#999; font-size:13px;">Total purchase</td>
              <td><span class="grand-total" id="grand-total">S/ 0.00</span></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <div id="hidden-inputs"></div>

      <div class="form-actions">
        <a href="/admin/purchases" class="btn-cancel">Cancel</a>
        <button type="submit" class="btn-save" onclick="return prepareForm()">
          <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Register purchase & update stock
        </button>
      </div>

    </form>
  </div>
</div>

<script>
const products = [];

function updateSupplierInfo() {
  const select = document.getElementById('supplier-select');
  const opt = select.options[select.selectedIndex];
  document.getElementById('info-ruc').textContent = opt.dataset.ruc || '—';
  document.getElementById('info-contact').textContent = opt.dataset.contact || '—';
}

function addProduct() {
  const select = document.getElementById('product-select');
  const opt = select.options[select.selectedIndex];
  if (!opt.value) return;

  const id = parseInt(opt.value);
  if (products.find(p => p.id === id)) {
    alert('Product already added.');
    return;
  }

  products.push({
    id,
    name: opt.dataset.name,
    cost: parseFloat(opt.dataset.cost) || 0,
    quantity: 1,
  });

  renderTable();
  select.selectedIndex = 0;
}

function removeProduct(id) {
  const idx = products.findIndex(p => p.id === id);
  if (idx > -1) products.splice(idx, 1);
  renderTable();
}

function updateQty(id, val) {
  const p = products.find(p => p.id === id);
  if (p) p.quantity = parseInt(val) || 1;
  renderTotal();
}

function updateCost(id, val) {
  const p = products.find(p => p.id === id);
  if (p) p.cost = parseFloat(val) || 0;
  renderTable();
}

function renderTable() {
  const tbody = document.getElementById('products-tbody');
  const emptyRow = document.getElementById('empty-row');

  tbody.innerHTML = '';

  if (products.length === 0) {
    tbody.innerHTML = '<tr class="empty-row" id="empty-row"><td colspan="5">No products added yet</td></tr>';
    document.getElementById('grand-total').textContent = 'S/ 0.00';
    return;
  }

  let total = 0;
  products.forEach(p => {
    const subtotal = p.quantity * p.cost;
    total += subtotal;
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><span class="product-name">${p.name}</span></td>
      <td><input type="number" class="qty-input" value="${p.quantity}" min="1" onchange="updateQty(${p.id}, this.value)"></td>
      <td><input type="number" class="cost-input" value="${p.cost.toFixed(2)}" min="0" step="0.01" onchange="updateCost(${p.id}, this.value)"></td>
      <td><span class="subtotal">S/ ${subtotal.toFixed(2)}</span></td>
      <td><button type="button" class="btn-remove" onclick="removeProduct(${p.id})">
        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
      </button></td>
    `;
    tbody.appendChild(tr);
  });

  document.getElementById('grand-total').textContent = `S/ ${total.toFixed(2)}`;
}

function renderTotal() {
  let total = 0;
  products.forEach(p => total += p.quantity * p.cost);
  document.getElementById('grand-total').textContent = `S/ ${total.toFixed(2)}`;
}

function prepareForm() {
  if (!document.getElementById('supplier-select').value) {
    alert('Please select a supplier.');
    return false;
  }
  if (products.length === 0) {
    alert('Please add at least one product.');
    return false;
  }

  const container = document.getElementById('hidden-inputs');
  container.innerHTML = '';
  products.forEach((p, i) => {
    container.innerHTML += `
      <input type="hidden" name="products[${i}][product_id]" value="${p.id}">
      <input type="hidden" name="products[${i}][quantity]" value="${p.quantity}">
      <input type="hidden" name="products[${i}][unit_cost]" value="${p.cost}">
    `;
  });
  return true;
}
</script>

</body>
</html>