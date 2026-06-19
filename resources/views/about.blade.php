<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express Minimarket — About Us</title>
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
  .nav-links a { color: #ccc; text-decoration: none; font-size: 14px; transition: color 0.2s; }
  .nav-links a:hover, .nav-links a.active { color: #fff; }
  .nav-btn { background: #e8192c; color: #fff; padding: 10px 22px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; }
  .nav-btn:hover { background: #c41525; color: #fff; }

  .page-hero { background: radial-gradient(ellipse at 30% 50%, #3a0010 0%, #1a0008 40%, #0a0a0a 70%); padding: 80px 48px; text-align: center; }
  .page-tag { display: inline-flex; align-items: center; gap: 8px; background: rgba(232,25,44,0.15); border: 1px solid rgba(232,25,44,0.3); color: #e8192c; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 100px; margin-bottom: 20px; }
  .page-hero h1 { font-size: 48px; font-weight: 800; margin-bottom: 16px; }
  .page-hero p { font-size: 16px; color: #888; max-width: 500px; margin: 0 auto; line-height: 1.7; }

  .content { max-width: 800px; margin: 0 auto; padding: 80px 48px; }
  .section { margin-bottom: 60px; }
  .section h2 { font-size: 28px; font-weight: 800; margin-bottom: 20px; color: #fff; }
  .section h2 span { color: #e8192c; }
  .section p { font-size: 15px; color: #888; line-height: 1.8; margin-bottom: 16px; }
  .divider { height: 1px; background: rgba(255,255,255,0.06); margin: 48px 0; }

  .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 32px; }
  .card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 24px; }
  .card:hover { border-color: rgba(232,25,44,0.3); }
  .card-icon { width: 40px; height: 40px; background: rgba(232,25,44,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
  .card-icon svg { width: 20px; height: 20px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .card h3 { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
  .card p { font-size: 13px; color: #666; line-height: 1.6; }

  footer { background: #080808; padding: 24px 48px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; }
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
    <a href="/about" class="active">About</a>
    <a href="/services">Services</a>
    <a href="/contact">Contact</a>
    <a href="{{ route('login') }}" class="nav-btn">Sign in</a>
  </div>
</nav>

<div class="page-hero">
  <div class="page-tag">🏪 Our story</div>
  <h1>About Us</h1>
  <p>Learn about the origin and purpose of Express Minimarket.</p>
</div>

<div class="content">
  <div class="section">
    <h2>Our <span>History</span></h2>
    <p>Minimarket Express was born from the need to improve the management of small and medium-sized product sales businesses. During the analysis of different systems, problems were identified such as inefficient inventory control, manual sales recording and difficulty in managing information in an organized way.</p>
    <p>With the aim of solving these difficulties, a web platform was developed that centralizes the administration of products, categories, users and sales in a single system. Our purpose is to offer a practical, secure and easy-to-use tool that contributes to business growth.</p>
    <p>Our vision is to continue improving the system through new functionalities that optimize processes and reduce operational errors.</p>
  </div>

  <div class="divider"></div>

  <div class="section">
    <h2>Our <span>Values</span></h2>
    <div class="cards">
      <div class="card">
        <div class="card-icon"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
        <h3>Simplicity</h3>
        <p>We design intuitive tools that anyone can use without prior training.</p>
      </div>
      <div class="card">
        <div class="card-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <h3>Security</h3>
        <p>We protect business data with secure access controls and differentiated roles.</p>
      </div>
      <div class="card">
        <div class="card-icon"><svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
        <h3>Growth</h3>
        <p>We help businesses grow by reducing errors and improving operational efficiency.</p>
      </div>
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