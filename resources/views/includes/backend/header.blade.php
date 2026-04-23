<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPF Admin Panel</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ── TOP NAVBAR ─────────────────────────────────── */
        .admin-navbar {
            background: linear-gradient(135deg, #1a237e 0%, #0d47a1 100%);
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
            flex-wrap: nowrap;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .navbar-brand-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            font-weight: 700;
        }

        .navbar-brand-text {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .navbar-brand-text span {
            font-weight: 300;
            opacity: 0.8;
        }

        /* ── HAMBURGER MENU ─────────────────────────────── */
        .hamburger-menu {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
            background: none;
            border: none;
            padding: 8px;
            margin-left: 10px;
            flex-shrink: 0;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .hamburger-menu:hover,
        .hamburger-menu:active {
            background: rgba(255,255,255,0.1);
        }

        .hamburger-line {
            width: 22px;
            height: 2.5px;
            background: rgba(255,255,255,0.88);
            border-radius: 3px;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .hamburger-menu.open .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger-menu.open .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger-menu.open .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(5.5px, -5.5px);
        }

        /* ── NAV MENUS ──────────────────────────────────── */
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            flex: 1;
            margin-left: 20px;
            overflow: visible;
            position: relative;
            z-index: 500;
        }

        .navbar-menu li {
            position: relative;
        }

        .navbar-menu li a,
        .navbar-menu li .menu-toggle {
            display: flex;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.88);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background 0.2s, color 0.2s;
            cursor: pointer;
            background: none;
            border: none;
            white-space: nowrap;
            min-height: 40px;
            font-family: inherit;
        }

        .navbar-menu li a:hover,
        .navbar-menu li .menu-toggle:hover,
        .navbar-menu li a.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        .menu-arrow {
            font-size: 10px;
            opacity: 0.7;
            transition: transform 0.25s ease;
        }

        /* ── DROPDOWN ───────────────────────────────────── */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            min-width: 190px;
            overflow: hidden;
            z-index: 10000;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            color: #444 !important;
            font-size: 13px !important;
            font-weight: 400 !important;
            border-radius: 0 !important;
            background: none !important;
            transition: background 0.15s !important;
            min-height: 44px;
        }

        .dropdown-menu a:hover {
            background: #f0f4ff !important;
            color: #1a237e !important;
        }

        .dropdown-divider {
            height: 1px;
            background: #eee;
            margin: 4px 0;
        }

        .navbar-menu li.open .dropdown-menu {
            display: block;
        }

        .navbar-menu li.open .menu-arrow {
            transform: rotate(180deg);
        }

        /* ── RIGHT SIDE ─────────────────────────────────── */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            margin-left: 12px;
        }

        .navbar-right .admin-info {
            font-size: 12px;
            color: rgba(255,255,255,0.8);
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            border: 2px solid rgba(255,255,255,0.3);
            flex-shrink: 0;
            min-width: 36px;
        }

        /* ── PAGE WRAPPER ───────────────────────────────── */
        .page-wrapper {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
            width: 100%;
            flex: 1;
        }

        /* ── MOBILE MENU OVERLAY ────────────────────────── */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mobile-menu-overlay.active {
            display: block;
            opacity: 1;
        }

        /* ── RESPONSIVE: Large Tablets / Small Laptops ──── */
        @media (max-width: 1200px) {
            .navbar-menu li a,
            .navbar-menu li .menu-toggle {
                font-size: 12.5px;
                padding: 7px 10px;
            }

            .navbar-menu {
                gap: 2px;
            }
        }

        /* ── RESPONSIVE: Tablets ────────────────────────── */
        @media (max-width: 992px) {
            .navbar-brand-text span {
                display: none;
            }

            .navbar-brand-text {
                font-size: 14px;
            }

            .navbar-menu {
                margin-left: 12px;
                gap: 1px;
            }

            .navbar-menu li a,
            .navbar-menu li .menu-toggle {
                font-size: 12px;
                padding: 6px 8px;
            }

            .navbar-right .admin-info {
                display: none;
            }

            .navbar-right {
                gap: 8px;
            }

            .page-wrapper {
                padding: 0 16px;
            }
        }

        /* ── RESPONSIVE: Mobile ─────────────────────────── */
        @media (max-width: 768px) {
            .admin-navbar {
                padding: 0 12px;
                height: 56px;
                flex-wrap: nowrap;
            }

            .hamburger-menu {
                display: flex;
                order: 3;
            }

            .navbar-brand {
                order: 1;
                gap: 8px;
                flex: 0 0 auto;
            }

            .navbar-right {
                order: 2;
                margin-left: auto;
                margin-right: 4px;
            }

            .navbar-menu {
                display: none;
                position: fixed;
                top: 56px;
                left: 0;
                right: 0;
                width: 100%;
                background: linear-gradient(180deg, #1a237e 0%, #0d47a1 100%);
                flex-direction: column;
                margin: 0;
                padding: 8px 0 16px;
                gap: 0;
                border-radius: 0;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                max-height: calc(100vh - 56px);
                max-height: calc(100dvh - 56px);
                z-index: 1001;
                box-shadow: 0 8px 24px rgba(0,0,0,0.3);
                border-top: 1px solid rgba(255,255,255,0.1);
            }

            .navbar-menu.show {
                display: flex;
            }

            .navbar-menu li {
                width: 100%;
            }

            .navbar-menu li a,
            .navbar-menu li .menu-toggle {
                border-radius: 0;
                padding: 14px 20px;
                font-size: 14px;
                width: 100%;
                text-align: left;
                min-height: 48px;
                border-bottom: 1px solid rgba(255,255,255,0.06);
            }

            .navbar-menu li a:hover,
            .navbar-menu li .menu-toggle:hover,
            .navbar-menu li a:active,
            .navbar-menu li .menu-toggle:active {
                background: rgba(255,255,255,0.12);
            }

            .dropdown-menu {
                position: static;
                background: rgba(0,0,0,0.15);
                box-shadow: none;
                border-radius: 0;
                min-width: 100%;
                display: none !important;
                margin-top: 0;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }

            .navbar-menu li.open .dropdown-menu {
                display: flex !important;
                flex-direction: column;
                max-height: 1000px;
            }

            .dropdown-menu a {
                padding: 12px 20px 12px 40px;
                color: rgba(255,255,255,0.9) !important;
                background: none !important;
                font-size: 13px !important;
                min-height: 46px;
                border-bottom: 1px solid rgba(255,255,255,0.04);
            }

            .dropdown-menu a:hover,
            .dropdown-menu a:active {
                background: rgba(255,255,255,0.12) !important;
            }

            .dropdown-divider {
                background: rgba(255,255,255,0.1);
                margin: 0;
            }

            .navbar-brand-icon {
                width: 32px;
                height: 32px;
                font-size: 16px;
            }

            .navbar-brand-text {
                font-size: 14px;
            }

            .admin-avatar {
                width: 32px;
                height: 32px;
                font-size: 13px;
                min-width: 32px;
            }

            .page-wrapper {
                padding: 0 12px;
                margin: 16px auto;
            }
        }

        /* ── RESPONSIVE: Small Phones ──────────────────── */
        @media (max-width: 480px) {
            .admin-navbar {
                padding: 0 10px;
                height: 52px;
            }

            .navbar-menu {
                top: 52px;
                max-height: calc(100vh - 52px);
                max-height: calc(100dvh - 52px);
            }

            .navbar-brand {
                gap: 6px;
            }

            .navbar-brand-icon {
                width: 28px;
                height: 28px;
                font-size: 14px;
                border-radius: 6px;
            }

            .navbar-brand-text {
                font-size: 13px;
            }

            .hamburger-menu {
                padding: 6px;
            }

            .hamburger-line {
                width: 20px;
                height: 2px;
            }

            .hamburger-menu.open .hamburger-line:nth-child(1) {
                transform: rotate(45deg) translate(4.5px, 4px);
            }

            .hamburger-menu.open .hamburger-line:nth-child(3) {
                transform: rotate(-45deg) translate(5px, -4.5px);
            }

            .navbar-right {
                gap: 6px;
                margin-right: 2px;
            }

            .admin-avatar {
                width: 30px;
                height: 30px;
                font-size: 12px;
                min-width: 30px;
            }

            .page-wrapper {
                padding: 0 10px;
                margin: 12px auto;
            }

            .navbar-menu li a,
            .navbar-menu li .menu-toggle {
                padding: 12px 16px;
                font-size: 13px;
                min-height: 46px;
            }

            .dropdown-menu a {
                padding: 11px 16px 11px 36px;
                font-size: 12px !important;
                min-height: 44px;
            }
        }

        /* ── RESPONSIVE: Extra-small Phones ────────────── */
        @media (max-width: 360px) {
            .admin-navbar {
                padding: 0 8px;
                height: 48px;
            }

            .navbar-menu {
                top: 48px;
                max-height: calc(100vh - 48px);
                max-height: calc(100dvh - 48px);
            }

            .navbar-brand-icon {
                width: 26px;
                height: 26px;
                font-size: 12px;
                border-radius: 5px;
            }

            .navbar-brand-text {
                font-size: 12px;
            }

            .hamburger-menu {
                padding: 5px;
                margin-left: 6px;
            }

            .hamburger-line {
                width: 18px;
                height: 2px;
                gap: 4px;
            }

            .hamburger-menu.open .hamburger-line:nth-child(1) {
                transform: rotate(45deg) translate(4px, 3.5px);
            }

            .hamburger-menu.open .hamburger-line:nth-child(3) {
                transform: rotate(-45deg) translate(4px, -3.5px);
            }

            .admin-avatar {
                width: 28px;
                height: 28px;
                font-size: 11px;
                min-width: 28px;
                border-width: 1.5px;
            }

            .page-wrapper {
                padding: 0 8px;
                margin: 10px auto;
            }

            .navbar-menu li a,
            .navbar-menu li .menu-toggle {
                padding: 11px 14px;
                font-size: 12px;
                min-height: 44px;
            }

            .dropdown-menu a {
                padding: 10px 14px 10px 32px;
                font-size: 11px !important;
                min-height: 42px;
            }
        }

        /* ── LANDSCAPE PHONE FIX ────────────────────────── */
        @media (max-width: 768px) and (max-height: 500px) {
            .navbar-menu {
                max-height: calc(100vh - 56px);
                padding-bottom: 20px;
            }

            .navbar-menu li a,
            .navbar-menu li .menu-toggle {
                padding: 10px 16px;
                min-height: 40px;
            }

            .dropdown-menu a {
                padding: 9px 16px 9px 36px;
                min-height: 38px;
            }
        }

        /* ── SAFE AREA INSETS (notched devices) ─────────── */
        @supports (padding: env(safe-area-inset-top)) {
            .admin-navbar {
                padding-left: max(12px, env(safe-area-inset-left));
                padding-right: max(12px, env(safe-area-inset-right));
            }

            .navbar-menu.show {
                padding-left: env(safe-area-inset-left);
                padding-right: env(safe-area-inset-right);
                padding-bottom: env(safe-area-inset-bottom);
            }
        }
    </style>
</head>
<body>

<nav class="admin-navbar">

    {{-- Brand --}}
    <a href="{{ url('/spf-backend') }}" class="navbar-brand">
        <div class="navbar-brand-icon">S</div>
        <div class="navbar-brand-text">SPF <span>Admin</span></div>
    </a>

    {{-- Hamburger Menu Button --}}
    <button class="hamburger-menu" id="hamburgerBtn">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </button>

    {{-- Nav Menus --}}
    <ul class="navbar-menu" id="navbarMenu">

        {{-- Dashboard — all roles --}}
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('spf-backend') && !request()->is('spf-backend/*') ? 'active' : '' }}">
                &#9783;&nbsp; Dashboard
            </a>
        </li>

        {{-- Members — Super Admin, Operator & Anchal Operator --}}
        @if(Auth::guard('admin')->user()?->isSuperAdmin() || Auth::guard('admin')->user()?->isOperator() || Auth::guard('admin')->user()?->isAnchalOperator())
        <li>
            <span class="menu-toggle">
                &#128101;&nbsp; Members <span class="menu-arrow">&#9660;</span>
            </span>
            <div class="dropdown-menu">
                <a href="{{ route('admin.members.pending') }}" class="{{ request()->is('spf-backend/members/pending') ? 'active' : '' }}">&#9711;&nbsp; Pending</a>
                <a href="{{ route('admin.members.approved') }}" class="{{ request()->is('spf-backend/members/approved') ? 'active' : '' }}">&#10003;&nbsp; Approved</a>
                <a href="{{ route('admin.members.rejected') }}" class="{{ request()->is('spf-backend/members/rejected') ? 'active' : '' }}">&#10007;&nbsp; Rejected</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.members.bulk_upload') }}" class="{{ request()->is('spf-backend/members/bulk-upload') ? 'active' : '' }}">&#128194;&nbsp; Bulk Upload</a>
            </div>
        </li>
        @endif

        {{-- Categories — Super Admin only --}}
        @if(Auth::guard('admin')->user()?->isSuperAdmin())
        <li>
            <span class="menu-toggle">
                &#9776;&nbsp; Categories <span class="menu-arrow">&#9660;</span>
            </span>
            <div class="dropdown-menu">
                <a href="{{ route('admin.categories.add') }}" class="{{ request()->is('spf-backend/categories/add') ? 'active' : '' }}">&#9632;&nbsp; All Categories</a>
                <a href="{{ route('admin.categories.add') }}" class="{{ request()->is('spf-backend/categories/add') ? 'active' : '' }}">&#43;&nbsp; Add Category</a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.sub-categories.add') }}" class="{{ request()->is('spf-backend/sub-categories/add') ? 'active' : '' }}">&#9632;&nbsp; Sub Categories</a>
                <a href="{{ route('admin.sub-categories.add') }}" class="{{ request()->is('spf-backend/sub-categories/add') ? 'active' : '' }}">&#43;&nbsp; Add Sub Category</a>
            </div>
        </li>
        @endif

        {{-- Reports — Super Admin & Operator --}}
        @if(Auth::guard('admin')->user()?->canViewAll())
        <li>
            <a href="#">&#128202;&nbsp; Reports</a>
        </li>
        @endif

        {{-- Admin Management — Super Admin only --}}
        @if(Auth::guard('admin')->user()?->isSuperAdmin())
        <li>
            <span class="menu-toggle">
                &#128105;&nbsp; Manage Admins <span class="menu-arrow">&#9660;</span>
            </span>
            <div class="dropdown-menu">
                <a href="{{ route('admin.users.index') }}">&#128101;&nbsp; All Admins</a>
                <a href="{{ route('admin.users.index') }}">&#43;&nbsp; Add Admin</a>
            </div>
        </li>
        @endif

        {{-- Settings — all roles --}}
        <li>
            <span class="menu-toggle">
                &#9881;&nbsp; Settings <span class="menu-arrow">&#9660;</span>
            </span>
            <div class="dropdown-menu">
                <a href="{{ route('admin.password.form') }}">&#128274;&nbsp; Change Password</a>
                <a href="#">&#128100;&nbsp; Profile</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="width:100%; text-align:left; display:flex; align-items:center; gap:10px; padding:10px 16px; color:#e53935; font-size:13px; background:none; border:none; cursor:pointer; font-family:inherit; min-height:44px;">
                        &#10006;&nbsp; Logout
                    </button>
                </form>
            </div>
        </li>

    </ul>

    {{-- Right: Admin Info --}}
    <div class="navbar-right">
        <div class="admin-info" style="text-align:right; line-height:1.4;">
            <div style="font-weight:600; color:#fff; font-size:13px;">{{ Auth::guard('admin')->user()?->name ?? 'Admin' }}</div>
            <div style="font-size:11px; opacity:0.7;">{{ Auth::guard('admin')->user()?->getRoleLabel() ?? '' }}</div>
        </div>
        <div class="admin-avatar">{{ strtoupper(substr(Auth::guard('admin')->user()?->name ?? 'A', 0, 1)) }}</div>
    </div>

</nav>

<!-- Mobile overlay backdrop -->
<div class="mobile-menu-overlay" id="mobileOverlay"></div>

<div class="page-wrapper">

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navbarMenu = document.getElementById('navbarMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');
    
    if (!hamburgerBtn || !navbarMenu) return;

    function isMobile() {
        return window.innerWidth <= 768;
    }

    function openMobileMenu() {
        hamburgerBtn.classList.add('open');
        navbarMenu.classList.add('show');
        if (mobileOverlay) mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        hamburgerBtn.classList.remove('open');
        navbarMenu.classList.remove('show');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
        // Close all dropdowns
        navbarMenu.querySelectorAll('.menu-toggle').forEach(toggle => {
            const li = toggle.closest('li');
            if (li) li.classList.remove('open');
        });
    }

    // Hamburger menu toggle
    hamburgerBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (navbarMenu.classList.contains('show')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });

    // Overlay click closes menu
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function() {
            closeMobileMenu();
        });
    }

    // Handle all dropdown menu toggles
    const menuToggles = navbarMenu.querySelectorAll('.menu-toggle');
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const li = this.closest('li');
            if (!li) return;
            
            // Close all other menus
            menuToggles.forEach(otherToggle => {
                const otherLi = otherToggle.closest('li');
                if (otherLi && otherLi !== li) {
                    otherLi.classList.remove('open');
                }
            });
            
            // Toggle current menu
            li.classList.toggle('open');
        });
    });

    // Close menu when clicking regular links (not toggles)
    const navLinks = navbarMenu.querySelectorAll('a:not(.menu-toggle)');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            closeMobileMenu();
        });
    });

    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.admin-navbar')) {
            if (isMobile()) {
                closeMobileMenu();
            }
            // Close desktop dropdowns
            menuToggles.forEach(toggle => {
                const li = toggle.closest('li');
                if (li) li.classList.remove('open');
            });
        }
    });

    // Handle window resize — clean up mobile state when going to desktop
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (!isMobile()) {
                closeMobileMenu();
            }
        }, 100);
    });

    // Prevent closing menu when clicking inside dropdown
    navbarMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Handle ESC key to close mobile menu
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isMobile() && navbarMenu.classList.contains('show')) {
            closeMobileMenu();
        }
    });
});
</script>
