<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express — Reset Password</title>
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
  .form-subtitle { font-size: 14px; color: #888; text-align: center; line-height: 1.6; margin-bottom: 28px; }

  .form-group { margin-bottom: 18px; }
  .form-group label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 8px; }
  .input-wrap { position: relative; }
  .input-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; stroke: #aaa; fill: none; stroke-width: 1.8; }
  .input-wrap input {
    width: 100%; padding: 11px 14px 11px 38px;
    border: 1px solid #e0e0e0; border-radius: 8px;
    font-size: 14px; color: #111; background: #fff;
    outline: none; transition: border-color 0.2s;
  }
  .input-wrap input:focus { border-color: #e8192c; }

  .error-msg {
    background: #fff0f0; border: 1px solid #fcc;
    border-radius: 8px; padding: 10px 14px;
    font-size: 13px; color: #c00; margin-bottom: 18px;
  }

  .password-strength { margin-top: 8px; }
  .strength-bar { height: 4px; background: #eee; border-radius: 2px; overflow: hidden; margin-bottom: 4px; }
  .strength-fill { height: 100%; border-radius: 2px; transition: width 0.3s, background 0.3s; width: 0%; }
  .strength-text { font-size: 11px; color: #aaa; }

  .btn-primary {
    width: 100%; padding: 13px; background: #e8192c; color: #fff;
    border: none; border-radius: 8px; font-size: 15px; font-weight: 700;
    cursor: pointer; transition: background 0.2s; margin-bottom: 16px;
  }
  .btn-primary:hover { background: #c41525; }

  .back-link { display: flex; align-items: center; justify-content: center; gap: 6px; font-size: 13px; color: #999; text-decoration: none; transition: color 0.2s; }
  .back-link:hover { color: #e8192c; }
  .back-link svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; }
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
    <h1>Create a new <span class="accent">password.</span></h1>
    <p>Choose a strong password to keep your Express account safe and secure.</p>
  </div>

  <div class="left-footer">© 2026 Express — All rights reserved</div>
</div>

<div class="right">
  <div class="form-box">

    <div class="icon-circle">
      <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    </div>

    <p class="form-title">Reset your password</p>
    <p class="form-subtitle">Enter your new password below. Make sure it's at least 8 characters long.</p>

    @if($errors->any())
      <div class="error-msg">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <div class="form-group">
        <label>Email address</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <input type="email" name="email" value="{{ old('email', request('email')) }}" placeholder="your@email.com" required>
        </div>
      </div>

      <div class="form-group">
        <label>New password</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          <input type="password" name="password" id="password" placeholder="••••••••" required oninput="checkStrength(this.value)">
        </div>
        <div class="password-strength">
          <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
          <span class="strength-text" id="strength-text"></span>
        </div>
      </div>

      <div class="form-group">
        <label>Confirm new password</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          <input type="password" name="password_confirmation" placeholder="••••••••" required>
        </div>
      </div>

      <button type="submit" class="btn-primary">Reset Password</button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
      <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      Back to Sign in
    </a>

  </div>
</div>

<script>
function checkStrength(password) {
  const fill = document.getElementById('strength-fill');
  const text = document.getElementById('strength-text');
  let strength = 0;

  if (password.length >= 8) strength++;
  if (/[A-Z]/.test(password)) strength++;
  if (/[0-9]/.test(password)) strength++;
  if (/[^A-Za-z0-9]/.test(password)) strength++;

  const levels = [
    { width: '0%',   color: '#eee',    label: '' },
    { width: '25%',  color: '#e8192c', label: 'Weak' },
    { width: '50%',  color: '#f59e0b', label: 'Fair' },
    { width: '75%',  color: '#3b82f6', label: 'Good' },
    { width: '100%', color: '#22c55e', label: 'Strong' },
  ];

  fill.style.width = levels[strength].width;
  fill.style.background = levels[strength].color;
  text.textContent = levels[strength].label;
  text.style.color = levels[strength].color;
}
</script>

</body>
</html>