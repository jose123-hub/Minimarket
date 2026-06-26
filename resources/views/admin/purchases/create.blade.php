@push('portal-styles')
<style>
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

  .product-name { font-weight: 600; color: #111; }
  .qty-input, .cost-input { width: 80px; padding: 7px 10px; border: 1px solid #e0e0e0; border-radius: 6px; font-size: 13px; text-align: center; outline: none; }
  .qty-input:focus, .cost-input:focus { border-color: #e8192c; }
  .subtotal { font-weight: 700; color: #111; }
  .btn-remove { background: none; border: none; cursor: pointer; color: #ddd; padding: 4px; }
  .btn-remove:hover { color: #e8192c; }
  .btn-remove svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .total-row td { font-weight: 700; font-size: 14px; }
  .grand-total { color: #e8192c; font-size: 18px; }

  .form-actions { display: flex; justify-content: flex-end; gap: 12px; }
  .btn-save { padding: 12px 28px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; }
  .btn-save:hover { background: #c41525; }
  .btn-save svg { width: 16px; height: 16px; stroke: #fff; fill: none; stroke-width: 1.8; }
  .btn-cancel { padding: 12px 28px; background: #f5f5f5; color: #555; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; }
  .btn-cancel:hover { background: #eee; }
</style>
@endpush

<x-portal-layout
    title="New Purchase Order"
    subtitle="Register new order to supplier"
    active="purchases"
>
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
                  data-cost="{{ $product->cost ?? 0 }}"
                  data-category="{{ $product->category?->name ?? 'No category' }}">
                  {{ $product->name }} — {{ $product->category?->name ?? 'No category' }} — Cost: S/ {{ number_format($product->cost ?? 0, 2) }}
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
          Register purchase order
        </button>
      </div>

    </form>

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
          category: opt.dataset.category,
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
            <td>
            <span class="product-name">${p.name}</span>
            <div style="font-size:12px; color:#999; margin-top:2px;">${p.category}</div>
            </td>
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

</x-portal-layout>