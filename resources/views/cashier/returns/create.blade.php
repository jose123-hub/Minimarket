@push('portal-styles')
<style>
  .content { max-width: 720px; }
  .card { background: #fff; border-radius: 12px; padding: 22px; border: 1px solid #eee; margin-bottom: 18px; }
  .card h3 { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 14px; }

  .search-row { display: flex; gap: 10px; }
  .search-row input { flex: 1; border: 1px solid #e5e5e5; background: #fafafa; border-radius: 9px; padding: 11px 14px; font-size: 13px; }
  .search-row input:focus { outline: none; border-color: #e8192c; background: #fff; }
  .lookup-error { color: #dc2626; font-size: 13px; margin-top: 10px; display: none; }

  .sale-info { font-size: 13px; color: #666; margin-bottom: 14px; }
  .sale-info strong { color: #111; }

  #returnForm table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
  #returnForm th { font-size: 11px; text-transform: uppercase; color: #999; font-weight: 600; text-align: left; padding: 8px; border-bottom: 1px solid #f0f0f0; }
  #returnForm td { font-size: 13px; color: #333; padding: 10px 8px; border-bottom: 1px solid #f9f9f9; }
  #returnForm td input[type="number"] { width: 70px; border: 1px solid #e5e5e5; border-radius: 7px; padding: 6px 8px; font-size: 13px; }
  .returnable-hint { font-size: 11px; color: #999; }

  #returnForm .form-group textarea { width: 100%; border: 1px solid #e5e5e5; background: #fafafa; border-radius: 9px; padding: 10px 12px; font-size: 13px; font-family: inherit; }
</style>
@endpush

<x-portal-layout
    title="New return request"
    subtitle="Look up the original sale, then select what to return"
    active="returns"
>
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

</x-portal-layout>