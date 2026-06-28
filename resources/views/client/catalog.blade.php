<x-client-layout
    title="Store"
    active="store"
    :client="$client"
>
    <x-slot name="styles">
        <style>
          .client-content {
                max-width: 1280px;
                width: 100%;
            }
            .hero-banner {
                background: radial-gradient(ellipse at 30% 50%, #3a0010 0%, #1a0008 50%, #0a0a0a 100%);
                border-radius: 16px;
                padding: 32px 40px;
                margin-bottom: 24px;
            }

            .hero-tag {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: rgba(232,25,44,0.2);
                border: 1px solid rgba(232,25,44,0.3);
                color: #e8192c;
                font-size: 12px;
                font-weight: 600;
                padding: 5px 12px;
                border-radius: 100px;
                margin-bottom: 14px;
            }

            .hero-banner h2 {
                font-size: 28px;
                font-weight: 800;
                color: #fff;
                margin-bottom: 8px;
            }

            .hero-banner p {
                font-size: 14px;
                color: #aaa;
            }

            .store-content {
              display: grid;
              grid-template-columns: minmax(0, 1fr) 300px;
              gap: 24px;
              align-items: start;
              width: 100%;
            }

            .search-wrap {
                position: relative;
                margin-bottom: 16px;
            }

            .search-wrap svg {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                width: 16px;
                height: 16px;
                stroke: #aaa;
                fill: none;
                stroke-width: 1.8;
            }

            .search-wrap input {
                width: 100%;
                padding: 12px 14px 12px 42px;
                border: 1px solid #e8e8e8;
                border-radius: 10px;
                font-size: 14px;
                color: #333;
                background: #fff;
                outline: none;
            }

            .search-wrap input:focus {
                border-color: #e8192c;
            }

            .products-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 16px;
            }

            .product-card {
                background: #fff;
                border-radius: 12px;
                border: 1px solid #eee;
                overflow: hidden;
                transition: all 0.15s;
            }

            .product-card:hover {
                box-shadow: 0 4px 16px rgba(0,0,0,0.08);
                transform: translateY(-2px);
            }

            .product-img {
                height: 130px;
                background: #f5f5f5;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 44px;
                font-weight: 800;
                color: #e8c0c4;
            }

            .product-info {
                padding: 12px;
            }

            .product-category {
                font-size: 11px;
                color: #999;
                margin-bottom: 3px;
            }

            .product-name {
                font-size: 14px;
                font-weight: 600;
                color: #111;
                margin-bottom: 4px;
            }

            .low-stock {
                font-size: 11px;
                color: #f59e0b;
                margin-bottom: 8px;
            }

            .product-price {
                font-size: 17px;
                font-weight: 700;
                color: #111;
                margin-bottom: 12px;
            }

            .btn-add {
                width: 100%;
                padding: 9px;
                background: #e8192c;
                color: #fff;
                border: none;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
            }

            .cart-panel {
              background: #fff;
              border-radius: 12px;
              border: 1px solid #eee;
              padding: 20px;
              position: sticky;
              top: 80px;
              min-width: 0;
            }

            .cart-header {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 16px;
            }

            .cart-header svg {
                width: 18px;
                height: 18px;
                stroke: #e8192c;
                fill: none;
                stroke-width: 1.8;
            }

            .cart-empty {
                text-align: center;
                color: #ccc;
                font-size: 13px;
                padding: 24px 0;
            }

            .cart-item {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                padding: 10px 0;
                border-bottom: 1px solid #f5f5f5;
            }

            .cart-item-avatar {
                width: 32px;
                height: 32px;
                background: #f5f5f5;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: 700;
                color: #ccc;
                flex-shrink: 0;
            }

            .cart-item-info {
                flex: 1;
            }

            .cart-item-name {
                font-size: 13px;
                font-weight: 600;
                color: #111;
            }

            .cart-item-price {
                font-size: 12px;
                color: #999;
            }

            .cart-item-right {
                text-align: right;
            }

            .cart-item-subtotal {
                font-size: 13px;
                font-weight: 700;
                color: #111;
                margin-bottom: 4px;
            }

            .cart-item-controls {
                display: flex;
                align-items: center;
                gap: 6px;
                justify-content: flex-end;
            }

            .qty-btn {
                width: 22px;
                height: 22px;
                border: 1px solid #e0e0e0;
                border-radius: 5px;
                background: #fff;
                cursor: pointer;
                font-size: 13px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #555;
            }

            .qty-num {
                font-size: 13px;
                font-weight: 600;
                min-width: 16px;
                text-align: center;
            }

            .del-btn {
                background: none;
                border: none;
                cursor: pointer;
                color: #ddd;
                margin-left: 4px;
            }

            .del-btn svg {
                width: 14px;
                height: 14px;
                stroke: currentColor;
                fill: none;
                stroke-width: 1.8;
            }

            .cart-summary {
                margin-top: 14px;
            }

            .summary-row,
            .summary-stars,
            .summary-total {
                display: flex;
                justify-content: space-between;
            }

            .summary-row {
                font-size: 13px;
                color: #888;
                margin-bottom: 6px;
            }

            .summary-stars {
                font-size: 13px;
                color: #f59e0b;
                font-weight: 500;
                margin-bottom: 10px;
            }

            .summary-total {
                font-size: 16px;
                font-weight: 800;
                color: #111;
                padding-top: 10px;
                border-top: 1px solid #eee;
                margin-bottom: 16px;
            }

            .btn-order {
                width: 100%;
                padding: 13px;
                background: #e8192c;
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 14px;
                font-weight: 700;
                cursor: pointer;
            }

            @media (max-width: 900px) {
                .store-content {
                    grid-template-columns: 1fr;
                }

                .products-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 600px) {
                .products-grid {
                    grid-template-columns: 1fr;
                }
            }
            .btn-add svg {
            width: 14px;
            height: 14px;
            }
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
            min-height: auto;
            scroll-behavior: smooth;
          }

          .subcategory-row.show {
            display: flex;
          }

          .category-btn,
          .subcategory-btn {
            white-space: nowrap;
            flex: 0 0 auto;
            padding: 9px 18px;
            border-radius: 999px;
            border: 1px solid #e0e0e0;
            background: #fff;
            color: #444;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
          }

          .category-btn:not(.active):hover,
          .subcategory-btn:not(.active):hover {
             border-color: #e8192c;
             color: #e8192c;
          }

          .category-btn.active,
          .subcategory-btn.active {
              background: #e8192c;
              color: #fff;
              border-color: #e8192c;
           }

            .category-btn:focus,
            .subcategory-btn:focus {
              outline: none;
              box-shadow: none;
            }

            .products-toolbar {
              display: flex;
              justify-content: space-between;
              align-items: center;
              margin-bottom: 14px;
              font-size: 13px;
              color: #777;
            }

            .clear-filter-btn {
              border: none;
              background: #fff0f2;
              color: #e8192c;
              padding: 7px 12px;
              border-radius: 8px;
              font-size: 12px;
              font-weight: 800;
              cursor: pointer;
            }

            .clear-filter-btn:hover {
              background: #ffe1e6;
            }

            .no-results {
              background: #fff;
              border: 1px dashed #ddd;
              border-radius: 16px;
              padding: 50px 20px;
              text-align: center;
              color: #777;
              margin-top: 18px;
            }

            .no-results div {
              font-size: 38px;
              margin-bottom: 10px;
            }

            .no-results h3 {
              color: #111;
              font-size: 17px;
              margin-bottom: 6px;
            }
            .left-panel {
              min-width: 0;
            }
            .btn-add.disabled {
              background: #e5e5e5;
              color: #999;
              cursor: not-allowed;
            }

            .btn-add.disabled:hover {
              background: #e5e5e5;
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
              .added-toast {
                position: fixed;
                bottom: 24px;
                right: 24px;
                background: #111;
                color: #fff;
                padding: 13px 18px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 800;
                z-index: 9999;
                opacity: 0;
                transform: translateY(12px);
                transition: all 0.25s ease;
              }

              .added-toast.show {
                opacity: 1;
                transform: translateY(0);
              }
              .price-row {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 3px;
              }

              .old-price {
                font-size: 12px;
                color: #999;
                text-decoration: line-through;
              }

              .discount-badge {
                background: #fff0f2;
                color: #e8192c;
                font-size: 11px;
                font-weight: 900;
                padding: 3px 7px;
                border-radius: 999px;
              }
.checkout-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9998;
    padding: 20px;
}

.checkout-modal.show {
    display: flex;
}

.checkout-box {
    background: #fff;
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    border-radius: 18px;
    padding: 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.22);
}

.checkout-box h3 {
    font-size: 21px;
    font-weight: 900;
    color: #111;
    margin-bottom: 6px;
}

.checkout-subtitle {
    font-size: 13px;
    color: #777;
    margin-bottom: 18px;
}

.checkout-total {
    background: #f8f8f8;
    border-radius: 12px;
    padding: 14px;
    display: flex;
    justify-content: space-between;
    font-size: 15px;
    font-weight: 800;
    margin-bottom: 18px;
}

.checkout-section-title {
    font-size: 13px;
    font-weight: 900;
    color: #111;
    margin-bottom: 10px;
}

.delivery-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 14px;
}

.delivery-option {
    border: 1px solid #e5e5e5;
    background: #fff;
    border-radius: 12px;
    padding: 14px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 800;
    color: #333;
}

.delivery-option.active {
    border-color: #e8192c;
    background: #fff0f2;
    color: #e8192c;
}

.delivery-fields,
.pickup-fields,
.card-fields {
    margin-bottom: 16px;
}

.pickup-fields {
    display: none;
}

.delivery-fields input,
.pickup-fields input,
.card-fields input {
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 10px;
    outline: none;
}

.delivery-fields input:focus,
.pickup-fields input:focus,
.card-fields input:focus {
    border-color: #e8192c;
}

.card-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.checkout-note {
    font-size: 11px;
    color: #999;
    margin-top: -4px;
    margin-bottom: 0;
}

.checkout-actions {
    display: flex;
    gap: 10px;
}

.btn-cancel-checkout,
.btn-confirm-checkout {
    flex: 1;
    padding: 12px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
}

.btn-cancel-checkout {
    border: 1px solid #e5e5e5;
    background: #fff;
    color: #555;
}

.btn-confirm-checkout {
    border: none;
    background: #e8192c;
    color: #fff;
}     
        </style>
    </x-slot>

<div class="hero-banner">
  <div class="hero-tag">⭐ Express Stars Program</div>
  <h2>Hello, {{ Auth::user()->name }} 👋</h2>
  <p>Every S/ 5.00 you spend earns 1 star. Redeem them for rewards.</p>
</div>

<div class="store-content">
  <div class="left-panel">

    @if(session('success'))
      <div class="success-msg">{{ session('success') }}</div>
    @endif

    <div class="search-wrap">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="search-input" placeholder="Search products...">
    </div>

    <div class="category-area">
    <div class="category-row main-category-row">
        <button class="category-btn active"
                onclick="selectMainCategory('all', this)">
            All
        </button>

        @foreach($mainCategories as $main)
            <button class="category-btn"
                    onclick="selectMainCategory('{{ $main->id }}', this)">
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

    <div class="products-toolbar">
    <span id="results-count">{{ $products->count() }} products found</span>
    </div>

    <div class="products-grid" id="products-grid">
      @foreach($products as $product)
      @php
       $productCategory = $product->category;
       $parentCategoryId = $productCategory?->parent_id ?? $productCategory?->id;
      @endphp
      <div class="product-card"
         data-id="{{ $product->id }}"
         data-name="{{ strtolower($product->name) }}"
         data-category-name="{{ strtolower($product->category?->name ?? '') }}"
         data-price="{{ $product->price }}"
         data-stock="{{ $product->stock }}"
         data-category="{{ $product->category_id }}"
         data-parent-category="{{ $parentCategoryId }}">
        @if($product->image)
          <div class="product-img" style="padding:0;">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                 style="width:100%; height:100%; object-fit:cover;">
          </div>
        @else
          <div class="product-img">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
        @endif
        <div class="product-info">
          <div class="product-category">{{ $product->category?->name }}</div>
          <div class="product-name">{{ $product->name }}</div>
          @if($product->stock <= 5)
            <div class="low-stock">Low stock!</div>
          @endif
          @php
           $activeDiscount = $product->activeDiscount();
           $finalPrice = $product->finalPrice();
          @endphp

    @if($activeDiscount)
    <div class="price-row">
        <span class="old-price">
            S/ {{ number_format($product->price, 2) }}
        </span>
        <span class="discount-badge">
            -{{ number_format($activeDiscount->value, 0) }}%
        </span>
      </div>
        <div class="product-price">
        S/ {{ number_format($finalPrice, 2) }}
        </div>
      @else
      <div class="product-price">
        S/ {{ number_format($product->price, 2) }}
      </div>
     @endif
    @if($product->stock > 0)
    <button class="btn-add"
        data-id="{{ $product->id }}"
        data-name="{{ $product->name }}"
        data-price="{{ $finalPrice }}"
        data-stock="{{ $product->stock }}"
        onclick="addToCart(this)">
        <svg viewBox="0 0 24 24">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add+
          </button>
            @else
            <button class="btn-add disabled" disabled>
            Out of stock
          </button>
         @endif
        </div>
      </div>
      @endforeach
    </div>
    <div id="no-results" class="no-results" style="display:none;">
    <div>🔎</div>
      <h3>No products found</h3>
      <p>Try another search or category.</p>
     </div>
  </div>

  <div class="cart-panel">
    <div class="cart-header">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      <h3>Your cart<span id="cart-count">(0)</span></h3>
    </div>

    <div id="cart-items">
      <div class="cart-empty" id="cart-empty">No products in cart</div>
    </div>

    <div class="cart-summary" id="cart-summary" style="display:none">
      <div class="summary-row">
    <span>Subtotal</span>
    <span id="subtotal">S/ 0.00</span>
    </div>
    <div class="summary-row">
     <span>Online discount 10%</span>
     <span id="online-discount">-S/ 0.00</span>
    </div>
    <div class="summary-row">
     <span>Rewards credit</span>
     <span id="store-credit-used">-S/ 0.00</span>
    </div>
    <div class="summary-row" id="rounding-row" style="display:none;">
    <span>Rounding</span>
    <span id="rounding-adjustment">S/ 0.00</span>
    </div>
    <div class="summary-stars">
     <span>Stars to earn</span>
     <span id="stars-earn">+0 ⭐</span>
    </div>
    <div class="summary-total">
     <span>Total</span>
     <span id="total">S/ 0.00</span>
    </div>
      <button class="btn-order" onclick="submitOrder()">Place order</button>
    </div>
  </div>
</div>

<form id="order-form" method="POST" action="{{ route('client.orders.store') }}" style="display:none">
  @csrf
  <div id="form-products"></div>
</form>
<div id="checkout-modal" class="checkout-modal">
    <div class="checkout-box">
        <h3>Checkout</h3>
        <p class="checkout-subtitle">
            Complete your delivery information and card payment.
        </p>

        <div class="checkout-total">
            <span>Total to pay</span>
            <span id="checkout-total">S/ 0.00</span>
        </div>

        <div class="checkout-section-title">Delivery method</div>

        <div class="delivery-options">
            <button type="button"
                    class="delivery-option active"
                    onclick="selectDeliveryType('delivery', this)">
                Delivery
            </button>

            <button type="button"
                    class="delivery-option"
                    onclick="selectDeliveryType('pickup', this)">
                Store pickup
            </button>
        </div>

        <div id="delivery-fields" class="delivery-fields">
            <input type="text" id="delivery-address" placeholder="Delivery address">
            <input type="text" id="delivery-reference" placeholder="Reference">
            <input type="text" id="delivery-phone" placeholder="Contact phone" maxlength="9">
        </div>

        <div id="pickup-fields" class="pickup-fields">
            <input type="text"
                   id="pickup-store"
                   value="Minimarket Express - Main Store"
                   readonly>

            <input type="text"
                   id="pickup-note"
                   placeholder="Pickup note, optional">
        </div>

        <div class="checkout-section-title">Card payment</div>

        <div class="card-fields">
            <input type="text" id="card-name" placeholder="Cardholder name">

            <input type="text" id="card-number" placeholder="Card number" maxlength="19">

            <div class="card-row">
                <input type="text" id="card-expiry" placeholder="MM/YY" maxlength="5">
                <input type="text" id="card-cvv" placeholder="CVV" maxlength="4">
            </div>

            <p class="checkout-note">
                Demo only. Do not use real card data.
            </p>
        </div>

        <div class="checkout-actions">
            <button type="button" class="btn-cancel-checkout" onclick="closeCheckoutModal()">
                Cancel
            </button>

            <button type="button" class="btn-confirm-checkout" onclick="confirmCheckoutAndSubmit()">
                Confirm payment
            </button>
        </div>
    </div>
</div>
<div id="added-toast" class="added-toast">
    Product added to cart
</div>

<script> 
class Node {
  constructor(data) { this.data = data; this.next = null; }
}
class LinkedList {
  constructor() { this.head = null; this.size = 0; }
  insert(product) {
  let cur = this.head;

  while (cur) {
    if (cur.data.id === product.id) {
      if (cur.data.quantity < cur.data.stock) {
        cur.data.quantity++;
        return true;
      }

      return false;
    }

    cur = cur.next;
  }

  const node = new Node({ ...product, quantity: 1 });
  node.next = this.head;
  this.head = node;
  this.size++;

  return true;
  }
  remove(id) {
    if (!this.head) return;
    if (this.head.data.id === id) { this.head = this.head.next; this.size--; return; }
    let cur = this.head;
    while (cur.next) {
      if (cur.next.data.id === id) { cur.next = cur.next.next; this.size--; return; }
      cur = cur.next;
    }
  }
  updateQty(id, delta) {
    let cur = this.head;
    while (cur) {
      if (cur.data.id === id) {
        const q = cur.data.quantity + delta;
        if (q <= 0) { this.remove(id); return; }
        if (q > cur.data.stock) return;
        cur.data.quantity = q; return;
      }
      cur = cur.next;
    }
  }
  toArray() {
  const arr = []; 
  let cur = this.head;

  while (cur) { 
    arr.push(cur.data); 
    cur = cur.next; 
  }

  return arr;
}

fromArray(items) {
  this.head = null;
  this.size = 0;

  items.reverse().forEach(item => {
    const node = new Node(item);
    node.next = this.head;
    this.head = node;
    this.size++;
  });
}
}
const cart = new LinkedList();
let selectedDeliveryType = 'delivery';

const CART_STORAGE_KEY = 'express_client_cart';

const ONLINE_DISCOUNT_RATE = 0.10;
const STORE_CREDIT_BALANCE = parseFloat('{{ $client->store_credit_balance ?? 0 }}');
const STAR_PROGRESS_AMOUNT = parseFloat('{{ $client->star_progress_amount ?? 0 }}');

function getCartSubtotal() {
  return cart.toArray().reduce((sum, item) => {
    return sum + (item.price * item.quantity);
  }, 0);
}

function getOnlineDiscount() {
  return getCartSubtotal() * ONLINE_DISCOUNT_RATE;
}

function getTotalAfterOnlineDiscount() {
  return getCartSubtotal() - getOnlineDiscount();
}

function getStoreCreditUsed() {
  return Math.min(STORE_CREDIT_BALANCE, getTotalAfterOnlineDiscount());
}

function getTotalBeforeRounding() {
  return getTotalAfterOnlineDiscount() - getStoreCreditUsed();
}

function getRoundingAdjustment() {
  const beforeRounding = getTotalBeforeRounding();
  if (beforeRounding <= 0) {
    return 0;
  }
const roundedTotal = Math.round(beforeRounding * 10) / 10;

  return roundedTotal - beforeRounding;
}

function getCartTotal() {
  return getTotalBeforeRounding() + getRoundingAdjustment();
}

function saveCart() {
  localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart.toArray()));
}

function loadCart() {
  const savedCart = localStorage.getItem(CART_STORAGE_KEY);

  if (!savedCart) {
    return;
  }

  try {
    const items = JSON.parse(savedCart);

    if (Array.isArray(items)) {
      cart.fromArray(items);
    }
  } catch (error) {
    localStorage.removeItem(CART_STORAGE_KEY);
  }
}

function clearSavedCart() {
  localStorage.removeItem(CART_STORAGE_KEY);
}

function addToCart(el) {
  const wasAdded = cart.insert({
    id: parseInt(el.dataset.id),
    name: el.dataset.name,
    price: parseFloat(el.dataset.price),
    stock: parseInt(el.dataset.stock),
  });

  if (wasAdded) {
    saveCart();
    renderCart();
    showCartToast(`${el.dataset.name} added to cart`, 'success');
  } else {
    showCartToast(`Stock limit reached for ${el.dataset.name}`, 'error');
  }
  }

function showCartToast(message, type = 'success') {
    const toast = document.getElementById('added-toast');

    toast.textContent = message;
    toast.classList.remove('success', 'error');
    toast.classList.add(type);
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 1600);
}

function changeQty(id, delta) {
  cart.updateQty(id, delta);
  saveCart();
  renderCart();
}

function removeItem(id) {
  cart.remove(id);
  saveCart();
  renderCart();
}

function renderCart() {
  const items = cart.toArray();
  const totalItems = items.reduce((sum, item) => sum + item.quantity, 0);
  document.getElementById('cart-count').textContent = `(${totalItems})`;
  const container = document.getElementById('cart-items');
  const summary = document.getElementById('cart-summary');

  container.innerHTML = '';

  if (items.length === 0) {
    container.innerHTML = '<div class="cart-empty">No products in cart</div>';
    summary.style.display = 'none';
    return;
  }

  summary.style.display = 'block';
  let total = 0;

  items.forEach(item => {
    const sub = item.price * item.quantity;
    total += sub;
    const div = document.createElement('div');
    div.className = 'cart-item';
    div.innerHTML = `
      <div class="cart-item-avatar">${item.name[0].toUpperCase()}</div>
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">S/ ${item.price.toFixed(2)}</div>
      </div>
      <div class="cart-item-right">
        <div class="cart-item-subtotal">S/ ${sub.toFixed(2)}</div>
        <div class="cart-item-controls">
          <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
          <span class="qty-num">${item.quantity}</span>
          <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
          <button class="del-btn" onclick="removeItem(${item.id})">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
          </button>
        </div>
      </div>
    `;
    container.appendChild(div);
  });

  const subtotal = total;
const onlineDiscount = subtotal * ONLINE_DISCOUNT_RATE;
const totalAfterDiscount = subtotal - onlineDiscount;
const storeCreditUsed = Math.min(STORE_CREDIT_BALANCE, totalAfterDiscount);
const totalBeforeRounding = totalAfterDiscount - storeCreditUsed;

const roundedTotal = totalBeforeRounding > 0
  ? Math.round(totalBeforeRounding * 10) / 10
  : 0;

const roundingAdjustment = roundedTotal - totalBeforeRounding;
const finalTotal = roundedTotal;

const estimatedStars = Math.floor((STAR_PROGRESS_AMOUNT + finalTotal) / 5);

document.getElementById('subtotal').textContent = `S/ ${subtotal.toFixed(2)}`;
document.getElementById('online-discount').textContent = `-S/ ${onlineDiscount.toFixed(2)}`;
document.getElementById('store-credit-used').textContent = `-S/ ${storeCreditUsed.toFixed(2)}`;
document.getElementById('total').textContent = `S/ ${finalTotal.toFixed(2)}`;
document.getElementById('stars-earn').textContent = `+${estimatedStars} ⭐`;

const roundingRow = document.getElementById('rounding-row');
const roundingAmount = document.getElementById('rounding-adjustment');

if (Math.abs(roundingAdjustment) > 0.001) {
  roundingRow.style.display = 'flex';

  roundingAmount.textContent =
    `${roundingAdjustment > 0 ? '+' : '-'}S/ ${Math.abs(roundingAdjustment).toFixed(2)}`;
} else {
  roundingRow.style.display = 'none';
}
}

function openCheckoutModal() {
  const items = cart.toArray();

  if (items.length === 0) {
    showCartToast('Your cart is empty', 'error');
    return;
  }

  const total = getCartTotal();

  document.getElementById('checkout-total').textContent = `S/ ${total.toFixed(2)}`;
  document.getElementById('checkout-modal').classList.add('show');
}

function closeCheckoutModal() {
  document.getElementById('checkout-modal').classList.remove('show');
}

function selectDeliveryType(type, btn) {
  selectedDeliveryType = type;

  document.querySelectorAll('.delivery-option').forEach(button => {
    button.classList.remove('active');
  });

  btn.classList.add('active');

  const deliveryFields = document.getElementById('delivery-fields');
  const pickupFields = document.getElementById('pickup-fields');

  if (type === 'delivery') {
    deliveryFields.style.display = 'block';
    pickupFields.style.display = 'none';
  } else {
    deliveryFields.style.display = 'none';
    pickupFields.style.display = 'block';
  }
}

function validateDeliveryData() {
  if (selectedDeliveryType === 'delivery') {
    const address = document.getElementById('delivery-address').value.trim();
    const reference = document.getElementById('delivery-reference').value.trim();
    const phone = document.getElementById('delivery-phone').value.trim();

    if (address === '') {
      showCartToast('Enter delivery address', 'error');
      return false;
    }

    if (reference === '') {
      showCartToast('Enter delivery reference', 'error');
      return false;
    }

    if (!/^\d{9}$/.test(phone)) {
      showCartToast('Enter a valid 9-digit phone', 'error');
      return false;
    }
  }

  return true;
}

function validateCardPayment() {
  const finalTotal = getCartTotal();

  if (finalTotal <= 0) {
    return true;
  }

  const cardName = document.getElementById('card-name').value.trim();
  const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');
  const cardExpiry = document.getElementById('card-expiry').value.trim();
  const cardCvv = document.getElementById('card-cvv').value.trim();

  if (cardName.length < 3) {
    showCartToast('Enter cardholder name', 'error');
    return false;
  }

  if (!/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/.test(cardName)) {
    showCartToast('Cardholder name must contain only letters', 'error');
    return false;
  }

  if (!/^\d{16}$/.test(cardNumber)) {
    showCartToast('Enter a valid 16-digit card number', 'error');
    return false;
  }

  if (!/^\d{2}\/\d{2}$/.test(cardExpiry)) {
    showCartToast('Enter expiry as MM/YY', 'error');
    return false;
  }

  const [monthText, yearText] = cardExpiry.split('/');
  const month = parseInt(monthText, 10);
  const year = parseInt('20' + yearText, 10);

  if (month < 1 || month > 12) {
    showCartToast('Enter a valid expiry month', 'error');
    return false;
  }

  const now = new Date();
  const currentMonth = now.getMonth() + 1;
  const currentYear = now.getFullYear();

  if (year < currentYear || (year === currentYear && month < currentMonth)) {
    showCartToast('Card expiry date is not valid', 'error');
    return false;
  }

  if (!/^\d{3,4}$/.test(cardCvv)) {
    showCartToast('Enter a valid CVV', 'error');
    return false;
  }

  return true;
}

function submitOrder() {
  openCheckoutModal();
}

function confirmCheckoutAndSubmit() {
  const items = cart.toArray();

  if (items.length === 0) {
    showCartToast('Your cart is empty', 'error');
    return;
  }

  if (!validateDeliveryData()) {
    return;
  }

  if (!validateCardPayment()) {
    return;
  }

  const container = document.getElementById('form-products');
  container.innerHTML = '';

  items.forEach((item, i) => {
    container.innerHTML += `
      <input type="hidden" name="products[${i}][product_id]" value="${item.id}">
      <input type="hidden" name="products[${i}][quantity]" value="${item.quantity}">
    `;
  });

  const finalTotal = getCartTotal();
  const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');

  let paymentMethod = 'card';
  let cardLastFour = '';

  if (finalTotal <= 0) {
  paymentMethod = 'store_credit';
  } else {
  cardLastFour = cardNumber.slice(-4);
  }

  container.innerHTML += `
  <input type="hidden" name="delivery_type" value="${selectedDeliveryType}">
  <input type="hidden" name="payment_method" value="${paymentMethod}">
  <input type="hidden" name="payment_status" value="paid">
  <input type="hidden" name="card_last_four" value="${cardLastFour}">
   `;

  if (selectedDeliveryType === 'delivery') {
    container.innerHTML += `
      <input type="hidden" name="delivery_address" value="${document.getElementById('delivery-address').value.trim()}">
      <input type="hidden" name="delivery_reference" value="${document.getElementById('delivery-reference').value.trim()}">
      <input type="hidden" name="delivery_phone" value="${document.getElementById('delivery-phone').value.trim()}">
    `;
  } else {
    container.innerHTML += `
      <input type="hidden" name="pickup_store" value="${document.getElementById('pickup-store').value.trim()}">
      <input type="hidden" name="pickup_note" value="${document.getElementById('pickup-note').value.trim()}">
    `;
  }

  clearSavedCart();
  document.getElementById('order-form').submit();
}

let selectedCategory = 'all';

const searchInput = document.getElementById('search-input');
const resultsCount = document.getElementById('results-count');
const noResults = document.getElementById('no-results');

searchInput.addEventListener('input', applyFilters);

function selectMainCategory(categoryId, btn) {
    selectedMainCategory = categoryId;
    selectedSubcategory = 'all';

    const subcategoryRow = document.getElementById('subcategory-row');

    document.querySelectorAll('.category-btn').forEach(button => {
        button.classList.remove('active');
    });

    document.querySelectorAll('.subcategory-btn').forEach(button => {
        button.classList.remove('active');
        button.style.display = 'none';
    });

    btn.classList.add('active');
    btn.blur();

    if (categoryId !== 'all') {
        let hasSubcategories = false;

        document.querySelectorAll(`.subcategory-btn[data-parent="${categoryId}"]`).forEach(button => {
            button.style.display = '';
            hasSubcategories = true;
        });

        if (hasSubcategories) {
            subcategoryRow.classList.add('show');
        } else {
            subcategoryRow.classList.remove('show');
        }
    } else {
        subcategoryRow.classList.remove('show');
    }

    applyFilters();
}

function selectSubcategory(categoryId, btn) {
    selectedSubcategory = categoryId;

    document.querySelectorAll('.subcategory-btn').forEach(button => {
        button.classList.remove('active');
    });

    btn.classList.add('active');
    btn.blur();

    applyFilters();
}

function applyFilters() {
    const query = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    document.querySelectorAll('.product-card').forEach(card => {
        const productName = card.dataset.name || '';
        const categoryName = card.dataset.categoryName || '';
        const productCategory = card.dataset.category;
        const parentCategory = card.dataset.parentCategory;

        const matchesSearch =
            productName.includes(query) ||
            categoryName.includes(query);

        const matchesMainCategory =
            selectedMainCategory === 'all' ||
            parentCategory === selectedMainCategory;

        const matchesSubcategory =
            selectedSubcategory === 'all' ||
            productCategory === selectedSubcategory;

        if (matchesSearch && matchesMainCategory && matchesSubcategory) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    resultsCount.textContent = `${visibleCount} products found`;
    noResults.style.display = visibleCount === 0 ? 'block' : 'none';
}

function clearFilters() {
    selectedMainCategory = 'all';
    selectedSubcategory = 'all';
    searchInput.value = '';

    document.querySelectorAll('.category-btn').forEach(button => {
        button.classList.remove('active');
    });

    document.querySelectorAll('.subcategory-btn').forEach(button => {
        button.classList.remove('active');
        button.style.display = 'none';
    });

    const allButton = document.querySelector('.category-btn');
    if (allButton) {
        allButton.classList.add('active');
    }

    applyFilters();
}
loadCart();
renderCart();
</script>

</x-client-layout>