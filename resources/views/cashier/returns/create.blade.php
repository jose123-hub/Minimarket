<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Express — New Return</title>
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
  .user-avatar { width: 34px; height: 34px; background: #e8192c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; }
  .user-info strong { font-size: 13px; color: #fff; display: block; }
  .user-info span { font-size: 11px; color: #666; }
  .logout-btn { margin-left: auto; background: none; border: none; cursor: pointer; color: #555; }
  .logout-btn:hover { color: #e8192c; }
  .logout-btn svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }

  .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }
  .topbar { background: #fff; padding: 18px 28px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; }
  .topbar-title h1 { font-size: 20px; font-weight: 800; color: #111; }
  .topbar-title p { font-size: 13px; color: #999; margin-top: 2px; }
  .content { padding: 24px 28px; flex: 1; max-width: 720px; }

  .card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; margin-bottom: 18px; }
  .card h3 { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 14px; }

  .search-row { display: flex; gap: 10px; }
  .search-row input { flex: 1; border: 1px solid #e5e5e5; background: #fafafa; border-radius: 9px; padding: 11px 14px; font-size: 13px; }
  .search-row input:focus { outline: none; border-color: #e8192c; background: #fff; }
  .btn { display: inline-flex; align-items: center; gap: 8px; border-radius: 9px; padding: 11px 18px; font-size: 13px; font-weight: 700; cursor: pointer; border: none; background: #111; color: #fff; }
  .btn-primary { background: #e8192c; }
  .btn-primary:disabled { background: #f3a8af; cursor: not-allowed; }
  .lookup-error { color: #dc2626; font-size: 13px; margin-top: 10px; display: none; }

  .sale-info { font-size: 13px; color: #666; margin-bottom: 14px; }
  .sale-info strong { color: #111; }

  table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
  th { font-size: 11px; text-transform: uppercase; color: #999; font-weight: 600; text-align: left; padding: 8px; border-bottom: 1px solid #f0f0f0; }
  td { font-size: 13px; color: #333; padding: 10px 8px; border-bottom: 1px solid #f9f9f9; }
  td input[type="number"] { width: 70px; border: 1px solid #e5e5e5; border-radius: 7px; padding: 6px 8px; font-size: 13px; }
  .returnable-hint { font-size: 11px; color: #999; }

  .form-group { margin-bottom: 16px; }
  .form-group label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; }
  .form-group textarea { width: 100%; border: 1px solid #e5e5e5; background: #fafafa; border-radius: 9px; padding: 10px 12px; font-size: 13px; font-family: inherit; }
  .field-error { color: #dc2626; font-size: 12px; margin-top: 4px; }

  .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 13px; }
</style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text">
      <strong>Express</strong>
      <span>Minimarket POS</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <a href="/cashier/dashboard" class="nav-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="/cashier/sales/create" class="nav-item">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      Sales (POS)
    </a>
    <a href="/cashier/inventory" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Inventory
    </a>
    <a href="/cashier/cash" class="nav-item">
      <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      Cash Register
    </a>
    <a href="/cashier/loyalty" class="nav-item">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Loyalty
    </a>
    <a href="/cashier/returns" class="nav-item active">
      <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
      Returns
    </a>
  </nav>

  <div class="sidebar-user">
    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="user-info">
      <strong>{{ Auth::user()->name }}</strong>
      <span>Cashier</span>
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
      <h1>New return request</h1>
      <p>Look up the original sale, then select what to return</p>
    </div>
  </div>

  <div class="content">

    @if($errors->any())
      <div class="flash-error">{{ $errors->first() }}</div>
    @endif

    <div class="card">
      <h3>1. Find the sale</h3>
      <div class="search-row">
        <input type="text" id="saleSearchInput" placeholder="Invoice number or sale ID">
        <button type="button" class="btn" id="saleSearchBtn">Search</button>
      </div>
      <p class="lookup-error" id="lookupError"></p>
    </div>

    <form id="returnForm" action="{{ route('cashier.returns.store') }}" method="POST" style="display:none;">
      @csrf
      <input type="hidden" name="sale_id" id="sale_id">

      <div class="card">
        <h3>2. Select products to return</h3>
        <p class="sale-info" id="saleInfoLine"></p>

        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Unit price</th>
              <th>Sold</th>
              <th>Return qty</th>
            </tr>
          </thead>
          <tbody id="returnItemsBody"></tbody>
        </table>
      </div>

      <div class="card">
        <h3>3. Reason for the return</h3>
        <div class="form-group" style="margin-bottom:0;">
          <textarea name="reason" id="reason" rows="3" placeholder="E.g. Customer reports product was defective" required></textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" id="submitReturnBtn" disabled>
        Submit return request
      </button>
      <p style="font-size:12px; color:#999; margin-top:10px;">
        This request will be sent to an administrator for approval. Stock is only restored once approved.
      </p>
    </form>

  </div>
</div>

<script>
  const saleSearchInput = document.getElementById('saleSearchInput');
  const saleSearchBtn = document.getElementById('saleSearchBtn');
  const lookupError = document.getElementById('lookupError');
  const returnForm = document.getElementById('returnForm');
  const saleIdInput = document.getElementById('sale_id');
  const saleInfoLine = document.getElementById('saleInfoLine');
  const returnItemsBody = document.getElementById('returnItemsBody');
  const submitReturnBtn = document.getElementById('submitReturnBtn');

  async function lookupSale() {
    const term = saleSearchInput.value.trim();
    if (!term) return;

    lookupError.style.display = 'none';
    saleSearchBtn.disabled = true;
    saleSearchBtn.textContent = 'Searching...';

    try {
      const res = await fetch(`/cashier/returns/sale-lookup/${encodeURIComponent(term)}`, {
        headers: { 'Accept': 'application/json' },
      });
      const data = await res.json();

      if (!res.ok) {
        lookupError.textContent = data.message ?? 'Sale not found.';
        lookupError.style.display = 'block';
        returnForm.style.display = 'none';
        return;
      }

      renderSale(data);
    } catch (err) {
      lookupError.textContent = 'Could not reach the server.';
      lookupError.style.display = 'block';
    } finally {
      saleSearchBtn.disabled = false;
      saleSearchBtn.textContent = 'Search';
    }
  }

  function renderSale(data) {
    saleIdInput.value = data.sale_id;
    saleInfoLine.innerHTML = `Sale <strong>${data.invoice_number ?? ('#' + data.sale_id)}</strong> — select the quantity to return for each product below.`;

    returnItemsBody.innerHTML = data.items.map((item, idx) => `
      <tr>
        <td>${item.product_name}</td>
        <td>S/ ${item.unit_price.toFixed(2)}</td>
        <td>${item.quantity_sold}</td>
        <td>
          <input type="number" min="0" max="${item.quantity_returnable}" value="0"
                 name="items[${idx}][quantity]"
                 data-product-id="${item.product_id}"
                 ${item.quantity_returnable === 0 ? 'disabled' : ''}>
          <input type="hidden" name="items[${idx}][product_id]" value="${item.product_id}">
          <div class="returnable-hint">${item.quantity_returnable} returnable</div>
        </td>
      </tr>
    `).join('');

    returnForm.style.display = 'block';
    updateSubmitState();

    returnItemsBody.querySelectorAll('input[type="number"]').forEach(input => {
      input.addEventListener('input', updateSubmitState);
    });
  }

  function updateSubmitState() {
    const quantities = Array.from(returnItemsBody.querySelectorAll('input[type="number"]'))
      .map(i => parseInt(i.value, 10) || 0);
    const hasAtLeastOne = quantities.some(q => q > 0);
    submitReturnBtn.disabled = !hasAtLeastOne;
  }

  returnForm.addEventListener('submit', (e) => {
    returnItemsBody.querySelectorAll('tr').forEach(row => {
      const qtyInput = row.querySelector('input[type="number"]');
      if (parseInt(qtyInput.value, 10) === 0) {
        row.querySelectorAll('input').forEach(i => i.disabled = true);
      }
    });
  });

  saleSearchBtn.addEventListener('click', lookupSale);
  saleSearchInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      lookupSale();
    }
  });
</script>

</body>
</html>