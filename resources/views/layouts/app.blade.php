<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="School Management System">
    <title>School Management System</title>

    <!-- Professional Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --sb-width: 240px;
            --accent: #2563eb;
            --bg-main: #fcfdfe;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --font-sm: 13px;
            --font-md: 14px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-dark);
            font-size: var(--font-md);
            letter-spacing: -0.01em;
            margin: 0;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sb-width);
            height: 100vh;
            position: fixed;
            background: #ffffff;
            border-right: 1px solid var(--border);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
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
            transition: all 0.15s ease;
        }

        .nav-link i {
            width: 20px;
            font-size: 14px;
            margin-right: 10px;
        }

        .nav-link:hover, .nav-link.active {
            background: #f1f5f9;
            color: var(--accent);
        }

        /* Header & Content */
        .top-header {
            height: 56px;
            margin-left: var(--sb-width);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .main-wrapper {
            margin-left: var(--sb-width);
            padding: 2rem;
            min-height: calc(100vh - 56px);
        }

        .search-container input {
            font-size: var(--font-sm);
            border: 1px solid var(--border);
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            width: 300px;
            outline: none;
        }

        /* Form Refinements */
        .form-label {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            font-size: 13px;
            padding: 0.55rem 0.8rem;
            border-color: var(--border);
        }

        .form-control::placeholder {
            font-size: 12.5px;
            color: #94a3b8;
            opacity: 0.8;
        }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .top-header, .main-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>

<aside class="sidebar" id="sidebarMenu">
    <div class="sidebar-brand">
        <div class="bg-primary text-white rounded me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 14px;">S</div>
        <span>{{ auth()->check() && auth()->user()->hasRole('accountant') ? 'Finance Portal' : 'School Admin' }}</span>
    </div>

    @auth
        @if(auth()->user()->hasRole('admin'))
        <div class="nav-section-label">Main Menu</div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a class="nav-link {{ request()->is('school*') ? 'active' : '' }}" href="/school"><i class="fa-solid fa-school"></i> School</a>
            <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="fa-solid fa-users"></i> User Management</a>
            <a class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}" href="/teachers"><i class="fa-solid fa-chalkboard-user"></i> Teachers</a>
            <a class="nav-link {{ request()->is('academic*') && !request()->is('academic/attendance*') ? 'active' : '' }}" href="/academic"><i class="fa-solid fa-book"></i> Academic</a>
            <a class="nav-link {{ request()->is('students*') ? 'active' : '' }}" href="/students"><i class="fa-solid fa-user-graduate"></i> Students</a>
        </nav>
        @endif

        <div class="nav-section-label">{{ auth()->user()->hasRole('accountant') ? 'Finance Management' : 'Management' }}</div>
        <nav class="nav flex-column">
            @if(auth()->user()->hasRole('admin'))
            <a class="nav-link {{ request()->is('exams*') ? 'active' : '' }}" href="/exams"><i class="fa-solid fa-file-signature"></i> Examinations</a>
            <a class="nav-link {{ request()->is('promotions*') ? 'active' : '' }}" href="/promotions"><i class="fa-solid fa-arrow-up-right-dots"></i> Promotions</a>
            <a class="nav-link {{ request()->routeIs('announcements.index') ? 'active' : '' }}" href="{{ route('announcements.index') }}"><i class="fa-solid fa-bullhorn"></i> Announcements</a>
            <a class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}" href="{{ route('chat.index') }}"><i class="fa-brands fa-whatsapp"></i> WhatsApp Chat</a>
            @endif
            
            <a class="nav-link {{ request()->is('finance*') ? 'active' : '' }}" href="{{ route('finance.index') }}"><i class="fa-solid fa-wallet"></i> Finance</a>
            
            @if(auth()->user()->hasRole('admin'))
            <a class="nav-link {{ request()->is('academic/attendance*') ? 'active' : '' }}" href="/academic/attendance"><i class="fa-solid fa-calendar-check"></i> Attendance</a>
            @endif
        </nav>

        @if(auth()->user()->hasRole('accountant'))
        <div class="nav-section-label">Finance Quick Links</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('finance.invoices') }}"><i class="fa-solid fa-file-invoice-dollar"></i> Invoices</a>
            <a class="nav-link" href="{{ route('finance.expenses.index') }}"><i class="fa-solid fa-money-bill-transfer"></i> Expenses</a>
            <a class="nav-link" href="{{ route('finance.payroll.index') }}"><i class="fa-solid fa-users-viewfinder"></i> Payroll</a>
            <a class="nav-link" href="{{ route('finance.fee-structures.index') }}"><i class="fa-solid fa-list-check"></i> Fee Structures</a>
        </nav>
        @endif

        <div class="nav-section-label">Reports</div>
        <nav class="nav flex-column mb-4">
            <a class="nav-link {{ request()->routeIs('finance.reports') ? 'active' : '' }}" href="{{ route('finance.reports') }}"><i class="fa-solid fa-chart-line"></i> Financial Reports</a>
            <a class="nav-link {{ request()->routeIs('finance.reports.year-end') ? 'active' : '' }}" href="{{ route('finance.reports.year-end') }}"><i class="fa-solid fa-calendar-alt"></i> Year-end Summary</a>
            <a class="nav-link {{ request()->routeIs('finance.pdf-export.index') ? 'active' : '' }}" href="{{ route('finance.pdf-export.index') }}"><i class="fa-solid fa-file-pdf"></i> PDF Exports</a>
        </nav>

        <div class="mt-auto border-top">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a class="nav-link my-3 text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign out
                </a>
            </form>
        </div>
    @else
        <div class="nav-section-label">Authentication</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
        </nav>
    @endauth
</aside>

<header class="top-header">
    <div class="d-flex align-items-center">
        <button class="btn d-md-none me-2" type="button" onclick="document.getElementById('sidebarMenu').classList.toggle('show')">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="search-container d-none d-md-block">
            <input type="text" placeholder="Search records...">
        </div>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        @auth
            <i class="fa-regular fa-bell text-muted cursor-pointer"></i>
            <div class="vr mx-1" style="height: 20px; color: var(--border)"></div>
            <div class="dropdown">
                <div class="d-flex align-items-center gap-2 cursor-pointer" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="fw-medium d-none d-sm-inline" style="font-size: var(--font-sm);">{{ auth()->user()->name ?? 'Admin User' }}</span>
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
        @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">Login</a>
        @endauth
    </div>
</header>

<main class="main-wrapper">
    @yield('content')
</main>

<!-- Application Footer -->
<footer style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 1.5rem 0; margin-top: 2rem;">
    <div class="container-fluid px-5 d-flex justify-content-between align-items-center" style="font-size: 12px; color: #64748b;">
        <div>
            <p style="margin: 0;"><strong>School Management System</strong> | Version 1.0.0</p>
            <p style="margin: 0.25rem 0 0;">© 2026 School Management System</p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0;"><strong>Developed by:</strong> <a href="mailto:ngutujoseph@gmail.com" style="color: #2563eb; text-decoration: none;">Joseph Genes</a></p>
            <p style="margin: 0.25rem 0 0;"><a href="mailto:ngutujoseph@gmail.com" style="color: #64748b; text-decoration: none; font-size: 11px;">ngutujoseph@gmail.com</a></p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('.datatable').DataTable({
                "responsive": true,
                "pageLength": 10,
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Filter results..."
                }
            });
        }
    });
</script>

</body>
</html>