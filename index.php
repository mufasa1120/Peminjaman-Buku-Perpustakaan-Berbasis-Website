<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Perpustakaan SMA Ki Hajar Dewantoro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3968;
            --secondary-color: #3d4b7a;
            --accent-color: #ffffff;
            --light-bg:rgb(0, 35, 139);
            --card-shadow: 0 8px 32px rgba(44, 57, 104, 0.12);
            --text-primary: #2c3968;
            --text-secondary: #6c757d;
            --gradient-primary: linear-gradient(135deg, #2c3968 0%, #3d4b7a 100%);
            --gradient-accent: linear-gradient(135deg, rgb(100, 112, 146) 0%,rgb(30, 59, 162) 100%);
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.15);
            --border-radius: 16px;
            --border-radius-lg: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Pattern */
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(244, 196, 48, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(44, 57, 104, 0.05) 0%, transparent 50%),
                linear-gradient(135deg,rgb(8, 52, 181) 0%, #e8ecf4 100%);
            z-index: -2;
        }

        .background-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25px 25px, rgba(255, 255, 255, 0.03) 2px, transparent 2px),
                radial-gradient(circle at 75px 75px, rgba(44, 57, 104, 0.05) 2px, transparent 2px);
            background-size: 100px 100px, 150px 150px;
            z-index: -1;
            animation: patternMove 20s linear infinite;
        }

        @keyframes patternMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Modern Navbar - Fixed for mobile responsiveness */
        .modern-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            padding: 0.8rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            min-height: 80px;
        }

        .modern-navbar::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--gradient-primary);
            background-size: 200% 100%;
            animation: gradientShift 3s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Container for navbar content */
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 1rem;
            min-height: 60px;
        }

        /* Navbar Brand - Always visible with responsive text */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: var(--text-primary) !important;
            text-decoration: none;
            transition: transform 0.3s ease;
            flex: 1;
            min-width: 0;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        /* Logo - Responsive sizing */
        .logo-navbar {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(44, 57, 104, 0.3);
            transition: all 0.3s ease;
            object-fit: contain;
            border: 2px solid var(--accent-color);
            padding: 3px;
            background: white;
            flex-shrink: 0;
        }

        .logo-navbar:hover {
            transform: rotate(5deg) scale(1.05);
            box-shadow: 0 8px 25px rgba(44, 57, 104, 0.4);
        }

        /* Brand Text Container - Always visible */
        .brand-text-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            flex: 1;
        }

        .brand-text {
            font-size: 1.3rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
            line-height: 1.2;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .brand-subtitle {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-top: -2px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        /* Login Button - Responsive */
        .login-btn {
            background: var(--gradient-primary);
            border: none;
            border-radius: 50px;
            padding: 0.8rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-accent);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .login-btn:hover::before {
            left: 0;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 57, 104, 0.4);
            color: white;
        }

        .login-icon {
            transition: transform 0.3s ease;
        }

        .login-btn:hover .login-icon {
            transform: translateX(3px);
        }

        /* Main Content Area */
        .main-content {
            margin-top: 100px;
            padding: 2rem;
            min-height: calc(100vh - 100px);
        }

        /* Hero Section */
        .hero-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-lg);
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-primary);
            background-size: 200% 100%;
            animation: gradientShift 3s ease-in-out infinite;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .stat-item {
            background: rgba(44, 57, 104, 0.1);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(44, 57, 104, 0.2);
            transition: all 0.3s ease;
            min-width: 140px;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow);
            background: rgba(44, 57, 104, 0.15);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        /* Feature Cards */
        .feature-cards {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            max-width: 400px;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .feature-icon i {
            font-size: 2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .feature-btn {
            background: var(--gradient-primary);
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2rem;
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .feature-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        /* Quick Actions */
        .quick-actions {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .quick-actions h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .action-btn {
            background: rgba(44, 57, 104, 0.1);
            border: 2px solid rgba(44, 57, 104, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            overflow: hidden;
        }

        .btn-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 10;
        }

        .login-image {
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
        }

        .image-content {
            text-align: center;
            color: white;
            z-index: 2;
            position: relative;
            padding: 2rem;
        }

        .logo-login {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            object-fit: contain;
            border: 4px solid rgba(255, 255, 255, 0.3);
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            letter-spacing: -1px;
        }

        .welcome-subtitle {
            font-size: 1rem;
            font-weight: 400;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .welcome-stats {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .login-form-container {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 1.8rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Updated Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .input-container {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 2px solid rgba(44, 57, 104, 0.2);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(44, 57, 104, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1rem;
        }

        /* Updated Button Styles */
        .btn-submit {
            width: 100%;
            background: var(--gradient-primary);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.8rem;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: var(--gradient-accent);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        /* Mobile Responsive Media Queries */
        @media (max-width: 991.98px) {
            .main-content {
                padding: 1rem;
                margin-top: 90px;
            }
            
            .hero-section {
                padding: 2rem 1.5rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-stats {
                gap: 1rem;
            }
            
            .feature-cards {
                flex-direction: column;
                align-items: center;
                gap: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }

            .login-form-container {
                padding: 1.5rem;
            }
            
            .welcome-title {
                font-size: 1.8rem;
            }
            
            .logo-login {
                width: 100px;
                height: 100px;
            }
        }

        /* Tablet responsive */
        @media (max-width: 768px) {
            .modern-navbar {
                padding: 0.6rem 0;
                min-height: 70px;
            }

            .navbar-brand {
                gap: 10px;
            }

            .logo-navbar {
                width: 45px;
                height: 45px;
            }

            .brand-text {
                font-size: 1.1rem;
            }

            .brand-subtitle {
                font-size: 0.65rem;
            }

            .login-btn {
                padding: 0.7rem 1.2rem;
                font-size: 0.9rem;
            }

            .main-content {
                margin-top: 85px;
            }

            .login-form-container {
                padding: 1.5rem;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
        }

        /* Mobile phones */
        @media (max-width: 576px) {
            .modern-navbar {
                padding: 0.5rem 0;
                min-height: 65px;
            }

            .navbar-brand {
                gap: 8px;
            }

            .logo-navbar {
                width: 40px;
                height: 40px;
            }

            .brand-text {
                font-size: 0.95rem;
                line-height: 1.1;
            }

            .brand-subtitle {
                font-size: 0.6rem;
            }

            .login-btn {
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
                gap: 6px;
            }

            .main-content {
                margin-top: 80px;
                padding: 0.8rem;
            }

            .hero-title {
                font-size: 1.6rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }
        }

        /* Small mobile screens */
        @media (max-width: 480px) {
            .brand-text {
                font-size: 0.85rem;
            }
            
            .brand-subtitle {
                font-size: 0.55rem;
            }

            .login-btn span {
                display: none;
            }

            .login-btn {
                padding: 0.6rem;
                min-width: 44px;
                justify-content: center;
            }
        }

        /* Very small screens */
        @media (max-width: 360px) {
            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .brand-text {
                font-size: 0.8rem;
            }

            .brand-subtitle {
                font-size: 0.5rem;
            }

            .navbar-brand {
                gap: 6px;
            }

            .logo-navbar {
                width: 35px;
                height: 35px;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--gradient-primary);
            transform-origin: left;
            transform: scaleX(0);
            transition: transform 0.3s ease;
            z-index: 9999;
        }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Touch optimizations */
        * {
            -webkit-tap-highlight-color: transparent;
        }

        .btn, .nav-link, .action-btn, .feature-btn {
            min-height: 44px;
        }

        input, select, textarea {
            font-size: 16px;
        }

        /* Accessibility improvements */
        .visually-hidden {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        /* Focus styles */
        button:focus,
        .btn:focus,
        .nav-link:focus,
        a:focus {
            outline: 2px solid var(--accent-color);
            outline-offset: 2px;
        }

        /* Spinner for loading state */
        .spinner-border {
            vertical-align: middle;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator" id="scrollIndicator"></div>

    <!-- Enhanced Background -->
    <div class="background-overlay"></div>
    <div class="background-pattern"></div>

    <!-- Modern Navbar with School Theme -->
    <nav class="navbar navbar-expand-lg modern-navbar" id="mainNavbar">
        <div class="container-fluid px-3 px-lg-4">
            <div class="navbar-container">
                <!-- Enhanced Brand - Always visible -->
                <a class="navbar-brand" aria-label="Dashboard Perpustakaan SMA Ki Hajar Dewantoro">
                    <img src="assets/images/logo-admin.png" alt="Logo SMA Ki Hajar Dewantoro" class="logo-navbar">
                    <div class="brand-text-container">
                        <div class="brand-text">SMA KI HAJAR DEWANTORO</div>
                        <div class="brand-subtitle">Perpustakaan Digital</div>
                    </div>
                </a>
                
                <!-- Login Button -->
                <button class="btn login-btn" id="loginBtn" aria-label="Login ke sistem" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fas fa-sign-in-alt login-icon" aria-hidden="true"></i>
                    <span>Login</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero-section fade-in-up">
            <h1 class="hero-title">Selamat Datang di Perpustakaan Digital</h1>
            <p class="hero-subtitle">
                Jelajahi koleksi buku terlengkap SMA Ki Hajar Dewantoro. 
                Temukan, pinjam, dan baca buku favorit Anda dengan mudah melalui sistem perpustakaan digital kami.
            </p>
            
            <!-- Statistics -->
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">1,500+</div>
                    <div class="stat-label">Koleksi Buku</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Anggota Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Akses Online</div>
                </div>
            </div>
        </section>

        <!-- Single Feature Card (Cari Buku only) -->
        <section class="feature-cards">
            <article class="feature-card slide-in-left">
                <div class="feature-icon">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </div>
                <h2 class="feature-title">Cari Buku</h2>
                <p class="feature-description">
                    Temukan buku yang Anda cari dengan mudah menggunakan fitur pencarian canggih kami. 
                    Filter berdasarkan kategori, penulis, atau tahun terbit.
                </p>
                <a href="login-proses.php" class="feature-btn">
                    <span>Mulai Mencari</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </article>
        </section>

        <!-- Quick Actions -->
        <section class="quick-actions fade-in-up">
            <h3>Akses Cepat</h3>
            <div class="action-buttons">
                <a href="login-proses.php" class="action-btn">
                    <i class="fas fa-books" aria-hidden="true"></i>
                    <span>Katalog Lengkap</span>
                </a>
                <a href="login-proses.php" class="action-btn">
                    <i class="fas fa-star" aria-hidden="true"></i>
                    <span>Buku Populer</span>
                </a>
                <a href="login-proses.php" class="action-btn">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <span>Buku Terbaru</span>
                </a>
            </div>
        </section>
    </main>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content login-card" style="border-radius: var(--border-radius-lg); overflow: hidden;">
                <div class="row g-0">
                    <!-- Left Side - Welcome Section -->
                    <div class="col-lg-6 login-image d-none d-lg-flex">
                        <div class="image-content">
                            <div class="logo-container">
                                <img src="assets/images/logo-admin.png" alt="Logo SMA Ki Hajar Dewantoro" class="logo-login">
                            </div>
                            <h1 class="welcome-title">Selamat Datang</h1>
                            <p class="welcome-subtitle">
                                Akses sistem perpustakaan digital SMA Ki Hajar Dewantoro. 
                                Kelola koleksi buku dan layanan perpustakaan dengan mudah.
                            </p>
                            <div class="welcome-stats">
                                <div class="stat-item">
                                    <div class="stat-number">1,500+</div>
                                    <div class="stat-label">Koleksi Buku</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number">500+</div>
                                    <div class="stat-label">Pengguna</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="col-lg-6">
                        <div class="login-form-container">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

                            <!-- Form Header -->
                            <div class="form-header">
                                <h2 class="form-title">Login</h2>
                                <p class="form-subtitle">Masuk ke akun Anda untuk melanjutkan</p>
                            </div>

                            <!-- Login Form -->
                            <form id="loginForm">
                                <!-- Username Field -->
                                <div class="form-group">
                                    <label for="username" class="form-label">USERNAME</label>
                                    <div class="input-container">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" 
                                            class="form-control" 
                                            id="username" 
                                            name="username" 
                                            placeholder="Masukkan username/NISN/NIG Anda"
                                            required 
                                            autocomplete="username">
                                    </div>
                                </div>

                                <!-- Password Field -->
                                <div class="form-group">
                                    <label for="password" class="form-label">PASSWORD</label>
                                    <div class="input-container">
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password" 
                                            class="form-control" 
                                            id="password" 
                                            name="password" 
                                            placeholder="Masukkan password Anda"
                                            required 
                                            autocomplete="current-password">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn-submit" id="loginBtn">
                                    <span>Masuk</span>
                                </button>
                            </form>

                            <!-- Alert Messages -->
                            <div id="alertContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Enhanced navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            const scrollIndicator = document.getElementById('scrollIndicator');
            
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Update scroll indicator
            const scrollPercent = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
            scrollIndicator.style.transform = `scaleX(${scrollPercent / 100})`;
        });

        // Loading overlay simulation
        document.addEventListener('DOMContentLoaded', function() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Show loading overlay
            loadingOverlay.style.display = 'flex';
            
            // Hide after 1.5 seconds (simulation)
            setTimeout(function() {
                loadingOverlay.style.opacity = '0';
                setTimeout(function() {
                    loadingOverlay.style.display = 'none';
                }, 300);
            }, 1500);
        });

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const alertContainer = document.getElementById('alertContainer');

            // Show loading state
            const loginBtn = document.querySelector('#loginForm button[type="submit"]');
            loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            loginBtn.disabled = true;

            // Kirim data ke server
            fetch('login-check.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Redirect ke dashboard yang sesuai
                    window.location.href = data.redirect;
                } else {
                    // Tampilkan pesan error
                    showAlert(data.message || 'Username atau Password salah!', 'danger');
                    loginBtn.innerHTML = '<span>Masuk</span>';
                    loginBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan pada koneksi.', 'danger');
                loginBtn.innerHTML = '<span>Masuk</span>';
                loginBtn.disabled = false;
            });
        });

        // Fungsi untuk menampilkan alert
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alertDiv);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => {
                    alertDiv.remove();
                }, 150);
            }, 5000);
        }

        // Login form handling
        document.getElementById('loginForm')?.addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const alertContainer = document.getElementById('alertContainer');
            
            // Add loading state
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<span class="visually-hidden">Loading...</span>';
            
            // Basic validation
            if (!username.value.trim() || !password.value.trim()) {
                e.preventDefault();
                showAlert('Harap isi semua field!', 'danger');
                resetButton();
                return;
            }
            
            // Optional: Add more validation here
        });

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'danger' ? 'danger' : 'success'} fade-in-up`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : 'check-circle'}"></i>
                ${message}
            `;
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alertDiv);
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => {
                    alertDiv.remove();
                }, 300);
            }, 5000);
        }

        function resetButton() {
            const loginBtn = document.getElementById('loginBtn');
            loginBtn.classList.remove('loading');
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<span>Masuk</span>';
        }
    </script>
</body>
</html>