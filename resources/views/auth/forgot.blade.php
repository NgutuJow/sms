<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password — School Management System</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root { color-scheme: dark; font-family: 'Nunito', sans-serif; background: #0F172A; color: #E2E8F0; }
    * { box-sizing: border-box; }
    body { margin:0; min-height:100vh; display:grid; place-items:center; padding:24px; background: radial-gradient(circle at top left, rgba(56,189,248,0.12), transparent 24%), linear-gradient(180deg,#020617 0%,#0F172A 100%); }
    .card { width: min(520px, 96%); border-radius:20px; background: rgba(15,23,42,0.92); border:1px solid rgba(148,163,184,0.08); padding:32px; box-shadow:0 30px 80px rgba(2,6,23,0.6); }
    .brand { display:flex; gap:12px; align-items:center; font-weight:700; color:#F8FAFC; margin-bottom:8px; }
    .brand-mark { width:44px; height:44px; border-radius:12px; display:grid; place-items:center; background:#38BDF8; color:#052029; font-weight:800; }
    h1 { margin:6px 0 12px; font-size:1.6rem; color:#F8FAFC; }
    p.lead { margin:0 0 20px; color:#94A3B8; }
    label { display:block; margin-bottom:8px; color:#CBD5E1; font-size:0.95rem; }
    input[type="email"] { width:100%; padding:12px 14px; border-radius:12px; border:1px solid rgba(148,163,184,0.12); background:rgba(15,23,42,0.7); color:#F8FAFC; font-size:1rem; outline:none; }
    input:focus { border-color:#38BDF8; box-shadow:0 0 0 6px rgba(56,189,248,0.06); }
    .btn { margin-top:18px; width:100%; padding:12px 14px; border-radius:12px; border:none; background:linear-gradient(90deg,#06B6D4,#0EA5E9); color:#041826; font-weight:700; cursor:pointer; }
    .note { margin-top:14px; color:#94A3B8; font-size:0.95rem; }
    .links { margin-top:18px; display:flex; justify-content:space-between; align-items:center; }
    .links a { color:#38BDF8; text-decoration:none; }
    .alert { margin-top:12px; padding:10px 12px; border-radius:10px; background:rgba(16,185,129,0.08); color:#86efac; border:1px solid rgba(16,185,129,0.12); }
  </style>
</head>
<body>
  <div class="card">
    <div class="brand">
      <div class="brand-mark">SMS</div>
      <div>School Management System</div>
    </div>

    <h1>Password reset</h1>
    <p class="lead">Enter the email associated with your account and we'll send a link to reset your password.</p>

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <div>
        <label for="email">Email address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
      </div>

      <button type="submit" class="btn">Send reset link</button>

      @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert" style="background:rgba(248,113,113,0.06); color:#FCA5A5; border-color:rgba(248,113,113,0.14);">{{ $errors->first() }}</div>
      @endif

      <div class="links">
        <a href="{{ url('/') }}">Back to home</a>
        <a href="{{ route('login') }}">Back to login</a>
      </div>

      <p class="note">If you don't receive an email, check your spam folder or contact your administrator.</p>
    </form>
  </div>
</body>
</html>