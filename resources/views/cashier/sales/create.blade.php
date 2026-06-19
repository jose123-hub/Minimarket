<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Point of Sale</title>
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
  .search-box { display: flex; align-items: center; gap: 8px; background: #f5f5f5; border: 1px solid #e8e8e8; border-radius: 8px; padding: 8px 14px; width: 280px; }
  .search-box svg { width: 15px; height: 15px; stroke: #aaa; fill: none; stroke-width: 1.8; flex-shrink: 0; }
  .search-box input { border: none; background: transparent; font-size: 13px; color: #555; outline: none; width: 100%; }
  .topbar-date { font-size: 13px; color: #888; }

  .pos-layout { display: grid; grid-template-columns: 1fr 340px; gap: 0; flex: 1; height: calc(100vh - 65px); }

  /* LEFT - Products */
  .products-panel { padding: 20px 24px; overflow-y: auto; }
  .filters { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
  .filter-btn { padding: 7px 16px; border-radius: 100px; border: 1px solid #e0e0e0; background: #fff; font-size: 13px; font-weight: 500; color: #555; cursor: pointer; transition: all 0.15s; }
  .filter-btn:hover { border-color: #e8192c; color: #e8192c; }
  .filter-btn.active { background: #e8192c; color: #fff; border-color: #e8192c; }

  .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
  .product-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; cursor: pointer; transition: all 0.15s; }
  .product-card:hover { border-color: #e8192c; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(232,25,44,0.1); }
  .product-img { height: 140px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 800; color: #ddd; }
  .product-info { padding: 12px; }
  .product-category { font-size: 11px; color: #999; margin-bottom: 4px; }
  .product-name { font-size: 14px; font-weight: 600; color: #111; margin-bottom: 8px; }
  .product-footer { display: flex; justify-content: space-between; align-items: center; }
  .product-price { font-size: 15px; font-weight: 700; color: #e8192c; }
  .product-stock { font-size: 11px; color: #aaa; }
  .product-card.out-of-stock { opacity: 0.5; cursor: not-allowed; }

  /* RIGHT - Cart */
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

  .hidden { display: none; }
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
    <a href="/cashier/sales/create" class="nav-item active">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      Sales (POS)
    </a>
    <a href="/cashier/products" class="nav-item">
      <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Products
    </a>
    <a href="#" class="nav-item">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Loyalty
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
      <h1>Point of Sale</h1>
      <p>Register sales quickly</p>
    </div>
    <div class="topbar-right">
      <div class="search-box">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="search-input" placeholder="Search product by name...">
      </div>
      <span class="topbar-date">{{ now()->isoFormat('dddd, D [of] MMMM [of] YYYY') }}</span>
    </div>
  </div>

  <div class="pos-layout">
    <div class="products-panel">

      @if(session('success'))
        <div class="success-msg" style="margin: 0 0 16px">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="error-msg" style="margin: 0 0 16px">{{ session('error') }}</div>
      @endif

      <div class="filters">
        <button class="filter-btn active" onclick="filterCategory('all', this)">All</button>
        @foreach($categories as $cat)
          <button class="filter-btn" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->nombre }}</button>
        @endforeach
      </div>

      <div class="products-grid" id="products-grid">
        @foreach($products as $product)
        <div class="product-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}"
             data-id="{{ $product->id }}"
             data-name="{{ $product->nombre }}"
             data-price="{{ $product->precio }}"
             data-stock="{{ $product->stock }}"
             data-category="{{ $product->category_id }}"
             @if($product->stock > 0) onclick="addToCart(this)" @endif>
          <div class="product-img">{{ strtoupper(substr($product->nombre, 0, 1)) }}</div>
          <div class="product-info">
            <div class="product-category">{{ $product->category?->nombre }}</div>
            <div class="product-name">{{ $product->nombre }}</div>
            <div class="product-footer">
              <span class="product-price">S/ {{ number_format($product->precio, 2) }}</span>
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
        <select id="customer-select">
         <option value="">— Select customer —</option>
           @foreach($customers as $customer)
         <option value="{{ $customer->id }}"
          {{ $customer->email === 'cliente@example.com' ? 'selected' : '' }}>
          {{ $customer->name }}
          {{ $customer->email === 'cliente@example.com' ? '(Generic)' : '' }}
    </option>
  @endforeach
</select>
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

<!-- Hidden form -->
<form id="sale-form" method="POST" action="{{ route('sales.store') }}" style="display:none">
  @csrf
  <input type="hidden" name="customer_id" id="form-customer">
  <div id="form-products"></div>
</form>

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
    let current = this.head;
    while (current) {
      if (current.data.id === id) {
        const newQty = current.data.quantity + delta;
        if (newQty <= 0) {
          this.remove(id);
          return;
        }
        if (newQty > current.data.stock) return;
        current.data.quantity = newQty;
        return;
      }
      current = current.next;
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

const cart = new LinkedList();

function addToCart(el) {
  const product = {
    id: parseInt(el.dataset.id),
    name: el.dataset.name,
    price: parseFloat(el.dataset.price),
    stock: parseInt(el.dataset.stock),
  };
  cart.insert(product);
  renderCart();
}

function changeQty(id, delta) {
  cart.updateQty(id, delta);
  renderCart();
}

function removeItem(id) {
  cart.remove(id);
  renderCart();
}

function clearCart() {
  cart.clear();
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
    countEl.textContent = '0 products';
    btn.disabled = true;
    return;
  }

  countEl.textContent = `${items.length} product${items.length > 1 ? 's' : ''}`;
  btn.disabled = false;

  let total = 0;

  items.forEach(item => {
    const subtotal = item.price * item.quantity;
    total += subtotal;
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

  document.getElementById('subtotal').textContent = `S/ ${total.toFixed(2)}`;
  document.getElementById('total').textContent = `S/ ${total.toFixed(2)}`;
}

function submitSale() {
  const customerId = document.getElementById('customer-select').value;
  if (!customerId) { alert('Please select a customer.'); return; }

  const items = cart.toArray();
  if (items.length === 0) { alert('Cart is empty.'); return; }

  document.getElementById('form-customer').value = customerId;
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

document.getElementById('search-input').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.product-card').forEach(card => {
    card.style.display = card.dataset.name.toLowerCase().includes(q) ? '' : 'none';
  });
});

function filterCategory(catId, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.product-card').forEach(card => {
    card.style.display = (catId === 'all' || card.dataset.category === catId) ? '' : 'none';
  });
}
</script>

</body>
</html>