<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teacher Portal | Compact UI</title>

    <!-- Inter Font: Standard for Professional Dashboards -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --sb-width: 220px; /* Slimmer sidebar */
            --accent: #2563eb;
            --bg-main: #fcfdfe;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --font-sm: 13px; /* Professional standard size */
            --font-md: 14px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            font-size: var(--font-md);
            letter-spacing: -0.01em;
        }

        /* Sidebar - Sharp & Professional */
        .sidebar {
            width: var(--sb-width);
            height: 100vh;
            position: fixed;
            background: #ffffff;
            border-right: 1px solid var(--border);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            font-size: 16px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border);
        }

        .nav-section-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            padding: 1.5rem 1.5rem 0.5rem;
            letter-spacing: 0.05em;
        }

        .nav-link {
            padding: 0.6rem 1.2rem;
            margin: 0.1rem 0.8rem;
            font-size: var(--font-sm);
            font-weight: 500;
            color: var(--text-muted);
            border-radius: 6px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .nav-link i {
            width: 18px;
            font-size: 14px;
            margin-right: 10px;
            opacity: 0.7;
        }

        .nav-link:hover {
            background: #f8fafc;
            color: var(--accent);
        }

        .nav-link.active {
            background: #f1f5f9;
            color: var(--accent);
            font-weight: 600;
        }

        /* Top Header - Compact */
        .top-header {
            height: 56px;
            margin-left: var(--sb-width);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }

        .search-container input {
            font-size: var(--font-sm);
            border: 1px solid var(--border);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            width: 280px;
            outline: none;
        }

        /* Main Area */
        .main-wrapper {
            margin-left: var(--sb-width);
            padding: 1.5rem;
        }

        .page-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Card Customization */
        .card-custom {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem;
            height: 100%;
        }

        .btn-compact {
            font-size: var(--font-sm);
            padding: 0.4rem 0.8rem;
            font-weight: 500;
            border-radius: 6px;
        }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .top-header, .main-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="bg-primary text-white rounded me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 14px;">T</div>
        <span>ParentHub</span>
    </div>

    <div class="nav-section-label">Academic</div>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}" href="{{ route('parent.dashboard') }}"><i class="fa-solid fa-grip"></i> Dashboard</a>
        <a class="nav-link {{ request()->routeIs('parent.attendance') ? 'active' : '' }}" href="{{ route('parent.attendance') }}">
            <i class="fa-solid fa-calendar-check"></i> Attendance
        </a>
        <a class="nav-link {{ request()->routeIs('parent.exam-reports') ? 'active' : '' }}" href="{{ route('parent.exam-reports') }}"><i class="fa-solid fa-file-pen"></i> Exam Reports</a>
        <a class="nav-link {{ request()->routeIs('parent.finance.*') ? 'active' : '' }}" href="{{ route('parent.finance.dashboard') }}"><i class="fa-solid fa-wallet"></i> Finance</a>

    </nav>

    <div class="nav-section-label">Support</div>
    <nav class="nav flex-column">
        <a class="nav-link {{ request()->routeIs('parent.messages') ? 'active' : '' }}" href="{{ route('parent.messages') }}"><i class="fa-solid fa-paper-plane"></i> Messages</a>
    </nav>

    <div class="mt-auto border-top">
        <a class="nav-link mt-3" href="#"><i class="fa-solid fa-gear"></i> Settings</a>
        <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
            @csrf
            <a class="nav-link mb-3 text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> Sign out
            </a>
        </form>
    </div>
</aside>

<header class="top-header">
    <div class="search-container">
        <input type="text" placeholder="Quick search...">
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <i class="fa-regular fa-bell text-muted cursor-pointer"></i>
        <div class="vr mx-1" style="height: 20px; color: var(--border)"></div>
        <div class="dropdown">
            <div class="d-flex align-items-center gap-2 cursor-pointer" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="fw-medium d-none d-sm-inline" style="font-size: var(--font-sm);">{{ auth()->user()->name }}</span>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=32&background=f1f5f9&color=64748b" class="rounded-circle" width="30">
            </div>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item small fw-medium py-2" href="{{ route('password.change') }}"><i class="fas fa-key me-2 text-muted"></i> Change Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item small fw-medium py-2 text-danger border-0 bg-transparent w-100 text-start"><i class="fas fa-sign-out-alt me-2"></i> Sign Out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
<main class="main-wrapper">
    @yield('content')
</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>