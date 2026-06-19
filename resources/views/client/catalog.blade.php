<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Store</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; background: #f9f9f9; color: #111; }

  .navbar { background: #fff; border-bottom: 1px solid #eee; padding: 0 40px; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 100; }
  .navbar-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
  .logo-icon { width: 36px; height: 36px; background: #e8192c; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
  .logo-icon svg { width: 18px; height: 18px; fill: #fff; }
  .logo-text strong { font-size: 15px; font-weight: 700; color: #111; display: block; line-height: 1.1; }
  .logo-text span { font-size: 10px; color: #999; }
  .navbar-links { display: flex; align-items: center; gap: 8px; }
  .nav-link { display: flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; color: #555; text-decoration: none; transition: all 0.15s; }
  .nav-link:hover { background: #f5f5f5; color: #111; }
  .nav-link.active { background: #e8192c; color: #fff; }
  .nav-link svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .navbar-user { display: flex; align-items: center; gap: 12px; }
  .user-stars { display: flex; align-items: center; gap: 4px; font-size: 13px; color: #e8192c; font-weight: 600; }
  .user-stars svg { width: 14px; height: 14px; fill: #e8192c; stroke: none; }
  .user-name { font-size: 13px; font-weight: 600; color: #111; }
  .user-avatar { width: 34px; height: 34px; background: #e8192c; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; }
  .logout-form button { background: none; border: none; cursor: pointer; color: #aaa; padding: 6px; }
  .logout-form button:hover { color: #e8192c; }
  .logout-form button svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; display: block; }

  .hero-banner { background: radial-gradient(ellipse at 30% 50%, #3a0010 0%, #1a0008 50%, #0a0a0a 100%); margin: 20px 40px; border-radius: 16px; padding: 32px 40px; }
  .hero-tag { display: inline-flex; align-items: center; gap: 6px; background: rgba(232,25,44,0.2); border: 1px solid rgba(232,25,44,0.3); color: #e8192c; font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 100px; margin-bottom: 14px; }
  .hero-banner h2 { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px; }
  .hero-banner p { font-size: 14px; color: #888; }

  .content { padding: 20px 40px; display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: start; }

  .search-wrap { position: relative; margin-bottom: 16px; }
  .search-wrap svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; stroke: #aaa; fill: none; stroke-width: 1.8; }
  .search-wrap input { width: 100%; padding: 12px 14px 12px 42px; border: 1px solid #e8e8e8; border-radius: 10px; font-size: 14px; color: #333; background: #fff; outline: none; }
  .search-wrap input:focus { border-color: #e8192c; }

  .filters { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
  .filter-btn { padding: 7px 16px; border-radius: 100px; border: 1px solid #e0e0e0; background: #fff; font-size: 13px; font-weight: 500; color: #555; cursor: pointer; transition: all 0.15s; }
  .filter-btn:hover { border-color: #e8192c; color: #e8192c; }
  .filter-btn.active { background: #e8192c; color: #fff; border-color: #e8192c; }

  .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
  .product-card { background: #fff; border-radius: 12px; border: 1px solid #eee; overflow: hidden; transition: all 0.15s; }
  .product-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); transform: translateY(-2px); }
  .product-img { height: 130px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 44px; font-weight: 800; color: #e8c0c4; }
  .product-info { padding: 12px; }
  .product-category { font-size: 11px; color: #999; margin-bottom: 3px; }
  .product-name { font-size: 14px; font-weight: 600; color: #111; margin-bottom: 4px; }
  .low-stock { font-size: 11px; color: #f59e0b; margin-bottom: 8px; }
  .product-price { font-size: 17px; font-weight: 700; color: #111; margin-bottom: 12px; }
  .btn-add { width: 100%; padding: 9px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s; }
  .btn-add:hover { background: #c41525; }
  .btn-add svg { width: 14px; height: 14px; stroke: #fff; fill: none; stroke-width: 2.5; }

  .cart-panel { background: #fff; border-radius: 12px; border: 1px solid #eee; padding: 20px; position: sticky; top: 80px; }
  .cart-header { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
  .cart-header svg { width: 18px; height: 18px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .cart-header h3 { font-size: 15px; font-weight: 700; color: #111; }
  .cart-empty { text-align: center; color: #ccc; font-size: 13px; padding: 24px 0; }
  .cart-item { display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
  .cart-item-avatar { width: 32px; height: 32px; background: #f5f5f5; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: #ccc; flex-shrink: 0; }
  .cart-item-info { flex: 1; }
  .cart-item-name { font-size: 13px; font-weight: 600; color: #111; }
  .cart-item-price { font-size: 12px; color: #999; }
  .cart-item-right { text-align: right; }
  .cart-item-subtotal { font-size: 13px; font-weight: 700; color: #111; margin-bottom: 4px; }
  .cart-item-controls { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
  .qty-btn { width: 22px; height: 22px; border: 1px solid #e0e0e0; border-radius: 5px; background: #fff; cursor: pointer; font-size: 13px; display: flex; align-items: center; justify-content: center; color: #555; }
  .qty-btn:hover { border-color: #e8192c; color: #e8192c; }
  .qty-num { font-size: 13px; font-weight: 600; min-width: 16px; text-align: center; }
  .del-btn { background: none; border: none; cursor: pointer; color: #ddd; margin-left: 4px; }
  .del-btn:hover { color: #e8192c; }
  .del-btn svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 1.8; }

  .cart-summary { margin-top: 14px; }
  .summary-row { display: flex; justify-content: space-between; font-size: 13px; color: #888; margin-bottom: 6px; }
  .summary-stars { display: flex; justify-content: space-between; font-size: 13px; color: #f59e0b; font-weight: 500; margin-bottom: 10px; }
  .summary-total { display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: #111; padding-top: 10px; border-top: 1px solid #eee; margin-bottom: 16px; }
  .btn-order { width: 100%; padding: 13px; background: #e8192c; color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
  .btn-order:hover { background: #c41525; }
  .success-msg { background: #f0fff4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 10px 14px; font-size: 13px; color: #16a34a; margin-bottom: 16px; }
</style>
</head>
<body>

<nav class="navbar">
  <a href="/client/catalog" class="navbar-logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text">
      <strong>Express</strong>
      <span>Minimarket · Customer</span>
    </div>
  </a>

  <div class="navbar-links">
    <a href="/client/catalog" class="nav-link active">
      <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      Store
    </a>
    <a href="#" class="nav-link">
      <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
      My orders
    </a>
    <a href="#" class="nav-link">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      My stars
    </a>
    <a href="#" class="nav-link">
      <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Profile
    </a>
  </div>

  <div class="navbar-user">
    <div class="user-stars">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      0 stars
    </div>
    <span class="user-name">{{ Auth::user()->name }}</span>
    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <form class="logout-form" method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </button>
    </form>
  </div>
</nav>

<div class="hero-banner">
  <div class="hero-tag">⭐ Express Stars Program</div>
  <h2>Hello, {{ Auth::user()->name }} 👋</h2>
  <p>Every S/1 you spend earns 1 star. Redeem them for discounts.</p>
</div>

<div class="content">
  <div class="left-panel">

    @if(session('success'))
      <div class="success-msg">{{ session('success') }}</div>
    @endif

    <div class="search-wrap">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="search-input" placeholder="Search products...">
    </div>

    <div class="filters">
      <button class="filter-btn active" onclick="filterCat('all', this)">All</button>
      @foreach($categories as $cat)
        <button class="filter-btn" onclick="filterCat('{{ $cat->id }}', this)">{{ $cat->nombre }}</button>
      @endforeach
    </div>

    <div class="products-grid" id="products-grid">
      @foreach($products as $product)
      <div class="product-card"
           data-id="{{ $product->id }}"
           data-name="{{ $product->nombre }}"
           data-price="{{ $product->precio }}"
           data-stock="{{ $product->stock }}"
           data-category="{{ $product->category_id }}">
        <div class="product-img">{{ strtoupper(substr($product->nombre, 0, 1)) }}</div>
        <div class="product-info">
          <div class="product-category">{{ $product->category?->nombre }}</div>
          <div class="product-name">{{ $product->nombre }}</div>
          @if($product->stock <= 5)
            <div class="low-stock">Low stock!</div>
          @endif
          <div class="product-price">S/ {{ number_format($product->precio, 2) }}</div>
          <button class="btn-add"
          data-id="{{ $product->id }}"
          data-name="{{ $product->nombre }}"
          data-price="{{ $product->precio }}"
          data-stock="{{ $product->stock }}"
          onclick="addToCart(this)">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add
          </button>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <div class="cart-panel">
    <div class="cart-header">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
      <h3>Your cart</h3>
    </div>

    <div id="cart-items">
      <div class="cart-empty" id="cart-empty">No products in cart</div>
    </div>

    <div class="cart-summary" id="cart-summary" style="display:none">
      <div class="summary-row"><span>Subtotal</span><span id="subtotal">S/ 0.00</span></div>
      <div class="summary-stars"><span>Stars to earn</span><span id="stars-earn">+0 ⭐</span></div>
      <div class="summary-total"><span>Total</span><span id="total">S/ 0.00</span></div>
      <button class="btn-order" onclick="submitOrder()">Place order</button>
    </div>
  </div>
</div>

<form id="order-form" method="POST" action="{{ route('client.orders.store') }}" style="display:none">
  @csrf
  <div id="form-products"></div>
</form>

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
        if (cur.data.quantity < cur.data.stock) cur.data.quantity++;
        return;
      }
      cur = cur.next;
    }
    const node = new Node({ ...product, quantity: 1 });
    node.next = this.head; this.head = node; this.size++;
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
    const arr = []; let cur = this.head;
    while (cur) { arr.push(cur.data); cur = cur.next; }
    return arr;
  }
}

const cart = new LinkedList();

function addToCart(el) {
  cart.insert({
    id: parseInt(el.dataset.id),
    name: el.dataset.name,
    price: parseFloat(el.dataset.price),
    stock: parseInt(el.dataset.stock),
  });
  renderCart();
}

function changeQty(id, delta) { cart.updateQty(id, delta); renderCart(); }
function removeItem(id) { cart.remove(id); renderCart(); }

function renderCart() {
  const items = cart.toArray();
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

  document.getElementById('subtotal').textContent = `S/ ${total.toFixed(2)}`;
  document.getElementById('total').textContent = `S/ ${total.toFixed(2)}`;
  document.getElementById('stars-earn').textContent = `+${Math.floor(total)} ⭐`;
}

function submitOrder() {
  const items = cart.toArray();
  if (items.length === 0) return;
  const container = document.getElementById('form-products');
  container.innerHTML = '';
  items.forEach((item, i) => {
    container.innerHTML += `
      <input type="hidden" name="products[${i}][product_id]" value="${item.id}">
      <input type="hidden" name="products[${i}][quantity]" value="${item.quantity}">
    `;
  });
  document.getElementById('order-form').submit();
}

document.getElementById('search-input').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.product-card').forEach(card => {
    card.style.display = card.dataset.name.toLowerCase().includes(q) ? '' : 'none';
  });
});

function filterCat(catId, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.product-card').forEach(card => {
    card.style.display = (catId === 'all' || card.dataset.category === catId) ? '' : 'none';
  });
}
</script>

</body>
</html>