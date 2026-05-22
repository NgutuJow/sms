<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>School Management System | Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      color-scheme: dark;
      font-family: 'Nunito', sans-serif;
      background: #0F172A;
      color: #E2E8F0;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      background: radial-gradient(circle at top left, rgba(56, 189, 248, 0.2), transparent 24%),
                  linear-gradient(180deg, #020617 0%, #0F172A 100%);
      display: grid;
      place-items: center;
      padding: 24px;
    }

    .login-shell {
      width: min(100%, 960px);
      display: grid;
      grid-template-columns: 1.1fr 0.9fr;
      gap: 32px;
      align-items: center;
    }

    .hero-panel,
    .login-panel {
      border-radius: 28px;
      background: rgba(15, 23, 42, 0.92);
      border: 1px solid rgba(148, 163, 184, 0.12);
      box-shadow: 0 35px 90px rgba(15, 23, 42, 0.35);
    }

    .hero-panel {
      padding: 42px 40px;
    }

    .hero-panel h1 {
      margin: 0;
      font-size: clamp(2.5rem, 4vw, 3.8rem);
      line-height: 1.02;
      letter-spacing: -0.03em;
      color: #F8FAFC;
    }

    .hero-panel p {
      margin: 24px 0 0;
      color: #94A3B8;
      font-size: 1.05rem;
      line-height: 1.8;
      max-width: 560px;
    }

    .hero-list {
      margin: 28px 0 0;
      padding-left: 1.1rem;
      color: #CBD5E1;
      line-height: 1.9;
    }

    .hero-list li {
      margin-bottom: 12px;
    }

    .login-panel {
      padding: 38px 36px;
    }

    .brand {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      color: #F8FAFC;
      margin-bottom: 26px;
      font-size: 1.25rem;
    }

    .brand-mark {
      width: 42px;
      height: 42px;
      border-radius: 14px;
      display: grid;
      place-items: center;
      background: #38BDF8;
      color: #0F172A;
      font-weight: 700;
      font-size: 1.1rem;
      letter-spacing: -0.04em;
    }

    .login-panel h2 {
      margin: 0;
      font-size: 1.9rem;
      color: #F8FAFC;
    }

    .login-panel p {
      margin: 12px 0 24px;
      color: #94A3B8;
      line-height: 1.75;
    }

    .form-group {
      margin-bottom: 18px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      color: #CBD5E1;
      text-transform: uppercase;
      letter-spacing: 0.025em;
    }

    input {
      width: 100%;
      padding: 12px 14px;
      border-radius: 12px;
      border: 1px solid rgba(148, 163, 184, 0.18);
      background: rgba(15, 23, 42, 0.75);
      color: #F8FAFC;
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    input:focus {
      border-color: #38BDF8;
      box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.12);
    }

    .login-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 22px;
    }

    .login-actions a {
      color: #38BDF8;
      text-decoration: none;
      font-size: 0.95rem;
    }

    .btn-submit {
      width: 100%;
      padding: 14px 16px;
      border: none;
      border-radius: 16px;
      background: linear-gradient(135deg, #38BDF8 0%, #0EA5E9 100%);
      color: #0F172A;
      font-weight: 700;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 18px 45px rgba(56, 189, 248, 0.25);
    }

    .alert {
      margin-top: 16px;
      padding: 14px 16px;
      border-radius: 16px;
      background: rgba(248, 113, 113, 0.12);
      color: #FCA5A5;
      border: 1px solid rgba(248, 113, 113, 0.25);
      font-size: 0.95rem;
    }

    .footer-note {
      margin-top: 28px;
      color: #94A3B8;
      font-size: 0.92rem;
      line-height: 1.75;
    }

    @media (max-width: 900px) {
      .login-shell {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="login-shell">
    <div class="hero-panel">
      <div class="brand">
        <div class="brand-mark">SMS</div>
        <span>School Management System</span>
      </div>
      <h1>Secure access for staff and parents.</h1>
      <p>Sign in to manage student records, fees, attendance, reports, and communication from one central dashboard.</p>
      <ul class="hero-list">
        <li>Fast and secure login with clear workflow access</li>
        <li>Designed for modern school administration</li>
        <li>Easy access to student finances and performance</li>
      </ul>
    </div>

    <div class="login-panel">
      <div class="brand">
        <div class="brand-mark">L</div>
        <span>Welcome back</span>
      </div>
      <h2>Sign in to your account</h2>
      <p>Enter your school credentials to continue.</p>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <div class="login-actions">
          <div></div>
          <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="btn-submit">Login</button>

        @if ($errors->any())
          <div class="alert">{{ $errors->first() }}</div>
        @endif
      </form>

      <p class="footer-note">Need help accessing your account? Contact your school administrator for support.</p>
    </div>
  </div>
</body>
</html>