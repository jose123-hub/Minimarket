<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express Minimarket — Contact</title>
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

  .page-hero { background: radial-gradient(ellipse at 50% 50%, #3a0010 0%, #1a0008 40%, #0a0a0a 70%); padding: 80px 48px; text-align: center; }
  .page-tag { display: inline-flex; align-items: center; gap: 8px; background: rgba(232,25,44,0.15); border: 1px solid rgba(232,25,44,0.3); color: #e8192c; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 100px; margin-bottom: 20px; }
  .page-hero h1 { font-size: 48px; font-weight: 800; margin-bottom: 16px; }
  .page-hero p { font-size: 16px; color: #888; max-width: 500px; margin: 0 auto; line-height: 1.7; }

  .content { max-width: 900px; margin: 0 auto; padding: 80px 48px; display: grid; grid-template-columns: 1fr 1fr; gap: 48px; }

  .contact-info h2 { font-size: 26px; font-weight: 800; margin-bottom: 8px; }
  .contact-info h2 span { color: #e8192c; }
  .contact-info p { font-size: 14px; color: #666; line-height: 1.7; margin-bottom: 32px; }
  .contact-item { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 20px; }
  .contact-item-icon { width: 40px; height: 40px; background: rgba(232,25,44,0.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .contact-item-icon svg { width: 18px; height: 18px; stroke: #e8192c; fill: none; stroke-width: 1.8; }
  .contact-item-text strong { font-size: 13px; font-weight: 600; color: #fff; display: block; margin-bottom: 2px; }
  .contact-item-text span { font-size: 13px; color: #666; }

  .contact-form h2 { font-size: 26px; font-weight: 800; margin-bottom: 24px; }
  .form-group { margin-bottom: 16px; }
  .form-group label { display: block; font-size: 13px; font-weight: 500; color: #888; margin-bottom: 8px; }
  .form-group input, .form-group textarea {
    width: 100%; padding: 12px 14px;
    background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px; font-size: 14px; color: #fff; outline: none; transition: border-color 0.2s;
  }
  .form-group input:focus, .form-group textarea:focus { border-color: #e8192c; }
  .form-group textarea { resize: vertical; min-height: 120px; }
  .btn-submit { width: 100%; padding: 13px; background: #e8192c; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
  .btn-submit:hover { background: #c41525; }

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
    <a href="/about">About</a>
    <a href="/services">Services</a>
    <a href="/contact" class="active">Contact</a>
    <a href="{{ route('login') }}" class="nav-btn">Sign in</a>
  </div>
</nav>

<div class="page-hero">
  <div class="page-tag">📬 Get in touch</div>
  <h1>Contact Us</h1>
  <p>If you want more information about Express Minimarket or have any questions, contact us.</p>
</div>

<div class="content">
  <div class="contact-info">
    <h2>Get in <span>touch</span></h2>
    <p>You can reach us through the following channels. We will be happy to assist you and help you with whatever you need.</p>

    <div class="contact-item">
      <div class="contact-item-icon">
        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      </div>
      <div class="contact-item-text">
        <strong>Email</strong>
        <span>soporte@minimarketexpress.com</span>
      </div>
    </div>

    <div class="contact-item">
      <div class="contact-item-icon">
        <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.06 1.18 2 2 0 012 .06h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92z"/></svg>
      </div>
      <div class="contact-item-text">
        <strong>Phone</strong>
        <span>+51 907 944 033</span>
      </div>
    </div>

    <div class="contact-item">
      <div class="contact-item-icon">
        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      <div class="contact-item-text">
        <strong>Address</strong>
        <span>Lima, Perú</span>
      </div>
    </div>

    <div class="contact-item">
      <div class="contact-item-icon">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div class="contact-item-text">
        <strong>Business hours</strong>
        <span>Monday to Friday, 8:00 a.m. to 6:00 p.m.</span>
      </div>
    </div>
  </div>

  <div class="contact-form">
    <h2>Send us a message</h2>
    <form method="POST" action="{{ route('contact.send') }}">
  @csrf

  @if(session('success'))
    <div style="background:#f0fff4; border:1px solid #bbf7d0; border-radius:8px; padding:12px 14px; font-size:13px; color:#16a34a; margin-bottom:16px;">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fff0f0; border:1px solid #fcc; border-radius:8px; padding:12px 14px; font-size:13px; color:#c00; margin-bottom:16px;">
      {{ $errors->first() }}
    </div>
  @endif

  <div class="form-group">
    <label>Full name</label>
    <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name">
  </div>
  <div class="form-group">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com">
  </div>
  <div class="form-group">
    <label>Subject</label>
    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="How can we help you?">
  </div>
  <div class="form-group">
    <label>Message</label>
    <textarea name="message" placeholder="Write your message here...">{{ old('message') }}</textarea>
  </div>
  <button type="submit" class="btn-submit">Send message</button>
</form>
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