<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DWCC Equipment Borrowing System')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
        <style>
        /* ============================================ */
        /*   DWCC EQUIPMENT BORROWING SYSTEM            */
        /*   PROFESSIONAL GREEN THEME WITH LOGO          */
        /* ============================================ */

        :root {
            --green-50: #f0fdf4;    
            --green-100: #dcfce7;
            --green-200: #bbf7d0;
            --green-300: #86efac;
            --green-400: #4ade80;
            --green-500: #22c55e;
            --green-600: #16a34a;
            --green-700: #15803d;
            --green-800: #166534;
            --green-900: #14532d;
            --green-950: #052e16;
            --gold-400: #47e268;
            --gold-500: #eab308;
            --gold-600: #ca8a04;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.04);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ========== SCROLLBAR ========== */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--gray-100); }
        ::-webkit-scrollbar-thumb { background: var(--green-600); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--green-700); }

        /* ========== TOP BAR ========== */
        .top-bar {
            background: var(--green-950);
            color: var(--gold-400);
            padding: 7px 0;
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        .top-bar i { font-size: 0.9rem; }

        /* ========== NAVBAR ========== */
        .navbar {
            background: linear-gradient(135deg, var(--green-700) 0%, var(--green-800) 50%, var(--green-900) 100%);
            box-shadow: var(--shadow-xl);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .navbar .container {
            padding-top: 8px;
            padding-bottom: 8px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        /* ===== DWCC LOGO ===== */
        .dwcc-logo {
            height: 48px;
            width: auto;
            object-fit: contain;
        }

        .brand-text { line-height: 1.25; }
        .brand-text .school-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.2px;
        }
        .brand-text .school-sub {
            font-size: 0.68rem;
            font-weight: 500;
            color: #ebfa15;
            letter-spacing: 0.3px;
        }

        /* Nav Links */
        .navbar .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 10px 16px !important;
            border-radius: var(--radius);
            margin: 0 2px;
            transition: all 0.25s ease;
            position: relative;
        }
        .navbar .nav-link i { font-size: 1rem; margin-right: 5px; }
        .navbar .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: var(--white) !important;
            transform: translateY(-1px);
        }
        .navbar .nav-link.active {
            background: rgba(255,255,255,0.18) !important;
            color: var(--gold-400) !important;
            font-weight: 600;
        }
        .navbar .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: var(--gold-400);
            border-radius: 10px;
        }

        /* Badge */
        .nav-badge {
            background: var(--gold-400) !important;
            color: var(--green-900) !important;
            font-weight: 700;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        /* ========== MAIN CONTENT ========== */
        main { min-height: calc(100vh - 200px); padding: 28px 0; }

        /* ========== CARDS ========== */
        .card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-2px);
        }
        .card-header {
            background: var(--white);
            border-bottom: 2px solid var(--gray-100);
            padding: 18px 24px;
            font-weight: 700;
            font-size: 1rem;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-header i { color: var(--green-600); font-size: 1.2rem; }
        .card-body { padding: 24px; }

        /* ========== STATS CARDS ========== */
        .stat-card {
            border-radius: var(--radius-lg);
            padding: 24px;
            color: var(--white);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-xl); }
        .stat-card .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 4rem;
            opacity: 0.2;
        }
        .stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 0.85rem;
            font-weight: 500;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-green { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
        .stat-dark { background: linear-gradient(135deg, #166534 0%, #14532d 100%); }
        .stat-gold { background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); color: var(--gray-900); }
        .stat-blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .stat-red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

        /* ========== BUTTONS ========== */
        .btn {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 10px 22px;
            border-radius: var(--radius);
            border: none;
            transition: all 0.3s ease;
            letter-spacing: 0.2px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .btn:active { transform: translateY(0); }

        .btn-primary {
            background: linear-gradient(135deg, var(--green-600) 0%, var(--green-700) 100%);
            color: var(--white);
        }
        .btn-primary:hover { background: linear-gradient(135deg, var(--green-500) 0%, var(--green-600) 100%); }

        .btn-success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: var(--white);
        }
        .btn-warning {
            background: linear-gradient(135deg, #facc15 0%, #eab308 100%);
            color: var(--gray-900);
        }
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: var(--white);
        }
        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: var(--white);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: var(--white);
        }
        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--green-600);
            color: var(--green-600);
        }
        .btn-outline-primary:hover {
            background: var(--green-600);
            color: var(--white);
        }
        .btn-sm { padding: 7px 14px; font-size: 0.8rem; border-radius: var(--radius-sm); }
        .btn-lg { padding: 14px 30px; font-size: 1rem; border-radius: var(--radius); }

        /* ========== TABLES ========== */
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th {
            background: linear-gradient(135deg, var(--green-700) 0%, var(--green-800) 100%);
            color: var(--white);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border: none;
        }
        .table thead th:first-child { border-radius: var(--radius-sm) 0 0 0; }
        .table thead th:last-child { border-radius: 0 var(--radius-sm) 0 0; }
        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--gray-100);
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background: var(--green-50);
            transform: scale(1.001);
        }
        .table tbody tr:last-child td:first-child { border-radius: 0 0 0 var(--radius-sm); }
        .table tbody tr:last-child td:last-child { border-radius: 0 0 var(--radius-sm) 0; }

        /* ========== BADGES ========== */
        .badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 6px 14px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-danger { background: #fce4ec; color: #c62828; }
        .badge-info { background: #e0f2fe; color: #0c4a6e; }
        .badge-primary { background: #dcfce7; color: #166534; }

        /* ========== FORMS ========== */
        .form-control, .form-select {
            font-family: 'Inter', sans-serif;
            border-radius: var(--radius);
            border: 2px solid var(--gray-200);
            padding: 12px 16px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: var(--white);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--green-500);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
            outline: none;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--gray-700);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* ========== ALERTS ========== */
        .alert {
            border-radius: var(--radius-lg);
            border: none;
            padding: 16px 24px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.4s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 5px solid #22c55e;
        }
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 5px solid #ef4444;
        }
        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border-left: 5px solid #f59e0b;
        }

        /* ========== MODALS ========== */
        .modal-content {
            border-radius: var(--radius-xl);
            border: none;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
        }
        .modal-header {
            padding: 20px 24px;
            border-bottom: 2px solid var(--gray-100);
        }
        .modal-header.bg-success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%) !important;
            color: var(--white);
        }
        .modal-header.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: var(--white);
        }
        .modal-header.bg-warning {
            background: linear-gradient(135deg, #facc15 0%, #eab308 100%) !important;
            color: var(--gray-900);
        }
        .modal-body { padding: 24px; }
        .modal-footer {
            padding: 16px 24px;
            border-top: 2px solid var(--gray-100);
        }

        /* ========== PAGINATION ========== */
        .pagination { gap: 4px; }
        .page-link {
            border-radius: var(--radius) !important;
            border: 2px solid var(--gray-200);
            color: var(--gray-700);
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }
        .page-link:hover {
            background: var(--green-50);
            border-color: var(--green-400);
            color: var(--green-700);
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--green-600) 0%, var(--green-700) 100%);
            border-color: var(--green-600);
            color: var(--white);
            font-weight: 700;
        }

        /* ========== FOOTER ========== */
        footer {
            background: linear-gradient(135deg, var(--green-800) 0%, var(--green-950) 100%);
            color: var(--white);
            border-top: 5px solid var(--gold-400);
            padding: 24px 0;
        }
        footer p { margin: 0; }
        footer .footer-title {
            font-weight: 700;
            font-size: 1rem;
        }
        footer .footer-motto {
            color: var(--gold-400);
            font-style: italic;
            font-weight: 500;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .stat-card .stat-value { font-size: 1.8rem; }
            .navbar-brand .school-name { font-size: 0.9rem; }
            .card-body { padding: 16px; }
            .dwcc-logo { height: 38px; }
        }
    </style>
</head>
<body>
    <!-- ==================== TOP BAR ==================== -->
    <div class="top-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-telephone-fill"></i> (043) 288-1234 &nbsp;&nbsp;
                    <i class="bi bi-envelope-fill"></i> info@dwcc.edu.ph
                </div>
                <div>
                    <i class="bi bi-geo-alt-fill"></i> Calapan City, Oriental Mindoro
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== NAVIGATION ==================== -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <!-- DWCC LOGO IMAGE -->
                <img src="{{ asset('images/dwcc-logo.png') }}" alt="DWCC Logo" class="dwcc-logo">
                <div class="brand-text">
                    <div class="school-name">Divine Word College</div>
                    <div class="school-sub">of Calapan</div>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-4">
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                                   href="{{ route('dashboard') }}">
                                    <i class="bi bi-house-door-fill"></i> Dashboard
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}" 
                               href="{{ route('equipment.index') }}">
                                <i class="bi bi-box-seam-fill"></i> Equipment
                            </a>
                        </li>
                        
                        @if(!auth()->user()->isAdmin() && !auth()->user()->isStaff())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('borrowings.create') ? 'active' : '' }}" 
                                   href="{{ route('borrowings.create') }}">
                                    <i class="bi bi-plus-circle-fill"></i> Borrow
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('borrowings.index') ? 'active' : '' }}" 
                               href="{{ route('borrowings.index') }}">
                                <i class="bi bi-clock-fill"></i> History
                            </a>
                        </li>
                        
                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.requests') ? 'active' : '' }}" 
                                   href="{{ route('admin.requests') }}">
                                    <i class="bi bi-list-check"></i> Requests
                                    @php $pendingCount = App\Models\Borrowing::where('status', 'pending')->count(); @endphp
                                    @if($pendingCount > 0)
                                        <span class="nav-badge ms-1">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" 
                                   href="{{ route('admin.users') }}">
                                    <i class="bi bi-people-fill"></i> Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" 
                                   href="{{ route('admin.reports') }}">
                                    <i class="bi bi-bar-chart-fill"></i> Reports
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-warning btn-sm" href="{{ route('register') }}">
                                <i class="bi bi-person-plus-fill"></i> Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item me-2">
                            <a class="nav-link position-relative" href="{{ route('notifications.index') }}" title="Notifications">
                                <i class="bi bi-bell-fill"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning notification-count" 
                                      style="font-size: 0.6rem; display: none;">0</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" 
                               role="button" data-bs-toggle="dropdown">
                                <div style="width: 35px; height: 35px; border-radius: 50%; background: var(--green-200); 
                                            display: flex; align-items: center; justify-content: center; font-weight: 700; 
                                            color: var(--green-800); font-size: 0.9rem;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2" style="min-width: 220px;">
                                <li class="px-3 py-2">
                                    <small class="text-muted d-block">{{ Auth::user()->email }}</small>
                                    <span class="badge bg-success mt-1">{{ ucfirst(Auth::user()->role) }}</span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item rounded-2 py-2" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                    <span>{{ session('warning') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- ==================== FOOTER ==================== -->
    <footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="footer-title">Divine Word College of Calapan</p>
                    <p class="footer-motto"><small>"Your Future is Our Mission"</small></p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0"><small>&copy; {{ date('Y') }} DWCC Equipment Borrowing System</small></p>
                    <p class="mb-0"><small style="opacity: 0.7;">All Rights Reserved</small></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- ==================== SCRIPTS ==================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        function updateNotificationCount() {
            $.get('{{ route("notifications.unreadCount") }}', function(data) {
                if (data.count > 0) {
                    $('.notification-count').text(data.count).show();
                } else {
                    $('.notification-count').hide();
                }
            }).fail(function() {
                console.log('Notifications unavailable');
            });
        }

        $(document).ready(function() {
            updateNotificationCount();
            setInterval(updateNotificationCount, 30000);
        });
    </script>
    @yield('scripts')
</body>
</html> 