@push('portal-styles')
<style>
  .btn-sales-history-toggle {
    display: flex; align-items: center; gap: 7px; background: #fff; border: 1px solid #e5e5e5;
    border-radius: 9px; padding: 9px 14px; font-size: 13px; font-weight: 600; color: #333; cursor: pointer;
  }
  .btn-sales-history-toggle:hover { border-color: #ccc; }
  .btn-sales-history-toggle svg { width: 15px; height: 15px; stroke: #e8192c; fill: none; stroke-width: 2; }
  .history-count { background: #fff0f2; color: #e8192c; font-size: 11px; font-weight: 700; border-radius: 20px; padding: 1px 7px; }

  .history-overlay {
    display: none; position: fixed; inset: 0; background: rgba(17,17,17,0.45);
    z-index: 200; justify-content: flex-end;
  }
  .history-overlay.open { display: flex; }
  .history-panel { width: 380px; max-width: 90vw; background: #fff; height: 100%; padding: 22px 22px 0; overflow-y: auto; box-shadow: -8px 0 24px rgba(0,0,0,0.12); }
  .history-panel-header { display: flex; align-items: center; justify-content: space-between; }
  .history-panel-header h3 { display: flex; align-items: center; gap: 8px; font-size: 16px; font-weight: 800; color: #111; }
  .history-panel-header svg { width: 18px; height: 18px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .history-close { background: none; border: none; cursor: pointer; color: #999; }
  .history-close svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; }
  .history-subtitle { font-size: 12px; color: #999; margin: 6px 0 16px; line-height: 1.5; }
  .history-list { display: flex; flex-direction: column; gap: 8px; padding-bottom: 20px; }
  .history-item { border: 1px solid #f0f0f0; border-radius: 10px; padding: 12px 14px; position: relative; }
  .history-item.is-top { border-color: #fecaca; background: #fff8f8; }
  .history-item .top-tag { position: absolute; top: -8px; right: 10px; background: #e8192c; color: #fff; font-size: 10px; font-weight: 700; padding: 1px 8px; border-radius: 20px; }
  .history-item-row { display: flex; justify-content: space-between; align-items: baseline; }
  .history-invoice { font-family: monospace; font-weight: 700; font-size: 13px; color: #111; }
  .history-total { font-weight: 800; font-size: 14px; color: #16a34a; }
  .history-meta { font-size: 11px; color: #999; margin-top: 4px; }
  .history-empty { text-align: center; color: #bbb; font-size: 13px; padding: 40px 0; }

  .pos-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: -6px 0 16px; }
  .pos-toolbar .search-box { width: 360px; flex: none; max-width: 100%; }

  .pos-layout { display: grid; grid-template-columns: 1fr 340px; gap: 0; margin: 0 -28px -24px; height: calc(100vh - 65px - 76px); }

  .products-panel { padding: 20px 24px; overflow-y: auto; }
  .category-area {
    width: 100%;
    min-width: 0;
    margin-bottom: 18px;
  }

  .category-row {
    display: flex;
    gap: 10px;
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    max-width: 100%;
    padding-bottom: 8px;
    scrollbar-width: thin;
  }

  .main-category-row {
    margin-bottom: 8px;
  }

  .subcategory-row {
    display: none;
    scroll-behavior: smooth;
  }

  .subcategory-row.show {
    display: flex;
  }

  .filter-btn,
  .subcategory-btn {
    white-space: nowrap;
    flex: 0 0 auto;
    padding: 8px 17px;
    border-radius: 999px;
    border: 1px solid #e0e0e0;
    background: #fff;
    font-size: 13px;
    font-weight: 600;
    color: #555;
    cursor: pointer;
    transition: all 0.15s;
  }

  .filter-btn:hover,
  .subcategory-btn:hover {
    border-color: #e8192c;
    color: #e8192c;
  }

  .filter-btn.active,
  .subcategory-btn.active {
    background: #e8192c;
    color: #fff;
    border-color: #e8192c;
  }

  .category-row::-webkit-scrollbar {
    height: 5px;
  }

  .category-row::-webkit-scrollbar-thumb {
    background: #ddd;
    border-radius: 999px;
  }

  .category-row::-webkit-scrollbar-track {
    background: transparent;
  }

  .stars-preview-row {
    color: #d97706;
    font-weight: 900;
    margin-top: 8px;
  }

  .stars-preview-row strong {
    color: #d97706;
  }

  .stars-progress-text {
    font-size: 11px;
    color: #999;
    margin-bottom: 10px;
  }

  .price-search-bar { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; }
  .price-search-bar input[type="number"] {
    width: 100px; border: 1px solid #e5e5e5; border-radius: 8px; padding: 8px 10px; font-size: 13px;
  }
  .price-search-sep { color: #bbb; font-size: 13px; }
  .price-search-bar button {
    border: 1px solid #e5e5e5; background: #fff; border-radius: 8px; padding: 8px 14px;
    font-size: 13px; font-weight: 600; color: #333; cursor: pointer;
  }
  .price-search-bar button#bst-search-btn { background: #111; color: #fff; border-color: #111; }
  .price-search-bar button#bst-search-btn:hover { background: #e8192c; border-color: #e8192c; }
  .price-search-bar button:hover:not(#bst-search-btn) { border-color: #ccc; }
  #bst-status { font-size: 12px; color: #999; }
  .filter-btn { padding: 7px 16px; border-radius: 100px; border: 1px solid #e0e0e0; background: #fff; font-size: 13px; font-weight: 500; color: #555; cursor: pointer; transition: all 0.15s; }
  .filter-btn:hover { border-color: #e8192c; color: #e8192c; }
  .filter-btn.active { background: #e8192c; color: #fff; border-color: #e8192c; }

  .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
  .product-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; cursor: pointer; transition: all 0.15s; }
  .product-card:hover { border-color: #e8192c; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(232,25,44,0.1); }
  .product-img {
    height: 140px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    font-weight: 800;
    color: #ddd;
    overflow: hidden;
  }

  .product-img-real {
    padding: 0;
  }

  .product-img-real img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  
  .payment-method-box {
    padding: 14px 20px;
    border-bottom: 1px solid #eee;
  }

  .payment-method-box label {
    font-size: 12px;
    color: #999;
    display: block;
    margin-bottom: 8px;
  }

  .payment-options {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
  }

  .payment-option {
    border: 1px solid #e5e5e5;
    background: #fff;
    border-radius: 9px;
    padding: 9px 8px;
    font-size: 12px;
    font-weight: 800;
    color: #555;
    cursor: pointer;
  }

  .payment-option.active {
    background: #e8192c;
    border-color: #e8192c;
    color: #fff;
  }

  .payment-extra-box {
    padding: 0 20px 14px;
    border-bottom: 1px solid #eee;
  }
 
  .payment-extra-box label {
    font-size: 12px;
    color: #999;
    display: block;
    margin-bottom: 6px;
  }

  .payment-extra-box input {
    width: 100%;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 13px;
    outline: none;
  }

  .payment-extra-box input:focus {
    border-color: #e8192c;
  }

  .payment-extra-box small {
    display: block;
    margin-top: 6px;
    font-size: 11px;
    color: #999;
  }

  .promo-discount-row {
    color: #16a34a;
  }

  .promo-discount-row strong {
    color: #16a34a;
  }

  .product-info { padding: 12px; }
  .product-category { font-size: 11px; color: #999; margin-bottom: 4px; }
  .product-name { font-size: 14px; font-weight: 600; color: #111; margin-bottom: 8px; }
  .product-footer { display: flex; justify-content: space-between; align-items: center; }
  .product-price { font-size: 15px; font-weight: 700; color: #e8192c; }
  .product-stock { font-size: 11px; color: #aaa; }
  .product-card.out-of-stock { opacity: 0.5; cursor: not-allowed; }

  .cart-panel { background: #fff; border-left: 1px solid #eee; display: flex; flex-direction: column; }
  .cart-header { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
  .cart-header h3 { font-size: 16px; font-weight: 700; color: #111; display: flex; align-items: center; gap: 8px; }
  .cart-header h3 svg { width: 18px; height: 18px; stroke: #111; fill: none; stroke-width: 1.8; }
  .cart-count { font-size: 12px; color: #999; }

  .cart-customer { padding: 14px 20px; border-bottom: 1px solid #eee; }
  .cart-customer label { font-size: 12px; color: #999; display: block; margin-bottom: 6px; }
  .cart-customer select { width: 100%; border: 1px solid #e0e0e0; border-radius: 8px; padding: 9px 12px; font-size: 13px; color: #333; outline: none; }
  .cart-customer select:focus { border-color: #e8192c; }

  .cart-items { flex: 1; overflow-y: auto; padding: 12px 20px; }
  .cart-empty { text-align: center; color: #bbb; font-size: 13px; padding: 40px 0; }
  .cart-empty svg { width: 40px; height: 40px; stroke: #ddd; fill: none; stroke-width: 1.5; display: block; margin: 0 auto 12px; }

  .cart-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
  .cart-item-avatar { width: 36px; height: 36px; background: #f5f5f5; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: #bbb; flex-shrink: 0; }
  .cart-item-info { flex: 1; }
  .cart-item-name { font-size: 13px; font-weight: 600; color: #111; }
  .cart-item-price { font-size: 12px; color: #999; }
  .cart-item-controls { display: flex; align-items: center; gap: 6px; }
  .qty-btn { width: 24px; height: 24px; border: 1px solid #e0e0e0; border-radius: 6px; background: #fff; cursor: pointer; font-size: 14px; display: flex; align-items: center; justify-content: center; color: #555; transition: all 0.15s; }
  .qty-btn:hover { border-color: #e8192c; color: #e8192c; }
  .qty-num { font-size: 13px; font-weight: 600; color: #111; min-width: 20px; text-align: center; }
  .cart-item-subtotal { font-size: 13px; font-weight: 700; color: #111; min-width: 56px; text-align: right; }
  .delete-btn { background: none; border: none; cursor: pointer; color: #ccc; padding: 4px; }
  .delete-btn:hover { color: #e8192c; }
  .delete-btn svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 1.8; }

  .cart-footer { padding: 16px 20px; border-top: 1px solid #eee; }
  .cart-total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
  .cart-total-row span { font-size: 13px; color: #999; }
  .cart-total-row strong { font-size: 13px; color: #333; }
  .cart-grand-total { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-top: 10px; border-top: 1px solid #eee; }
  .cart-grand-total span { font-size: 15px; font-weight: 700; color: #111; }
  .cart-grand-total strong { font-size: 20px; font-weight: 800; color: #111; }
  .btn-checkout { width: 100%; padding: 14px; background: #e8192c; color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  .btn-checkout:hover { background: #c41525; }
  .btn-checkout:disabled { background: #ddd; color: #aaa; cursor: not-allowed; }
  .btn-clear { width: 100%; padding: 10px; background: transparent; color: #999; border: 1px solid #e0e0e0; border-radius: 10px; font-size: 13px; font-weight: 500; cursor: pointer; margin-top: 8px; transition: all 0.15s; }
  .btn-clear:hover { border-color: #e8192c; color: #e8192c; }

  .success-msg { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin: 12px 20px 0; }
  .error-msg { background: #fff0f0; border: 1px solid #fcc; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #c00; margin: 12px 20px 0; }
   
  .pos-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(17,17,17,0.45);
    z-index: 400;
    align-items: center;
    justify-content: center;
    padding: 18px;
  }

  .pos-modal-overlay.open {
    display: flex;
  }

  .pos-modal {
    width: 480px;
    max-width: 95vw;
    max-height: 92vh;
    overflow-y: auto;
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 18px 50px rgba(0,0,0,0.18);
  }

  .pos-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
  }

  .pos-modal-title {
    font-size: 17px;
    font-weight: 900;
    color: #111;
  }

  .pos-modal-close {
    border: none;
    background: transparent;
    cursor: pointer;
    color: #777;
    font-size: 24px;
    line-height: 1;
  }

  .receipt-paper {
    border: 1px dashed #d4d4d4;
    border-radius: 10px;
    padding: 18px;
    font-family: monospace;
    font-size: 12px;
    color: #111;
    background: #fff;
  }

  .receipt-center {
    text-align: center;
  }

  .receipt-title {
    font-size: 16px;
    font-weight: 900;
    letter-spacing: 2px;
  }

  .receipt-line {
    border-top: 1px dashed #d4d4d4;
    margin: 12px 0;
  }

  .receipt-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 7px;
  }

  .receipt-row span:first-child {
    color: #555;
  }

  .receipt-table-header,
  .receipt-product-row {
    display: grid;
    grid-template-columns: 42px 1fr 78px;
    gap: 8px;
    margin-bottom: 6px;
  }

  .receipt-table-header {
    font-weight: 900;
  }

  .receipt-table-header span:last-child,
  .receipt-product-row span:last-child {
    text-align: right;
  }

  .receipt-total {
    font-size: 15px;
    font-weight: 900;
  }

  .receipt-success-note {
    margin-top: 12px;
    padding: 10px;
    border-radius: 8px;
    background: #f0fdf4;
    color: #15803d;
    font-size: 12px;
    font-weight: 800;
    text-align: center;
  }

  .pos-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 16px;
  } 

  .btn-modal-cancel,
  .btn-modal-confirm {
    border-radius: 9px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
  }

  .btn-modal-cancel {
    background: #fff;
    border: 1px solid #e5e5e5;
    color: #333;
  }

  .btn-modal-confirm {
    background: #e8192c;
    border: 1px solid #e8192c;
    color: #fff;
  }
  .hidden { display: none; }
</style>
@endpush

<x-portal-layout
    title="Point of Sale"
    subtitle="Register sales quickly"
    active="sales"
>

    <div class="pos-toolbar">
    <div class="search-box">
        <svg viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>

        <input type="text" id="search-input" placeholder="Search product by name...">
    </div>

    <button type="button" id="btn-sales-history" class="btn-sales-history-toggle">
        <svg viewBox="0 0 24 24">
            <path d="M3 3v18h18"/>
            <path d="M18.7 8l-5.1 5.2-3-3L7 13.5"/>
        </svg>
        See latest sales
        <span class="history-count" id="history-count">0</span>
     </button>
    </div>

  <div class="pos-layout">
    <div class="products-panel">

      @if(session('success'))
        <div class="success-msg" style="margin: 0 0 16px">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="error-msg" style="margin: 0 0 16px">{{ session('error') }}</div>
      @endif

      <div class="category-area">
    <div class="category-row main-category-row">
        <button class="filter-btn active" onclick="selectMainCategory('all', this)">
            All
        </button>

        @foreach($mainCategories as $main)
            <button class="filter-btn" onclick="selectMainCategory('{{ $main->id }}', this)">
                {{ $main->name }}
            </button>
        @endforeach
    </div>

    <div class="category-row subcategory-row" id="subcategory-row">
        @foreach($mainCategories as $main)
            @foreach($main->children as $child)
                <button class="subcategory-btn"
                        data-parent="{{ $main->id }}"
                        onclick="selectSubcategory('{{ $child->id }}', this)"
                        style="display:none;">
                    {{ $child->name }}
                </button>
            @endforeach
        @endforeach
       </div>
     </div>

      <div class="price-search-bar">
        <input type="number" id="bst-min" placeholder="Min price" step="0.01" min="0">
        <span class="price-search-sep">—</span>
        <input type="number" id="bst-max" placeholder="Max price" step="0.01" min="0">
        <button type="button" id="bst-search-btn">Search range</button>
        <button type="button" id="bst-clear-btn">Clear</button>
        <span id="bst-status"></span>
      </div>

      <div class="products-grid" id="products-grid">
        @foreach($products as $product)
        @php
          $activeDiscount = $product->activeDiscount();
          $finalPrice = $product->finalPrice();
          $productCategory = $product->category;
          $parentCategoryId = $productCategory?->parent_id ?? $productCategory?->id;
        @endphp
        <div class="product-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}"
             data-id="{{ $product->id }}"
             data-name="{{ strtolower($product->name) }}"
             data-price="{{ $finalPrice }}"
             data-original-price="{{ $product->price }}"
             data-discount="{{ $activeDiscount?->value ?? 0 }}"
             data-stock="{{ $product->stock }}"
             data-category="{{ $product->category_id }}"
             data-parent-category="{{ $parentCategoryId }}"
             @if($product->stock > 0) onclick="addToCart(this)" @endif>
          @if($product->image)
            <div class="product-img product-img-real">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
           </div>
          @else
          <div class="product-img">
           {{ strtoupper(substr($product->name, 0, 1)) }}
          </div>
          @endif
          <div class="product-info">
            <div class="product-category">{{ $product->category?->name }}</div>
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-footer">
              @if($activeDiscount)
             <span style="text-decoration: line-through; color:#999; font-size:12px;">
               S/ {{ number_format($product->price, 2) }}
             </span>
               <span class="product-price">
                 S/ {{ number_format($finalPrice, 2) }}
               </span>
                  <span style="font-size:11px; color:#16a34a; font-weight:700;">
                 -{{ number_format($activeDiscount->value, 0) }}%
               </span>
              @else
               <span class="product-price">
                S/ {{ number_format($product->price, 2) }}
               </span>
              @endif
              <span class="product-stock">{{ $product->stock }} u.</span>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <div class="cart-panel">
      <div class="cart-header">
        <h3>
          <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
          Cart
        </h3>
        <span class="cart-count" id="cart-count">0 products</span>
      </div>

      <div class="cart-customer">
        <label>Customer</label>

      <input type="text"
         id="customer-search"
         placeholder="Search customer by name or email..."
         style="width:100%; border:1px solid #e0e0e0; border-radius:8px; padding:9px 12px; font-size:13px; margin-bottom:8px; outline:none;">

         <select id="customer-select">
         <option value="">— Select customer —</option>
        @foreach($customers as $customer)
        @php
          $clientData = $clientsByUserId[$customer->id] ?? null;
          $isGeneric = strtolower($customer->email) === 'cliente@example.com';
        @endphp
    <option value="{{ $customer->id }}"
            data-search="{{ strtolower($customer->name . ' ' . $customer->email) }}"
            data-generic="{{ $isGeneric ? '1' : '0' }}"
            data-progress="{{ $clientData?->star_progress_amount ?? 0 }}">
        {{ $customer->name }}
        {{ $isGeneric ? '(Generic)' : '' }}
    </option>
   @endforeach
</select>
      </div>

      <div class="payment-method-box">
    <label>Payment method</label>

    <div class="payment-options">
        <button type="button" class="payment-option active" data-method="cash" onclick="selectPaymentMethod(this)">
            Cash
        </button>

        <button type="button" class="payment-option" data-method="card" onclick="selectPaymentMethod(this)">
            Card
        </button>

        <button type="button" class="payment-option" data-method="yape" onclick="selectPaymentMethod(this)">
            Yape
        </button>

        <button type="button" class="payment-option" data-method="plin" onclick="selectPaymentMethod(this)">
            Plin
        </button>
    </div>
</div>

<div class="payment-extra-box">
    <div id="cash-fields">
        <label>Cash received</label>

        <input type="number"
               id="cash-received"
               placeholder="Amount received"
               step="0.01"
               min="0">

        <small id="cash-change-preview">
            Change: S/ 0.00
        </small>
    </div>

    <div id="reference-fields" style="display:none;">
        <label id="payment-reference-label">Payment code</label>

        <input type="text"
               id="payment-reference"
               placeholder="Enter payment code">

        <small id="payment-reference-help">
            Enter the operation code or voucher number.
        </small>
    </div>

    <div id="promo-fields" style="display:none; margin-top:10px;">
        <label>Promo code</label>

        <input type="text"
               id="promo-code"
               placeholder="Example: YAPE10 or PLIN5">

        <small id="promo-code-help">
            Optional promotional code for digital payment.
        </small>
     </div>
    </div>

      <div class="cart-items" id="cart-items">
        <div class="cart-empty" id="cart-empty">
          <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
          No products added yet
        </div>
      </div>

      <div class="cart-footer">
        <div class="cart-total-row">
         <span>Subtotal</span>
         <strong id="subtotal">S/ 0.00</strong>
        </div>

        <div class="cart-total-row promo-discount-row" id="promo-discount-row" style="display:none;">
         <span id="promo-discount-label">Promo discount</span>
         <strong id="promo-discount">-S/ 0.00</strong>
        </div>

        <div class="cart-total-row stars-preview-row">
         <span>Stars to earn</span>
         <strong id="stars-preview">No stars</strong>
        </div>

        <div class="stars-progress-text" id="stars-progress-preview">
         Select a registered customer to earn stars
        </div>

        <div class="cart-grand-total">
         <span>Total</span>
         <strong id="total">S/ 0.00</strong>
        </div>
        <button class="btn-checkout" id="btn-checkout" onclick="submitSale()" disabled>
          Register Sale
        </button>
        <button class="btn-clear" onclick="clearCart()">Clear cart</button>
      </div>
    </div>

  </div>
</div>

<div class="history-overlay" id="history-overlay">
  <div class="history-panel">
    <div class="history-panel-header">
      <h3>
        <svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="4" rx="1"/><rect x="4" y="10" width="16" height="4" rx="1"/><rect x="4" y="16" width="16" height="4" rx="1"/></svg>
        Latest sales
      </h3>
      <button type="button" id="close-history" class="history-close">
        <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <p class="history-subtitle">The most recent sale will appear first</p>
    <div class="history-list" id="history-list"></div>
  </div>
</div>

<form id="sale-form" method="POST" action="{{ route('sales.store') }}" style="display:none">
  @csrf
  <input type="hidden" name="customer_id" id="form-customer">
  <input type="hidden" name="payment_method" id="form-payment-method" value="cash">
  <input type="hidden" name="payment_reference" id="form-payment-reference">
  <input type="hidden" name="promo_code" id="form-promo-code">
  <input type="hidden" name="cash_received" id="form-cash-received">

  <input type="hidden" name="order_id" value="{{ request('order_id') }}">
  <div id="form-products"></div>
</form>

@if(!empty($receiptSale))
    @php
        $receiptSubtotal = $receiptSale->total + ($receiptSale->discount ?? 0);

        $methodLabel = match($receiptSale->payment_method) {
            'cash' => 'Cash',
            'card' => 'Card',
            'yape' => 'Yape',
            'plin' => 'Plin',
            default => ucfirst($receiptSale->payment_method ?? 'Cash'),
        };

        $isGenericCustomer = strtolower($receiptSale->customer?->email ?? '') === 'cliente@example.com';

        $receiptNumber = $receiptSale->invoice_number
            ?? 'B-' . str_pad($receiptSale->id, 6, '0', STR_PAD_LEFT);
    @endphp

    <div class="pos-modal-overlay open" id="receipt-modal">
        <div class="pos-modal">
            <div class="pos-modal-header">
                <div class="pos-modal-title">
                    🧾 Ticket generated
                </div>

                <button type="button" class="pos-modal-close" onclick="closeReceiptModal()">
                    ×
                </button>
            </div>

            <div class="receipt-paper" id="receipt-print-area">
                <div class="receipt-center">
                    <div class="receipt-title">MINIMARKET EXPRESS</div>
                    <div>RUC: 20512345678</div>
                    <div>Av. Los Próceres 123 - Lima</div>
                    <div>Electronic Ticket</div>
                    <strong>{{ $receiptNumber }}</strong>
                </div>

                <div class="receipt-line"></div>

                <div class="receipt-row">
                    <span>Date:</span>
                    <span>{{ $receiptSale->created_at->format('d/m/Y h:i A') }}</span>
                </div>

                <div class="receipt-row">
                    <span>Client:</span>
                    <span>
                        @if($isGenericCustomer)
                            Generic client
                        @else
                            {{ $receiptSale->customer?->name ?? 'Cliente' }}
                        @endif
                    </span>
                </div>

                <div class="receipt-row">
                    <span>Method:</span>
                    <span>{{ $methodLabel }}</span>
                </div>

                @if($receiptSale->payment_method === 'cash')
                    <div class="receipt-row">
                        <span>Got it:</span>
                        <span>S/ {{ number_format($receiptSale->cash_received ?? 0, 2) }}</span>
                    </div>

                    <div class="receipt-row">
                        <span>Change:</span>
                        <span>S/ {{ number_format($receiptSale->cash_change ?? 0, 2) }}</span>
                    </div>
                @else
                    <div class="receipt-row">
                        <span>Operation code:</span>
                        <span>{{ $receiptSale->payment_reference ?? '-' }}</span>
                    </div>
                @endif

                @if($receiptSale->promo_code)
                    <div class="receipt-row">
                        <span>Promo code:</span>
                        <span>{{ $receiptSale->promo_code }}</span>
                    </div>
                @endif

                <div class="receipt-line"></div>

                <div class="receipt-table-header">
                    <span>Units</span>
                    <span>Product</span>
                    <span>Amount</span>
                </div>

                @foreach($receiptSale->details as $detail)
                    <div class="receipt-product-row">
                        <span>{{ $detail->quantity }}</span>
                        <span>{{ $detail->product?->name ?? 'Product removed' }}</span>
                        <span>S/ {{ number_format($detail->subtotal, 2) }}</span>
                    </div>
                @endforeach

                <div class="receipt-line"></div>

                <div class="receipt-row">
                    <span>Subtotal:</span>
                    <span>S/ {{ number_format($receiptSubtotal, 2) }}</span>
                </div>

                @if(($receiptSale->discount ?? 0) > 0)
                    <div class="receipt-row">
                        <span>Promo discount:</span>
                        <span>-S/ {{ number_format($receiptSale->discount, 2) }}</span>
                    </div>
                @endif

                <div class="receipt-row">
                    <span>Stars earned:</span>
                    <span>
                        @if($isGenericCustomer)
                            Not applicable
                        @else
                            +{{ $receiptSale->stars_earned ?? 0 }} ⭐
                        @endif
                    </span>
                </div>

                <div class="receipt-line"></div>

                <div class="receipt-row receipt-total">
                    <span>TOTAL:</span>
                    <span>S/ {{ number_format($receiptSale->total, 2) }}</span>
                </div>

                <div class="receipt-line"></div>

                <div class="receipt-center">
                    <div>Thanks for your purchase!</div>
                    <small>Keep this receipt as proof.</small>
                </div>
            </div>

            <div class="receipt-success-note">
                Sale registered successfully.
            </div>

            <div class="pos-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeReceiptModal()">
                    Close
                </button>

                <button type="button" class="btn-modal-confirm" onclick="printReceipt()">
                    Print
                </button>
            </div>
        </div>
    </div>
@endif

<script id="products-price-data" type="application/json">{!! json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float) $p->price]), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>

<script>
class BSTNode {
  constructor(product) {
    this.product = product;
    this.left = null;
    this.right = null;
  }
}

class PriceBST {
  constructor() {
    this.root = null;
    this.comparisons = 0;
  }

  insert(product) {
    this.root = this.insertNode(this.root, product);
  }

  insertNode(node, product) {
    if (node === null) {
      return new BSTNode(product);
    }
    if (product.price < node.product.price) {
      node.left = this.insertNode(node.left, product);
    } else {
      node.right = this.insertNode(node.right, product);
    }
    return node;
  }

  rangeSearch(min, max) {
    this.comparisons = 0;
    const result = [];
    this.rangeSearchNode(this.root, min, max, result);
    return result;
  }

  rangeSearchNode(node, min, max, result) {
    if (node === null) return;

    this.comparisons++;
    if (min < node.product.price) {
      this.rangeSearchNode(node.left, min, max, result);
    }

    this.comparisons++;
    if (node.product.price >= min && node.product.price <= max) {
      result.push(node.product);
    }

    this.comparisons++;
    if (max > node.product.price) {
      this.rangeSearchNode(node.right, min, max, result);
    }
  }
}

const productsForBST = JSON.parse(document.getElementById('products-price-data').textContent || '[]');
const priceBST = new PriceBST();
productsForBST.forEach(p => priceBST.insert(p));
</script>

<script>
class Node {
  constructor(data) {
    this.data = data;
    this.next = null;
  }
}

class LinkedList {
  constructor() {
    this.head = null;
    this.size = 0;
  }

  insert(product) {
    let current = this.head;
    while (current) {
      if (current.data.id === product.id) {
        if (current.data.quantity < current.data.stock) {
          current.data.quantity++;
        }
        return;
      }
      current = current.next;
    }
    const node = new Node({ ...product, quantity: 1 });
    node.next = this.head;
    this.head = node;
    this.size++;
  }

  remove(id) {
    if (!this.head) return;
    if (this.head.data.id === id) {
      this.head = this.head.next;
      this.size--;
      return;
    }
    let current = this.head;
    while (current.next) {
      if (current.next.data.id === id) {
        current.next = current.next.next;
        this.size--;
        return;
      }
      current = current.next;
    }
  }

  updateQty(id, delta) {
  let cur = this.head;
  let prev = null;
  while (cur) {
    if (cur.data.id === id) {
      const newQty = cur.data.quantity + delta;

      if (newQty <= 0) {
        if (prev === null) {
          this.head = cur.next;
        } else {
          prev.next = cur.next;
        }
        this.size--;
        return;
      }

      if (newQty > cur.data.stock) return;
      cur.data.quantity = newQty;
      return;
    }
    prev = cur;
    cur = cur.next;
  }
  }

  toArray() {
    const arr = [];
    let current = this.head;
    while (current) {
      arr.push(current.data);
      current = current.next;
    }
    return arr;
  }

  clear() {
    this.head = null;
    this.size = 0;
  }
}

class Stack {
  constructor() {
    this.items = {};
    this.top = -1;
  }

  push(action) {
    this.top++;
    this.items[this.top] = action;
  }

  pop() {
  if (this.top === -1) return null;
  const item = this.items[this.top];
  delete this.items[this.top];
  this.top--;
  return item;
  }

  peek() {
    return this.items[this.top];
  }

  isEmpty() {
    return this.top === -1;
  }

  size() {
    return this.top + 1;
  }
}

const actionHistory = new Stack();
const cart = new LinkedList();
let selectedPaymentMethod = 'cash';

function selectPaymentMethod(btn) {
  selectedPaymentMethod = btn.dataset.method;

  document.querySelectorAll('.payment-option').forEach(button => {
    button.classList.remove('active');
  });

  btn.classList.add('active');

  renderPaymentFields();
  renderCart();
}
</script>
<script id="order-items-data" type="application/json">{!! json_encode($orderItems->map(fn($item) => [
  'id' => $item->product_id,
  'name' => $item->product->name,
  'price' => $item->price,
  'stock' => $item->product->stock,
  'quantity' => $item->quantity,
]) ?? []) !!}</script>
<script id="attending-order-id-data" type="application/json">{!! json_encode(request('order_id')) !!}</script>
<script>
const orderItemsData = JSON.parse(document.getElementById('order-items-data').textContent || '[]');
orderItemsData.forEach(item => {
  cart.insert({
    id: item.id,
    name: item.name,
    price: item.price,
    stock: item.stock,
    quantity: item.quantity,
  });
});
if (orderItemsData.length > 0) renderCart();

const attendingOrderId = JSON.parse(document.getElementById('attending-order-id-data').textContent || 'null');

const salesHistoryStack = new Stack();
</script>
<script id="recent-sales-data" type="application/json">{!! json_encode($recentSales ?? []) !!}</script>
<script>
const serverRecentSales = JSON.parse(document.getElementById('recent-sales-data').textContent || '[]');
serverRecentSales.forEach(sale => salesHistoryStack.push(sale));
updateHistoryCount();

function updateHistoryCount() {
  const el = document.getElementById('history-count');
  if (el) el.textContent = salesHistoryStack.size();
}

function getSalesHistoryLIFO() {
  const drained = [];
  while (!salesHistoryStack.isEmpty()) {
    drained.push(salesHistoryStack.pop());
  }
  for (let i = drained.length - 1; i >= 0; i--) {
    salesHistoryStack.push(drained[i]);
  }
  return drained; 
}

function renderSalesHistory() {
  const list = document.getElementById('history-list');
  const sales = getSalesHistoryLIFO();

  if (sales.length === 0) {
    list.innerHTML = '<div class="history-empty">No sales registered yet today</div>';
    return;
  }

  list.innerHTML = sales.map((sale, i) => `
    <div class="history-item ${i === 0 ? 'is-top' : ''}">
      ${i === 0 ? '<span class="top-tag">TOP — last popped</span>' : ''}
      <div class="history-item-row">
        <span class="history-invoice">${sale.invoice_number}</span>
        <span class="history-total">S/ ${parseFloat(sale.total).toFixed(2)}</span>
      </div>
      <div class="history-meta">${sale.items} item(s) · ${sale.time}</div>
    </div>
  `).join('');
}

document.getElementById('btn-sales-history').addEventListener('click', () => {
  renderSalesHistory();
  document.getElementById('history-overlay').classList.add('open');
});
document.getElementById('close-history').addEventListener('click', () => {
  document.getElementById('history-overlay').classList.remove('open');
});
document.getElementById('history-overlay').addEventListener('click', (e) => {
  if (e.target.id === 'history-overlay') e.target.classList.remove('open');
});

function addToCart(el) {
  const product = {
    id: parseInt(el.dataset.id),
    name: el.dataset.name,
    price: parseFloat(el.dataset.price),
    stock: parseInt(el.dataset.stock),
  };
  actionHistory.push({
    type: 'add',
    product: { ...product }
  });

  cart.insert(product);
  renderCart();
  updateUndoBtn();
}

function changeQty(id, delta) {
  actionHistory.push({ type: 'qty', id, delta });
  cart.updateQty(id, delta);
  renderCart();
  updateUndoBtn();
}

function removeItem(id) {
  const items = cart.toArray();
  const product = items.find(p => p.id === id);
  if (product) {
    actionHistory.push({ type: 'remove', product: { ...product } });
  }
  cart.remove(id);
  renderCart();
  updateUndoBtn();
}

//

function clearCart() {
  cart.clear();
  actionHistory.items = {};
  actionHistory.top = -1;
  renderCart();
  updateUndoBtn();
}

function getSelectedCustomerOption() {
  const select = document.getElementById('customer-select');
  return select.options[select.selectedIndex];
}

function isGenericCustomerSelected() {
  const option = getSelectedCustomerOption();
  return option && option.dataset.generic === '1';
}

function getSelectedCustomerProgress() {
  const option = getSelectedCustomerOption();
  return parseFloat(option?.dataset.progress || 0);
}

function updateStarsPreview(total) {
  const starsPreview = document.getElementById('stars-preview');
  const progressPreview = document.getElementById('stars-progress-preview');

  if (!starsPreview || !progressPreview) {
    return;
  }

  const customerId = document.getElementById('customer-select').value;

  if (!customerId) {
    starsPreview.textContent = 'No stars';
    progressPreview.textContent = 'Select a registered customer to earn stars';
    return;
  }

  if (isGenericCustomerSelected()) {
    starsPreview.textContent = 'No stars';
    progressPreview.textContent = 'Generic customer does not earn stars';
    return;
  }

  const previousProgress = getSelectedCustomerProgress();

  const previousProgressCents = Math.round(previousProgress * 100);
  const totalCents = Math.round(total * 100);
  const starBaseCents = previousProgressCents + totalCents;

  const starsEarned = Math.floor(starBaseCents / 500);
  const newProgressCents = starBaseCents % 500;
  const newProgressAmount = newProgressCents / 100;

  starsPreview.textContent = `+${starsEarned} ⭐`;
  progressPreview.textContent = `Progress after sale: S/ ${newProgressAmount.toFixed(2)} / S/ 5.00`;
}

function getPaymentMethodLabel(method) {
  const labels = {
    cash: 'Cash',
    card: 'Card',
    yape: 'Yape',
    plin: 'Plin'
  };

  return labels[method] || method;
}

function getCartSubtotal() {
  return cart.toArray().reduce((sum, item) => {
    return sum + (item.price * item.quantity);
  }, 0);
}

function getPromoCode() {
  return document.getElementById('promo-code')?.value.trim().toUpperCase() || '';
}

function getPromoDiscountRate() {
  const code = getPromoCode();

  if (selectedPaymentMethod === 'yape' && code === 'YAPE10') {
    return 0.10;
  }

  if (selectedPaymentMethod === 'plin' && code === 'PLIN5') {
    return 0.05;
  }

  return 0;
}

function getRoundingAdjustment() {
  if (selectedPaymentMethod !== 'cash') {
    return 0;
  }

  const beforeRounding = getTotalBeforeRounding();
  const roundedTotal = Math.round(beforeRounding * 10) / 10;

  return roundedTotal - beforeRounding;
}

function getPromoDiscount() {
  return getCartSubtotal() * getPromoDiscountRate();
}

function getCartTotal() {
  return getCartSubtotal() - getPromoDiscount();
}

function renderPaymentFields() {
  const cashFields = document.getElementById('cash-fields');
  const referenceFields = document.getElementById('reference-fields');
  const promoFields = document.getElementById('promo-fields');

  const referenceLabel = document.getElementById('payment-reference-label');
  const referenceInput = document.getElementById('payment-reference');
  const referenceHelp = document.getElementById('payment-reference-help');

  if (selectedPaymentMethod === 'cash') {
    cashFields.style.display = 'block';
    referenceFields.style.display = 'none';
    promoFields.style.display = 'none';

    document.getElementById('payment-reference').value = '';
    document.getElementById('promo-code').value = '';
    updateCashChangePreview();

    return;
  }

  cashFields.style.display = 'none';
  referenceFields.style.display = 'block';

  if (selectedPaymentMethod === 'card') {
    promoFields.style.display = 'none';
    document.getElementById('promo-code').value = '';

    referenceLabel.textContent = 'Card voucher / last 4 digits';
    referenceInput.placeholder = 'Example: 4587 or voucher code';
    referenceHelp.textContent = 'Enter the card voucher code or the last 4 digits.';
  }

  if (selectedPaymentMethod === 'yape') {
    promoFields.style.display = 'block';

    referenceLabel.textContent = 'Yape operation code';
    referenceInput.placeholder = 'Example: 845921';
    referenceHelp.textContent = 'Enter the Yape operation code.';
  }

  if (selectedPaymentMethod === 'plin') {
    promoFields.style.display = 'block';

    referenceLabel.textContent = 'Plin operation code';
    referenceInput.placeholder = 'Example: 739184';
    referenceHelp.textContent = 'Enter the Plin operation code.';
  }
}

function updateCashChangePreview() {
  const cashReceived = parseFloat(document.getElementById('cash-received').value || 0);
  const total = getCartTotal();
  const change = cashReceived - total;

  document.getElementById('cash-change-preview').textContent =
    `Change: S/ ${change > 0 ? change.toFixed(2) : '0.00'}`;
}

function updatePromoPreview() {
  renderCart();
}

function renderCart() {
  const items = cart.toArray();
  const container = document.getElementById('cart-items');
  const countEl = document.getElementById('cart-count');
  const btn = document.getElementById('btn-checkout');

  container.innerHTML = '';

  if (items.length === 0) {
    container.innerHTML = `
      <div class="cart-empty">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
        No products added yet
      </div>`;

    document.getElementById('subtotal').textContent = 'S/ 0.00';
    document.getElementById('total').textContent = 'S/ 0.00';

    const promoRow = document.getElementById('promo-discount-row');
    if (promoRow) {
      promoRow.style.display = 'none';
    }

    countEl.textContent = '0 products';
    btn.disabled = true;

    if (typeof updateStarsPreview === 'function') {
      updateStarsPreview(0);
    }

    updateCashChangePreview();

    return;
  }

  countEl.textContent = `${items.length} product${items.length > 1 ? 's' : ''}`;
  btn.disabled = false;

  let subtotalCart = 0;

  items.forEach(item => {
    const subtotal = item.price * item.quantity;
    subtotalCart += subtotal;

    const div = document.createElement('div');
    div.className = 'cart-item';

    div.innerHTML = `
      <div class="cart-item-avatar">${item.name[0].toUpperCase()}</div>

      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">S/ ${item.price.toFixed(2)}</div>
      </div>

      <div class="cart-item-controls">
        <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
        <span class="qty-num">${item.quantity}</span>
        <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
      </div>

      <span class="cart-item-subtotal">S/ ${subtotal.toFixed(2)}</span>

      <button class="delete-btn" onclick="removeItem(${item.id})">
        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
      </button>
    `;

    container.appendChild(div);
  });

  const promoDiscount = subtotalCart * getPromoDiscountRate();
  const finalTotal = subtotalCart - promoDiscount;

  document.getElementById('subtotal').textContent = `S/ ${subtotalCart.toFixed(2)}`;
  document.getElementById('total').textContent = `S/ ${finalTotal.toFixed(2)}`;

  const promoRow = document.getElementById('promo-discount-row');
  const promoLabel = document.getElementById('promo-discount-label');
  const promoAmount = document.getElementById('promo-discount');

  if (promoDiscount > 0) {
    promoRow.style.display = 'flex';
    promoLabel.textContent = `Promo ${getPromoCode()}`;
    promoAmount.textContent = `-S/ ${promoDiscount.toFixed(2)}`;
  } else {
    promoRow.style.display = 'none';
  }

  if (typeof updateStarsPreview === 'function') {
    updateStarsPreview(finalTotal);
  }

  updateCashChangePreview();
}

function validatePaymentBeforeSubmit() {
  const total = getCartTotal();

  if (selectedPaymentMethod === 'cash') {
    const cashReceived = parseFloat(document.getElementById('cash-received').value || 0);

    if (cashReceived <= 0) {
      alert('Enter the cash received.');
      return false;
    }

    if (cashReceived < total) {
      alert('Cash received is less than the total.');
      return false;
    }

    return true;
  }

  const reference = document.getElementById('payment-reference').value.trim();

  if (reference.length < 4) {
    alert('Enter a valid operation code or voucher.');
    return false;
  }

  const promoCode = getPromoCode();

  if (promoCode !== '') {
    if (selectedPaymentMethod === 'yape' && promoCode !== 'YAPE10') {
      alert('Invalid promo code for Yape.');
      return false;
    }

    if (selectedPaymentMethod === 'plin' && promoCode !== 'PLIN5') {
      alert('Invalid promo code for Plin.');
      return false;
    }

    if (selectedPaymentMethod === 'card' || selectedPaymentMethod === 'cash') {
      alert('Promo code is only available for Yape or Plin.');
      return false;
    }
  }

  return true;
}

function submitSale() {
  const customerId = document.getElementById('customer-select').value;

  if (!customerId) {
    alert('Please select a customer.');
    return;
  }

  const items = cart.toArray();

  if (items.length === 0) {
    alert('Cart is empty.');
    return;
  }

  if (!validatePaymentBeforeSubmit()) {
    return;
  }

  document.getElementById('form-customer').value = customerId;
  document.getElementById('form-payment-method').value = selectedPaymentMethod;
  document.getElementById('form-payment-reference').value =
    selectedPaymentMethod === 'cash'
      ? ''
      : document.getElementById('payment-reference').value.trim();

  document.getElementById('form-promo-code').value =
    selectedPaymentMethod === 'yape' || selectedPaymentMethod === 'plin'
      ? getPromoCode()
      : '';

  document.getElementById('form-cash-received').value =
    selectedPaymentMethod === 'cash'
      ? parseFloat(document.getElementById('cash-received').value || 0).toFixed(2)
      : '';

  const container = document.getElementById('form-products');
  container.innerHTML = '';

  items.forEach((item, i) => {
    container.innerHTML += `
      <input type="hidden" name="products[${i}][product_id]" value="${item.id}">
      <input type="hidden" name="products[${i}][quantity]" value="${item.quantity}">
    `;
  });

  document.getElementById('sale-form').submit();
}

let selectedMainCategory = 'all';
let selectedSubcategory = 'all';
let currentSearch = '';
let priceFilterIds = null;

function applyProductFilters() {
  document.querySelectorAll('.product-card').forEach(card => {
    const matchesSearch = card.dataset.name.includes(currentSearch);

    const matchesMain =
      selectedMainCategory === 'all' ||
      card.dataset.parentCategory === selectedMainCategory;

    const matchesSub =
      selectedSubcategory === 'all' ||
      card.dataset.category === selectedSubcategory;

    const matchesPrice =
      priceFilterIds === null ||
      priceFilterIds.has(card.dataset.id);

    card.style.display =
      matchesSearch && matchesMain && matchesSub && matchesPrice
        ? ''
        : 'none';
  });
}

document.getElementById('search-input').addEventListener('input', function() {
  currentSearch = this.value.toLowerCase().trim();
  applyProductFilters();
});

function selectMainCategory(categoryId, btn) {
  selectedMainCategory = categoryId;
  selectedSubcategory = 'all';

  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  document.querySelectorAll('.subcategory-btn').forEach(button => {
    button.classList.remove('active');

    if (categoryId !== 'all' && button.dataset.parent === categoryId) {
      button.style.display = '';
    } else {
      button.style.display = 'none';
    }
  });

  const subcategoryRow = document.getElementById('subcategory-row');

  if (categoryId === 'all') {
    subcategoryRow.classList.remove('show');
  } else {
    subcategoryRow.classList.add('show');
  }

  applyProductFilters();
}

function selectSubcategory(categoryId, btn) {
  selectedSubcategory = categoryId;

  document.querySelectorAll('.subcategory-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  applyProductFilters();
}

document.getElementById('bst-search-btn').addEventListener('click', () => {
  const minInput = document.getElementById('bst-min').value;
  const maxInput = document.getElementById('bst-max').value;
  const status = document.getElementById('bst-status');

  const min = minInput === '' ? 0 : parseFloat(minInput);
  const max = maxInput === '' ? Infinity : parseFloat(maxInput);

  if (isNaN(min) || isNaN(max) || min > max) {
    status.textContent = 'Enter a valid price range.';
    return;
  }

  const results = priceBST.rangeSearch(min, max);

  priceFilterIds = new Set(results.map(p => String(p.id)));
  applyProductFilters();

  const maxLabel = maxInput === '' ? '∞' : `S/ ${max.toFixed(2)}`;
  status.textContent = `${results.length} product(s) between S/ ${min.toFixed(2)} and ${maxLabel} — ${priceBST.comparisons} node comparisons`;
});

document.getElementById('bst-clear-btn').addEventListener('click', () => {
  document.getElementById('bst-min').value = '';
  document.getElementById('bst-max').value = '';
  document.getElementById('bst-status').textContent = '';

  priceFilterIds = null;
  selectedMainCategory = 'all';
  selectedSubcategory = 'all';
  currentSearch = '';

  document.getElementById('search-input').value = '';

  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));

  const allBtn = document.querySelector('.filter-btn');
  if (allBtn) {
    allBtn.classList.add('active');
  }

  document.querySelectorAll('.subcategory-btn').forEach(button => {
    button.classList.remove('active');
    button.style.display = 'none';
  });

  document.getElementById('subcategory-row').classList.remove('show');

  applyProductFilters();
});

document.getElementById('customer-search').addEventListener('input', function () {
  const q = this.value.toLowerCase().trim();

  document.querySelectorAll('#customer-select option').forEach(option => {
    if (option.value === '') {
      option.style.display = '';
      return;
    }

    const text = option.dataset.search || option.textContent.toLowerCase();
    option.style.display = text.includes(q) ? '' : 'none';
  });
});

document.getElementById('customer-select').addEventListener('change', function () {
  renderCart();
});

document.getElementById('cash-received').addEventListener('input', updateCashChangePreview);

document.getElementById('promo-code').addEventListener('input', function () {
  renderCart();
});

renderPaymentFields();
renderCart();

function closeReceiptModal() {
  const modal = document.getElementById('receipt-modal');

  if (modal) {
    modal.classList.remove('open');
  }
}

function printReceipt() {
  const content = document.getElementById('receipt-print-area').innerHTML;

  const win = window.open('', '_blank', 'width=420,height=650');

  win.document.write(`
    <html>
      <head>
        <title>Boleta</title>
        <style>
          body {
            font-family: monospace;
            padding: 16px;
            color: #111;
          }

          .receipt-center {
            text-align: center;
          }

          .receipt-title {
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 2px;
          }

          .receipt-line {
            border-top: 1px dashed #ccc;
            margin: 12px 0;
          }

          .receipt-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 7px;
          }

          .receipt-table-header,
          .receipt-product-row {
            display: grid;
            grid-template-columns: 42px 1fr 78px;
            gap: 8px;
            margin-bottom: 6px;
          }

          .receipt-table-header {
            font-weight: 900;
          }

          .receipt-table-header span:last-child,
          .receipt-product-row span:last-child {
            text-align: right;
          }

          .receipt-total {
            font-size: 15px;
            font-weight: 900;
          }
        </style>
      </head>

      <body>
        ${content}
      </body>
    </html>
  `);

  win.document.close();
  win.focus();
  win.print();
}

</script>

</x-portal-layout>