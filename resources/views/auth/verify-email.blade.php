<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Verify Email</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; background: #fff; }

  .left {
    width: 45%; min-height: 100vh;
    background: radial-gradient(ellipse at 30% 50%, #3a0010 0%, #1a0008 40%, #0a0a0a 70%);
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 28px 40px;
  }
  .left-logo { display: flex; align-items: center; gap: 12px; }
  .logo-icon { width: 40px; height: 40px; background: #e8192c; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
  .logo-icon svg { width: 22px; height: 22px; fill: #fff; }
  .logo-text strong { font-size: 16px; font-weight: 700; color: #fff; display: block; }
  .logo-text span { font-size: 11px; color: #999; }
  .left-content { padding: 40px 0; }
  .left-content h1 { font-size: clamp(28px, 3.5vw, 48px); font-weight: 800; color: #fff; line-height: 1.15; margin-bottom: 16px; }
  .left-content h1 .accent { color: #e8192c; }
  .left-content p { font-size: 15px; color: #888; line-height: 1.6; max-width: 320px; }
  .left-footer { font-size: 13px; color: #555; }

  .right { flex: 1; display: flex; align-items: center; justify-content: center; padding: 48px 40px; background: #fafafa; }
  .form-box { width: 100%; max-width: 400px; }

  .icon-circle {
    width: 64px; height: 64px; background: #fff0f2;
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 24px;
  }
  .icon-circle svg { width: 28px; height: 28px; stroke: #e8192c; fill: none; stroke-width: 1.8; }

  .form-title { font-size: 24px; font-weight: 800; color: #111; text-align: center; margin-bottom: 10px; }
  .form-subtitle { font-size: 14px; color: #888; text-align: center; line-height: 1.6; margin-bottom: 24px; }

  .success-box {
    background: #f0fff4; border: 1px solid #bbf7d0;
    border-radius: 8px; padding: 12px 14px;
    font-size: 13px; color: #16a34a; margin-bottom: 20px;
    text-align: center;
  }

  .btn-primary {
    width: 100%; padding: 13px; background: #e8192c; color: #fff;
    border: none; border-radius: 8px; font-size: 15px; font-weight: 700;
    cursor: pointer; transition: background 0.2s; margin-bottom: 12px;
  }
  .btn-primary:hover { background: #c41525; }

  .btn-secondary {
    width: 100%; padding: 13px; background: transparent; color: #999;
    border: 1px solid #e0e0e0; border-radius: 8px; font-size: 14px;
    font-weight: 500; cursor: pointer; transition: all 0.2s;
  }
  .btn-secondary:hover { border-color: #ccc; color: #555; }

  .hint { text-align: center; font-size: 12px; color: #bbb; margin-top: 20px; line-height: 1.6; }
</style>
</head>
<body>

<div class="left">
  <div class="left-logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </div>
    <div class="logo-text">
      <strong>Express</strong>
      <span>Minimarket</span>
    </div>
  </div>

  <div class="left-content">
    <h1>Activate your <span class="accent">account.</span></h1>
    <p>Verify your email address and start shopping, earning stars and redeeming rewards through the Express loyalty program.</p>
  </div>

  <div class="left-footer">© 2026 Express — All rights reserved</div>
</div>

<div class="right">
  <div class="form-box">

    <div class="icon-circle">
      <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
    </div>

    <p class="form-title">Verify your email</p>
    <p class="form-subtitle">We've sent a verification link to your email address. Please verify your account before continuing.</p>

    @if(session('status') == 'verification-link-sent')
      <div class="success-box">A new verification email has been sent successfully.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button type="submit" class="btn-primary">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 12px">
      @csrf
      <button type="submit" class="btn-secondary">Sign Out</button>
    </form>

    <p class="hint">Check your spam folder if you don't see the email in your inbox.</p>

  </div>
</div>

</body>
</html>