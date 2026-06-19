<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express Minimarket — Services</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #fff; min-height: 100vh; }
  nav { display: flex; align-items: center; justify-content: space-between; padding: 20px 48px; border-bottom: 1px solid rgba(255,255,255,0.06); }
  .logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
  .logo-icon { width: 40px; height: 40px; background: #e8192c; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
  .logo-icon svg { width: 22px; height: 22px; fill: #fff; }
  .logo-text strong { font-size: 16px; font-weight: 700; color: #fff; display: block; }
  .logo-text span { font-size: 11px; color: #999; }
  .nav-links { display: flex; align-items: center; gap: 32px; }
  .nav-links a { color: #ccc; text-decoration: none; font-size: 14px; }
  .nav-links a:hover, .nav-links a.active { color: #fff; }
  .nav-btn { background: #e8192c; color: #fff; padding: 10px 22px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; }
  .nav-btn:hover { background: #c41525; color: #fff; }

  .page-hero { background: radial-gradient(ellipse at 70% 50%, #3a0010 0%, #1a0008 40%, #0a0a0a 70%); padding: 80px 48px; text-align: center; }
  .page-tag { display: inline-flex; align-items: center; gap: 8px; background: rgba(232,25,44,0.15); border: 1px solid rgba(232,25,44,0.3); color: #e8192c; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 100px; margin-bottom: 20px; }
  .page-hero h1 { font-size: 48px; font-weight: 800; margin-bottom: 16px; }
  .page-hero p { font-size: 16px; color: #888; max-width: 500px; margin: 0 auto; line-height: 1.7; }

  .content { max-width: 900px; margin: 0 auto; padding: 80px 48px; }
  .section-title { text-align: center; margin-bottom: 48px; }
  .section-title h2 { font-size: 32px; font-weight: 800; margin-bottom: 12px; }
  .section-title h2 span { color: #e8192c; }
  .section-title p { font-size: 15px; color: #666; }

  .services-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
  .service-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; padding: 28px; transition: border-color 0.2s; }
  .service-card:hover { border-color: rgba(232,25,44,0.3); }
  .service-icon { width: 48px; height: 48px; background: rgba(232,25,44,0.12); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
  .service-icon svg { width: 24px; height: 24px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .service-card h3 { font-size: 17px; font-weight: 700; margin-bottom: 10px; }
  .service-card p { font-size: 14px; color: #666; line-height: 1.7; }

  footer { background: #080808; padding: 24px 48px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; margin-top: 80px; }
  footer p { font-size: 13px; color: #555; }
  footer a { color: #555; text-decoration: none; font-size: 13px; margin-left: 24px; }
  footer a:hover { color: #999; }
</style>
</head>
<body>

<nav>
  <a href="/" class="logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text">
      <strong>Express</strong>
      <span>Minimarket</span>
    </div>
  </a>
  <div class="nav-links">
    <a href="/">Home</a>
    <a href="/about">About</a>
    <a href="/services" class="active">Services</a>
    <a href="/contact">Contact</a>
    <a href="{{ route('login') }}" class="nav-btn">Sign in</a>
  </div>
</nav>

<div class="page-hero">
  <div class="page-tag">⚙️ What we offer</div>
  <h1>Our Services</h1>
  <p>Express Minimarket offers various functionalities designed to facilitate minimarket management.</p>
</div>

<div class="content">
  <div class="section-title">
    <h2>Everything you need in <span>one platform</span></h2>
    <p>Designed for administrators, cashiers and customers.</p>
  </div>

  <div class="services-grid">
    <div class="service-card">
      <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg></div>
      <h3>Product Management</h3>
      <p>Allows you to quickly and organized register, edit, consult and delete products.</p>
    </div>
    <div class="service-card">
      <div class="service-icon"><svg viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></div>
      <h3>Category Management</h3>
      <p>Facilitates the classification of products for better organization and search within the system.</p>
    </div>
    <div class="service-card">
      <div class="service-icon"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg></div>
      <h3>Sales Control</h3>
      <p>Allows registering sales and maintaining a detailed history of completed transactions.</p>
    </div>
    <div class="service-card">
      <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
      <h3>User Management</h3>
      <p>Manages the different system roles including administrators, cashiers and customers.</p>
    </div>
    <div class="service-card">
      <div class="service-icon"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
      <h3>Inventory Control</h3>
      <p>Helps keep product stock up to date and avoids errors in recording existing stock.</p>
    </div>
    <div class="service-card">
      <div class="service-icon"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
      <h3>Basic Reports</h3>
      <p>Provides relevant information to support decision making within the business.</p>
    </div>
  </div>
</div>

<footer>
  <p>© 2026 Express Minimarket — All rights reserved</p>
  <div>
    <a href="/about">About</a>
    <a href="/services">Services</a>
    <a href="/contact">Contact</a>
  </div>
</footer>

</body>
</html>