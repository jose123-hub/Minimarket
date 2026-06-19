<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Express Minimarket — Register</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Inter',sans-serif;
    display:flex;
    min-height:100vh;
    background:#fff;
}

.left{
    width:45%;
    min-height:100vh;
    background:radial-gradient(
        ellipse at 30% 50%,
        #3a0010 0%,
        #1a0008 40%,
        #0a0a0a 70%
    );
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:28px 40px;
}

.left-logo{
    display:flex;
    align-items:center;
    gap:12px;
}

.logo-icon{
    width:40px;
    height:40px;
    background:#e8192c;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-weight:bold;
}

.logo-text strong{
    display:block;
}

.logo-text span{
    color:#999;
    font-size:12px;
}

.left-content{
    padding:40px 0;
}

.left-content h1{
    font-size:48px;
    font-weight:800;
    line-height:1.15;
    margin-bottom:16px;
}

.left-content .accent{
    color:#e8192c;
}

.left-content p{
    color:#888;
    max-width:350px;
    line-height:1.6;
}

.left-footer{
    color:#555;
    font-size:13px;
}

.right{
    flex:1;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#fafafa;
    padding:40px;
}

.form-box{
    width:100%;
    max-width:430px;
}

.form-title{
    font-size:30px;
    font-weight:800;
    margin-bottom:8px;
    color:#111;
}

.form-subtitle{
    color:#888;
    margin-bottom:30px;
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    color:#444;
}

.form-group input{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
    font-size:14px;
}

.form-group input:focus{
    outline:none;
    border-color:#e8192c;
}

.btn-register{
    width:100%;
    border:none;
    background:#e8192c;
    color:white;
    padding:14px;
    border-radius:8px;
    font-weight:700;
    cursor:pointer;
}

.btn-register:hover{
    background:#c41525;
}

.login-link{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#e8192c;
    text-decoration:none;
    font-weight:500;
}

.error-box{
    background:#fff0f0;
    border:1px solid #ffcccc;
    color:#c00;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
}
.password-rules{
    margin-top:8px;
    font-size:12px;
    color:#888;
    line-height:1.6;
}

.password-rules ul{
    margin-top:4px;
    padding-left:18px;
}
</style>

</head>

<body>

<div class="left">

```
<div class="left-logo">
    <div class="logo-icon">E</div>

    <div class="logo-text">
        <strong>Express</strong>
        <span>Minimarket</span>
    </div>
</div>

<div class="left-content">
    <h1>
        Join and earn
        <span class="accent">rewards.</span>
    </h1>

    <p>
        Create your customer account to browse products,
        place orders and redeem loyalty stars.
    </p>
</div>

<div class="left-footer">
    © 2026 Express — All rights reserved
</div>
```

</div>

<div class="right">

```
<div class="form-box">

    <p class="form-title">Create account</p>
    <p class="form-subtitle">
        Register as a customer
    </p>

    @if ($errors->any())
        <div class="error-box">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label>Full name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <div class="form-group">
            <label>Password</label>
            <input
                type="password"
                name="password"
                required
            >
        </div>
        <div class="password-rules">
        Password must contain:
        <ul>
        <li>At least 8 characters</li>
        <li>One uppercase letter</li>
        <li>One lowercase letter</li>
        <li>One number</li>
        <li>One special character</li>
        </ul>
       </div>

        <div class="form-group">
            <label>Confirm password</label>
            <input
                type="password"
                name="password_confirmation"
                required
            >
        </div>

        <button type="submit" class="btn-register">
            Create account
        </button>

        <a href="{{ route('login') }}" class="login-link">
            Already have an account? Sign in
        </a>

    </form>

</div>
```

</div>

</body>
</html>
