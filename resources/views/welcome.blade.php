<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express Minimarket</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #fff; min-height: 100vh; }

  nav {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 48px; position: absolute; top: 0; left: 0; right: 0; z-index: 10;
  }
  .logo { display: flex; align-items: center; gap: 12px; }
  .logo-icon { width: 40px; height: 40px; background: #e8192c; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
  .logo-icon svg { width: 22px; height: 22px; fill: #fff; }
  .logo-text strong { font-size: 16px; font-weight: 700; color: #fff; display: block; }
  .logo-text span { font-size: 11px; color: #999; }
  .nav-links { display: flex; align-items: center; gap: 32px; }
  .nav-links a { color: #ccc; text-decoration: none; font-size: 14px; }
  .nav-links a:hover { color: #fff; }
  .nav-btn { background: #e8192c; color: #fff; padding: 10px 22px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; }
  .nav-btn:hover { background: #c41525; color: #fff; }

  .hero {
    min-height: 100vh;
    background: radial-gradient(ellipse at 50% 50%, #3a0010 0%, #1a0008 40%, #0a0a0a 70%);
    display: flex; flex-direction: column; justify-content: center; align-items: center;
    padding: 120px 48px 80px;
    text-align: center;
   }
  .hero-tag { display: inline-flex; align-items: center; gap: 8px; background: rgba(232,25,44,0.15); border: 1px solid rgba(232,25,44,0.3); color: #e8192c; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 100px; margin-bottom: 28px; width: fit-content; }
  .hero h1 { font-size: clamp(36px, 5vw, 64px); font-weight: 800; line-height: 1.1; max-width: 700px; margin-bottom: 24px; }
  .hero h1 .accent { color: #e8192c; }
  .hero p { font-size: 17px; color: #999; max-width: 480px; line-height: 1.7; margin-bottom: 40px; margin: 0 auto 40px; }
  .hero-btns { display: flex; gap: 16px; }
  .btn-primary { background: #e8192c; color: #fff; padding: 14px 32px; border-radius: 10px; font-size: 15px; font-weight: 600; text-decoration: none; display: inline-block; }
  .btn-primary:hover { background: #c41525; color: #fff; }
  .btn-secondary { background: transparent; color: #fff; padding: 14px 32px; border-radius: 10px; font-size: 15px; font-weight: 600; text-decoration: none; border: 1px solid rgba(255,255,255,0.2); display: inline-block; }
  .btn-secondary:hover { border-color: rgba(255,255,255,0.5); color: #fff; }

  .stats { display: flex; gap: 48px; margin-top: 64px; padding-top: 48px; border-top: 1px solid rgba(255,255,255,0.08); justify-content: center; }
  .stat-num { font-size: 28px; font-weight: 800; }
  .stat-label { font-size: 13px; color: #666; margin-top: 2px; }

  .features { background: #0d0d0d; padding: 96px 48px; border-top: 1px solid rgba(255,255,255,0.05); }
  .features-header { text-align: center; margin-bottom: 64px; }
  .features-header h2 { font-size: 36px; font-weight: 800; margin-bottom: 12px; }
  .features-header p { color: #666; font-size: 16px; }
  .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 1000px; margin: 0 auto; }
  .feature-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; padding: 28px; }
  .feature-card:hover { border-color: rgba(232,25,44,0.3); }
  .feature-icon { width: 44px; height: 44px; background: rgba(232,25,44,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
  .feature-icon svg { width: 22px; height: 22px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .feature-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
  .feature-card p { font-size: 14px; color: #666; line-height: 1.6; }

  .roles { background: #0a0a0a; padding: 96px 48px; border-top: 1px solid rgba(255,255,255,0.05); }
  .roles-header { text-align: center; margin-bottom: 56px; }
  .roles-header h2 { font-size: 36px; font-weight: 800; margin-bottom: 12px; }
  .roles-header p { color: #666; }
  .roles-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 900px; margin: 0 auto; }
  .role-card { border-radius: 14px; padding: 28px; text-align: center; border: 1px solid rgba(255,255,255,0.06); }
  .role-card.admin { background: rgba(232,25,44,0.08); border-color: rgba(232,25,44,0.2); }
  .role-card.cashier { background: rgba(255,165,0,0.06); border-color: rgba(255,165,0,0.15); }
  .role-card.client { background: rgba(30,200,100,0.06); border-color: rgba(30,200,100,0.15); }
  .role-emoji { font-size: 32px; margin-bottom: 14px; }
  .role-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
  .role-card p { font-size: 13px; color: #777; line-height: 1.6; }

  .cta { padding: 96px 48px; text-align: center; background: radial-gradient(ellipse at center, rgba(232,25,44,0.1) 0%, transparent 70%); border-top: 1px solid rgba(255,255,255,0.05); }
  .cta h2 { font-size: 40px; font-weight: 800; margin-bottom: 16px; }
  .cta p { color: #666; font-size: 16px; margin-bottom: 36px; }
  .cta-btns { display: flex; gap: 16px; justify-content: center; }

  footer { background: #080808; padding: 24px 48px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; }
  footer p { font-size: 13px; color: #555; }
  footer a { color: #555; text-decoration: none; font-size: 13px; margin-left: 24px; }
  footer a:hover { color: #999; }
</style>
</head>
<body>

<nav>
  <div class="logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text">
      <strong>Express</strong>
      <span>Minimarket</span>
    </div>
  </div>
  <div class="nav-links">
    <a href="#">Home</a>
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#">Contact</a>
    <a href="{{ route('login') }}" class="nav-btn">Sign in</a>
  </div>
</nav>

<section class="hero">
  <div class="hero-tag">🚀 Integrated management system</div>
  <h1>Manage your minimarket <span class="accent">with speed.</span></h1>
  <p>Point of sale, inventory, purchasing, promotions and customer loyalty in one platform.</p>
  <div class="hero-btns">
    <a href="{{ route('login') }}" class="btn-primary">Sign in</a>
    <a href="{{ route('register') }}" class="btn-secondary">Create account</a>
  </div>
  <div class="stats">
    <div><div class="stat-num">3</div><div class="stat-label">User roles</div></div>
    <div><div class="stat-num">24</div><div class="stat-label">System modules</div></div>
    <div><div class="stat-num">100%</div><div class="stat-label">Web responsive</div></div>
  </div>
</section>

<section class="features">
  <div class="features-header">
    <h2>Everything you need</h2>
    <p>A complete system to manage your business</p>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon"><svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
      <h3>Point of sale</h3>
      <p>Register sales quickly, issue receipts and control your daily cash register.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
      <h3>Inventory</h3>
      <p>Real-time stock control, minimum alerts and purchase orders to suppliers.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
      <h3>Loyalty stars</h3>
      <p>Star system for customers. Earn points per purchase and redeem them for rewards.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
      <h3>Discounts</h3>
      <p>Create promotions and discounts by product, category or special dates.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
      <h3>User management</h3>
      <p>Manage employees, cashiers and customers with differentiated roles and permissions.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
      <h3>Reports</h3>
      <p>View sales, inventory movements and business performance in real time.</p>
    </div>
  </div>
</section>

<section class="roles">
  <div class="roles-header">
    <h2>A system for everyone</h2>
    <p>Each user only accesses what they need</p>
  </div>
  <div class="roles-grid">
    <div class="role-card admin">
      <div class="role-emoji">👨‍💼</div>
      <h3>Administrator</h3>
      <p>Full system control: products, users, reports and configuration.</p>
    </div>
    <div class="role-card cashier">
      <div class="role-emoji">🧾</div>
      <h3>Cashier</h3>
      <p>Register sales, manage the daily cash register and process returns.</p>
    </div>
    <div class="role-card client">
      <div class="role-emoji">🛒</div>
      <h3>Customer</h3>
      <p>Browse the catalog, place orders and redeem Express stars.</p>
    </div>
  </div>
</section>

<section class="cta">
  <h2>Ready to get started?</h2>
  <p>Sign in to the system or create your customer account</p>
  <div class="cta-btns">
    <a href="{{ route('login') }}" class="btn-primary">Sign in</a>
    <a href="{{ route('register') }}" class="btn-secondary">Create customer account</a>
  </div>
</section>

<footer>
  <p>© 2026 Express Minimarket — All rights reserved</p>
  <div>
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#">Contact</a>
  </div>
</footer>

</body>
</html>