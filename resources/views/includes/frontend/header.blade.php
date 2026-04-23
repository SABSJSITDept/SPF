<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPF Registration - Sadhumargi Parivar Foundation</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            color: #333;
        }

        /* ─────────────────────────────────────── TOP BAR ─────────────────────────────────────── */
        .top-bar {
            background: #0d47a1;
            padding: 6px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: rgba(255,255,255,0.85);
            position: relative;
        }

        .top-bar-center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .top-bar a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
        }

        .top-bar a:hover { color: #fff; }

        .top-bar-left { display: flex; align-items: center; gap: 18px; }
        .top-bar-right { display: flex; align-items: center; gap: 18px; }

        .top-bar-icon {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* ─────────────────────────────────────── MAIN HEADER ─────────────────────────────────────── */
        .site-header {
            background: linear-gradient(135deg, #1565c0 0%, #1976d2 60%, #1e88e5 100%);
            padding: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        }

        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-logo-wrap {
            flex-shrink: 0;
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-logo-wrap img {
            width: 90px;
            height: 90px;
            object-fit: contain;
        }

        .header-text { flex: 1; }

        .header-org-name {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 3px 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .header-tagline {
            font-size: 13px;
            color: rgba(255,255,255,0.80);
            margin: 0 0 4px 0;
        }

        .header-address {
            font-size: 12px;
            color: rgba(255,255,255,0.65);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-badge {
            flex-shrink: 0;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.30);
            border-radius: 6px;
            padding: 10px 18px;
            text-align: center;
        }

        .header-badge-title {
            font-size: 11px;
            color: rgba(255,255,255,0.75);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 4px 0;
        }

        .header-badge-year {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        /* ─────────────────────────────────────── INFO BANNER ─────────────────────────────────────── */
        .info-banner {
            background: #e3f2fd;
            border-left: 4px solid #1976d2;
            padding: 12px 16px;
            max-width: 1200px;
            margin: 20px auto 0;
            font-size: 13px;
            color: #0d47a1;
            line-height: 1.65;
            border-radius: 0 4px 4px 0;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-banner svg {
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ─────────────────────────────────────── PAGE HEADER (legacy) ─────────────────────────────────────── */
        .page-header {
            background: #fff;
            padding: 24px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin: 0 0 4px 0;
        }

        .header-subtitle {
            font-size: 13px;
            color: #666;
            margin: 0;
        }

        /* ─────────────────────────────────────── FORM CONTAINER ─────────────────────────────────────── */
        .form-wrapper {
            max-width: 1200px;
            margin: 20px auto 0;
            padding: 0 20px 40px;
        }

        /* ─────────────────────────────────────── FORM BOX ─────────────────────────────────────── */
        .form-box {
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }

        .form-title {
            background: #1976d2;
            color: #fff;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: 600;
            margin: 0;
        }

        .form-content {
            padding: 20px;
        }

        /* ─────────────────────────────────────── FORM GRID ─────────────────────────────────────── */
        .form-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-full { grid-column: 1 / -1; }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }

        .required { color: #d32f2f; }

        .form-input,
        .form-select,
        .form-textarea {
            padding: 9px 12px;
            font-size: 13px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fafafa;
            color: #333;
            font-family: Arial, sans-serif;
            outline: none;
            transition: all .2s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .form-input::placeholder { color: #999; }

        .form-input:focus, .form-select:focus {
            background: #fff;
            border-color: #1976d2;
            box-shadow: 0 0 0 2px rgba(25,118,210,.1);
        }

        .form-input[readonly] {
            background: #f5f5f5;
            color: #999;
            cursor: not-allowed;
        }

        .form-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23666' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 30px;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
            font-family: Arial, sans-serif;
        }

        /* ─────────────────────────────────────── CHECKBOX & RADIO ─────────────────────────────────────── */
        .checkbox-group, .radio-group { display: grid; gap: 10px; }

        .radio-group.horizontal {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .checkbox-item, .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-item input[type="checkbox"],
        .radio-item input[type="radio"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #1976d2;
            flex-shrink: 0;
        }

        .checkbox-item label, .radio-item label {
            font-size: 13px;
            color: #333;
            cursor: pointer;
            margin: 0;
        }

        /* ─────────────────────────────────────── 3-COLUMN SECTION ─────────────────────────────────────── */
        .three-column-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .column-content {
            padding: 16px;
            background: #fafafa;
            border-radius: 4px;
        }

        .column-title {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin: 0 0 12px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #1976d2;
        }

        .column-content .checkbox-group,
        .column-content .radio-group { gap: 8px; }

        .column-content .checkbox-item label,
        .column-content .radio-item label { font-size: 12px; }

        /* ─────────────────────────────────────── FILE UPLOAD ─────────────────────────────────────── */
        .file-upload-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: flex-start;
        }

        .file-input-label {
            display: inline-block;
            padding: 8px 16px;
            background: #1976d2;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background .2s;
        }

        .file-input-label:hover { background: #1565c0; }

        #fileUpload { display: none; }

        .file-name {
            font-size: 12px;
            color: #666;
            margin-top: 8px;
        }

        .file-error {
            color: #d32f2f;
            font-size: 12px;
            margin-top: 6px;
        }

        /* ─────────────────────────────────────── BUTTON ─────────────────────────────────────── */
        .button-wrapper { margin-top: 24px; text-align: center; }

        .submit-btn {
            padding: 12px 36px;
            background: #1976d2;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }

        .submit-btn:hover:not(:disabled) { background: #1565c0; }
        .submit-btn:disabled { opacity: .6; cursor: not-allowed; }

        /* ─────────────────────────────────────── LOADING OVERLAY ─────────────────────────────────────── */
        body.overlay-active {
            overflow: hidden;
        }

        body.overlay-active .spf-card {
            filter: blur(4px);
            pointer-events: none;
        }

        #loadingOverlay {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(2px);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        #loadingOverlay.active {
            opacity: 1;
        }

        #loadingOverlay.hidden {
            display: none;
            opacity: 0;
        }

        .loader-box {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
            width: auto;
            justify-content: center;
        }

        .loader-spinner-container {
            width: auto;
            min-width: 300px;
            height: 120px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .duck-container {
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top: 4px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loader-text {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin: 0;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .loader-subtext {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            font-weight: 500;
            text-align: left;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* No additional spinner CSS needed */

        /* ─────────────────────────────────────── SUGGESTIONS ─────────────────────────────────────── */
        .suggestions-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 50;
            list-style: none;
            margin: 0;
            padding: 0;
            box-shadow: 0 4px 8px rgba(0,0,0,.1);
        }

        .suggestions-list.hidden { display: none; }

        .suggestions-list li {
            padding: 10px 12px;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background .15s;
        }

        .suggestions-list li:last-child { border-bottom: none; }
        .suggestions-list li:hover { background: #f5f5f5; }

        /* ─────────────────────────────────────── HELPERS ─────────────────────────────────────── */
        .hidden { display: none !important; }
        .field-wrapper { position: relative; }

        /* ─────────────────────────────────────── RESPONSIVE ─────────────────────────────────────── */
        @media (max-width: 1024px) {
            .form-grid-3 { grid-template-columns: repeat(2, 1fr); }
            .three-column-section { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
            .form-grid-3 .form-full, .form-grid-2 .form-full { grid-column: 1; }
            .three-column-section { grid-template-columns: 1fr; }
            .file-upload-wrapper { grid-template-columns: 1fr; }
            .submit-btn { width: 100%; }
            .header-inner { flex-wrap: wrap; }
            .header-badge { display: none; }
            .header-org-name { font-size: 17px; }
            .nav-item { padding: 10px 10px; font-size: 12px; }
            .top-bar { padding: 4px 12px; font-size: 11px; }
            .top-bar-left, .top-bar-right { gap: 8px; }
        }
    </style>
</head>
<body>

    {{-- ─── Loading Overlay ────────────────────────────────────────────────── --}}
    <div id="loadingOverlay" class="hidden">
        <div class="loader-box">
            <div class="loader-spinner-container">
                <div class="duck-container">
                    <div class="spinner"></div>
                </div>
            </div>
            <div class="loader-text-wrapper">
                <p class="loader-text" id="loaderText">Searching Profile...</p>
                <p class="loader-subtext" id="loaderSubtext">Please wait</p>
            </div>
        </div>
    </div>

    {{-- ─── Top Bar ─────────────────────────────────────────────────────────── --}}
    <div class="top-bar">
        <div class="top-bar-left">
            <span class="top-bar-icon">
             जय गुरु नाना 
            </span>
        </div>
        <div class="top-bar-center">
            <span>जय महावीर</span>
        </div>
        <div class="top-bar-right">
            <span>जय गुरु राम</span>
        </div>
    </div>

    {{-- ─── Main Site Header ────────────────────────────────────────────────── --}}
    <header class="site-header">
        <div class="header-inner">
            <div class="header-logo-wrap">
                <img src="/uploads/SPF_logo.png" alt="SPF Logo" />
            </div>
            <div class="header-text">
                <h1 class="header-org-name">Sadhumargi Professional Forum</h1>
                <p class="header-tagline">Empowering Sadhumargi Community Professionals</p>
                <p class="header-address">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="rgba(255,255,255,0.65)" viewBox="0 0 16 16">
                        <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32.066 32.066 0 0 1 8 14.58a32.068 32.068 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94z"/>
                        <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" fill="rgba(255,255,255,0.5)"/>
                    </svg>
                    Acharya Nanesh Marg, Gangashahar, Nokha Road, Bikaner, Rajasthan
                </p>
            </div>
            <div class="header-badge">
                <p class="header-badge-year">SPF</p>
            </div>
        </div>
    </header>

    {{-- ─── Info Banner ─────────────────────────────────────────────────────── --}}
    <div class="info-banner">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#1976d2" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
        </svg>
        <span>
            If you don't have MID, no worries — fill the form completely and submit. Once we receive your form, Bikaner Office will help generate the MID if you are a Sadhumargi Parivar member.
        </span>
    </div>

