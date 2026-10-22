<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Salon HQ') }}</title>
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-body: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --card-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* ================= SIDEBAR STYLES ================= */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            transition: transform 0.25s ease;
        }

        .sidebar-brand {
            padding: 1.35rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .brand-text { display: flex; flex-direction: column; }
        .brand-title { font-size: 1.15rem; font-weight: 700; letter-spacing: -0.02em; color: #ffffff; }
        .brand-subtitle { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }

        .sidebar-menu {
            flex: 1;
            padding: 1rem 0.85rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .menu-section-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 0.85rem 0.75rem 0.35rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.68rem 0.85rem;
            border-radius: 8px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .menu-item:hover { background-color: var(--sidebar-hover); color: #ffffff; }
        .menu-item.active { background-color: var(--primary); color: #ffffff; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); }
        .menu-icon { width: 19px; height: 19px; flex-shrink: 0; opacity: 0.9; }
        .menu-badge { margin-left: auto; background-color: rgba(99, 102, 241, 0.2); color: #818cf8; font-size: 0.72rem; font-weight: 600; padding: 0.15rem 0.5rem; border-radius: 999px; }
        .menu-item.active .menu-badge { background-color: rgba(255, 255, 255, 0.2); color: #ffffff; }

        .sidebar-footer {
            padding: 1rem 1.15rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            background-color: rgba(15, 23, 42, 0.6);
        }

        .user-info { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: #334155; color: #f8fafc; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem; flex-shrink: 0; }
        .user-details { min-width: 0; }
        .user-name { font-size: 0.85rem; font-weight: 600; color: #f8fafc; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-email { font-size: 0.72rem; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .logout-icon-btn { background: rgba(239, 68, 68, 0.12); color: #f87171; border: none; width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.15s ease; flex-shrink: 0; }
        .logout-icon-btn:hover { background: #ef4444; color: #ffffff; }

        /* ================= MAIN CONTENT WRAPPER & TOPBAR ================= */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left { display: flex; align-items: center; gap: 1rem; }

        .mobile-toggle {
            display: none;
            background: none;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.45rem;
            cursor: pointer;
            color: var(--text-main);
        }

        .page-heading h1 { font-size: 1.25rem; font-weight: 700; color: var(--text-main); }
        .page-heading p { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }
        .topbar-right { display: flex; align-items: center; gap: 0.85rem; }

        .content-body { padding: 1.75rem 2rem; }

        /* ================= SHARED UI UTILITIES ================= */
        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            padding: 0.62rem 1.15rem;
            border-radius: 9px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            text-decoration: none;
            transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.22);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .search-box { position: relative; }
        .search-box input {
            padding: 0.56rem 0.85rem 0.56rem 2.25rem;
            border: 1px solid var(--border-color);
            border-radius: 9px;
            font-size: 0.85rem;
            width: 260px;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 15px;
            height: 15px;
        }

        /* Status & Unisex Badges */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            flex-shrink: 0;
        }
        .status-online { background: #dcfce7; color: #15803d; }
        .status-busy { background: #dbeafe; color: #1d4ed8; }
        .status-break { background: #fef3c7; color: #b45309; }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

        .serves-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.58rem;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
        }
        .serves-both { background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; }
        .serves-female { background: #fce7f3; color: #be185d; border: 1px solid #fbcfe8; }
        .serves-male { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

        /* Panels & Tables */
        .panel {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }
        .panel-header {
            padding: 1.15rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .panel-header h2, .panel-header h3 { font-size: 1rem; font-weight: 700; color: var(--text-main); }
        .panel-link { font-size: 0.82rem; font-weight: 600; color: var(--primary); text-decoration: none; }

        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th {
            background: #f8fafc;
            padding: 0.8rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
        }
        td {
            padding: 0.95rem 1.5rem;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }

        .badge {
            display: inline-block;
            padding: 0.24rem 0.65rem;
            font-size: 0.74rem;
            font-weight: 600;
            border-radius: 999px;
        }
        .badge-completed, .badge-confirmed { background: #dcfce7; color: #15803d; }
        .badge-progress { background: #dbeafe; color: #1d4ed8; }
        .badge-pending { background: #fef3c7; color: #b45309; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .mobile-toggle { display: inline-flex; }
        }

        @yield('styles')
    </style>
</head>
<body>

    {{-- Reusable Sidebar Component --}}
    <x-sidebar />

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                @yield('header-left')
            </div>

            <div class="topbar-right">
                @yield('header-right')
            </div>
        </header>

        <main class="content-body">
            @yield('content')
        </main>
    </div>

    @yield('modals')
    @yield('scripts')
</body>
</html>

