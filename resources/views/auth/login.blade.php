<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express Minimarket — Sign in</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; background: #fff; }

  .left {
    width: 45%; min-height: 100vh; position: relative; overflow: hidden;
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

  .right {
    flex: 1; display: flex; align-items: center; justify-content: center;
    padding: 48px 40px; background: #fafafa;
  }
  .form-box { width: 100%; max-width: 400px; }

  .toggle {
    display: flex; background: #f0f0f0; border-radius: 10px; padding: 4px;
    margin-bottom: 32px;
  }
  .toggle-btn {
    flex: 1; padding: 10px; border: none; background: transparent;
    border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;
    color: #888; transition: all 0.2s;
  }
  .toggle-btn.active { background: #fff; color: #111; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }

  .form-title { font-size: 26px; font-weight: 800; color: #111; margin-bottom: 6px; }
  .form-subtitle { font-size: 14px; color: #888; margin-bottom: 28px; }

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

  .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
  .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #555; cursor: pointer; }
  .checkbox-label input[type="checkbox"] { accent-color: #e8192c; width: 15px; height: 15px; }
  .forgot { font-size: 13px; color: #e8192c; text-decoration: none; font-weight: 500; }
  .forgot:hover { text-decoration: underline; }

  .btn-login {
    width: 100%; padding: 13px; background: #e8192c; color: #fff;
    border: none; border-radius: 8px; font-size: 15px; font-weight: 700;
    cursor: pointer; transition: background 0.2s;
  }
  .btn-login:hover { background: #c41525; }

  .btn-register {
    width: 100%; padding: 13px; background: transparent; color: #e8192c;
    border: 2px solid #e8192c; border-radius: 8px; font-size: 15px; font-weight: 700;
    cursor: pointer; margin-top: 12px; transition: all 0.2s; text-decoration: none;
    display: block; text-align: center;
  }
  .btn-register:hover { background: #e8192c; color: #fff; }

  .test-accounts {
    margin-top: 20px; background: #f5f5f5; border-radius: 8px;
    padding: 12px 14px; display: flex; align-items: flex-start; gap: 10px;
  }
  .test-accounts svg { width: 16px; height: 16px; stroke: #e8192c; fill: none; stroke-width: 1.8; flex-shrink: 0; margin-top: 1px; }
  .test-accounts-text strong { font-size: 13px; color: #333; display: block; margin-bottom: 2px; }
  .test-accounts-text span { font-size: 12px; color: #888; }

  .error-msg {
    background: #fff0f0; border: 1px solid #fcc; border-radius: 8px;
    padding: 10px 14px; font-size: 13px; color: #c00; margin-bottom: 18px;
  }

  .hidden { display: none; }
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

  <div class="left-content" id="left-content">
    <h1 id="left-title">Manage your minimarket <span class="accent">with speed.</span></h1>
    <p id="left-desc">Point of sale, inventory, purchasing, promotions and loyalty in one platform.</p>
  </div>

  <div class="left-footer">© 2026 Express — All rights reserved</div>
</div>

<div class="right">
  <div class="form-box">

    <div class="toggle">
      <button class="toggle-btn active" id="btn-employee" onclick="switchTab('employee')">I'm an employee</button>
      <button class="toggle-btn" id="btn-client" onclick="switchTab('client')">I'm a customer</button>
    </div>

    @php
    $errorTab = session('login_error_type', old('login_type', 'employee'));
    @endphp

    @if ($errors->any())
    <div 
    class="error-msg" 
    id="login-error-box" 
    data-error-tab="{{ $errorTab }}"
     >
     {{ $errors->first() }}
    </div>
    @endif
    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div id="title-employee">
        <p class="form-title">Welcome back</p>
        <p class="form-subtitle">Enter your system credentials</p>
      </div>
      <div id="title-client" class="hidden">
        <p class="form-title">Hello again</p>
        <p class="form-subtitle">Sign in to your Express customer account</p>
      </div>

      <input type="hidden" name="login_type" id="login_type" value="{{ old('login_type', 'employee') }}">

      <div class="form-group">
        <label>Email</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="email@express.com" required autofocus>
        </div>
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="input-wrap">
          <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          <input type="password" name="password" placeholder="••••••••" required>
        </div>
      </div>

      <div class="form-row">
        <label class="checkbox-label">
          <input type="checkbox" name="remember"> Remember me
        </label>
        <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
      </div>

      <button type="submit" class="btn-login">Sign in</button>

      <a href="{{ route('register') }}" class="btn-register hidden" id="btn-register-link">Create new account</a>

    </form>

    <div class="test-accounts" id="test-accounts-employee">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <div class="test-accounts-text">
        <strong>Test accounts</strong>
        <span>admin / password · cashier / password</span>
      </div>
    </div>

    <div class="test-accounts hidden" id="test-accounts-client">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <div class="test-accounts-text">
        <strong>Test accounts</strong>
        <span>client@example.com / password (or create a new one)</span>
      </div>
    </div>

  </div>
</div>

<script>
function switchTab(tab) {
  const isEmployee = tab === 'employee';

  document.getElementById('btn-employee').classList.toggle('active', isEmployee);
  document.getElementById('btn-client').classList.toggle('active', !isEmployee);

  document.getElementById('title-employee').classList.toggle('hidden', !isEmployee);
  document.getElementById('title-client').classList.toggle('hidden', isEmployee);

  document.getElementById('btn-register-link').classList.toggle('hidden', isEmployee);

  document.getElementById('test-accounts-employee').classList.toggle('hidden', !isEmployee);
  document.getElementById('test-accounts-client').classList.toggle('hidden', isEmployee);

  document.getElementById('login_type').value = tab;

  document.getElementById('left-title').innerHTML = isEmployee
    ? 'Manage your minimarket <span class="accent">with speed.</span>'
    : 'Shop and earn <span class="accent">stars.</span>';

  document.getElementById('left-desc').textContent = isEmployee
    ? 'Point of sale, inventory, purchasing, promotions and loyalty in one platform.'
    : 'Browse the catalog, check your orders and redeem your Express stars for discounts.';

  const errorBox = document.getElementById('login-error-box');

  if (errorBox) {
    const errorTab = errorBox.getAttribute('data-error-tab');

    if (errorTab === tab) {
      errorBox.style.display = 'block';
    } else {
      errorBox.style.display = 'none';
    }
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const selectedTab = "{{ old('login_type', session('login_error_type', 'employee')) }}";

  switchTab(selectedTab);
});
</script>

</body>
</html>
