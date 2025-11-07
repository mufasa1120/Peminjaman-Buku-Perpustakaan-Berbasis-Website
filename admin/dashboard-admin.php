<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2c3968;
            --secondary-color: #3d4b7a;
            --accent-color: #f4c430;
            --light-bg: #f8f9fc;
            --card-shadow: 0 8px 32px rgba(44, 57, 104, 0.12);
            --text-primary: #2c3968;
            --text-secondary: #6c757d;
            --gradient-primary: linear-gradient(135deg, #2c3968 0%, #3d4b7a 100%);
            --gradient-accent: linear-gradient(135deg, #f4c430 0%, #f39c12 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(244, 196, 48, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(44, 57, 104, 0.05) 0%, transparent 50%),
                linear-gradient(135deg, #f8f9fc 0%, #e8ecf4 100%);
            z-index: -1;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-accent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
            z-index: 1050;
        }

        /* Modern Navbar */
        .modern-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            padding: 1rem 0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 20px rgba(44, 57, 104, 0.08);
        }

        .modern-navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            padding: 0.5rem 0;
            box-shadow: 0 4px 30px rgba(44, 57, 104, 0.15);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        .logo-navbar {
            width: 45px;
            height: 45px;
            margin-right: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.2);
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 600;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-menu {
            gap: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px !important;
            border-radius: 12px;
            color: var(--text-secondary) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            opacity: 0.1;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .logout-btn {
            background: var(--gradient-accent);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(244, 196, 48, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(244, 196, 48, 0.4);
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-top: 120px;
            padding: 2rem 0;
        }

        /* Welcome Section */
        .welcome-section {
            background: var(--gradient-primary);
            border-radius: 24px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .welcome-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: rgba(244, 196, 48, 0.2);
            border-radius: 50%;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-accent);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(44, 57, 104, 0.2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: white;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: var(--accent-color);
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .action-btn {
            background: linear-gradient(135deg, #f8f9fc 0%, #e8ecf4 100%);
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 1.5rem;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .action-btn:hover {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(44, 57, 104, 0.3);
        }

        .action-btn i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }
            
            .welcome-subtitle {
                font-size: 1rem;
            }
            
            .brand-text {
                display: none !important;
            }
            
            .main-content {
                margin-top: 100px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .action-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        .fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

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

        /* Recent Activities */
        .recent-activities {
            margin-bottom: 2rem;
        }

        .activity-card, .summary-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            height: 100%;
        }

        /* Activity List - Enhanced with scroll functionality like alert-list */
        .activity-list {
            max-height: 600px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .activity-list::-webkit-scrollbar {
            width: 6px;
        }

        .activity-list::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .activity-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }

        .activity-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #f8f9fc 0%, #e8ecf4 100%);
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.1);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-size: 1rem;
        }

        .activity-icon.borrow {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .activity-icon.return {
            background: linear-gradient(135deg, #007bff, #6610f2);
        }

        .activity-icon.new-book {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .activity-icon.new-member {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .activity-subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .activity-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
            background: rgba(44, 57, 104, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        /* Alert List - Add scrollable functionality */
        .alert-list {
            max-height: 600px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .alert-list::-webkit-scrollbar {
            width: 6px;
        }

        .alert-list::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .alert-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }

        .alert-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        .alert-item {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .alert-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .alert-item.warning {
            background: rgba(255, 193, 7, 0.1);
            border-color: #ffc107;
        }

        .alert-item.danger {
            background: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
        }

        .alert-item.info {
            background: rgba(13, 202, 240, 0.1);
            border-color: #0dcaf0;
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .alert-description {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        
        .floating-books {
            position: absolute;
            top: 10%;
            right: 5%;
            opacity: 0.1;
            font-size: 6rem;
            color: var(--accent-color);
            animation: float 6s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .navbar-toggler {
            border: none;
            padding: 8px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2844, 57, 104, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>
</head>
<body>
    <!-- Scroll Indicator -->
    <div class="scroll-indicator" id="scrollIndicator"></div>

    <!-- Floating Books Decoration -->
    <div class="floating-books">
        <i class="fas fa-book-open"></i>
    </div>

    <!-- Modern Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top modern-navbar" id="mainNavbar">
        <div class="container-fluid px-3 px-lg-4">
            <!-- Brand -->
            <a class="navbar-brand" href="#">
                <div class="logo-navbar d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #2c3968 0%, #3d4b7a 100%); color: white;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span class="brand-text d-none d-sm-inline">SMA KI HAJAR DEWANTORO</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav nav-menu mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="../admin/akun-member.php">
                            <i class="fas fa-users"></i>
                            <span>Akun Member</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/rekapitulasi.php">
                            <i class="fas fa-chart-bar"></i>
                            <span>Rekapitulasi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/daftar-buku.php">
                            <i class="fas fa-book"></i>
                            <span>Daftar Buku</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/pinjam-buku-tamu.php">
                            <i class="fas fa-hand-holding"></i>
                            <span>Pinjam Buku</span>
                        </a>
                    </li>
                </ul>

                <!-- Logout Button -->
                <button type="button" class="btn logout-btn" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid px-3 px-lg-4">
            <!-- Welcome Section -->
            <section class="welcome-section">
                <div class="welcome-content">
                    <h1 class="welcome-title">Selamat Datang, Admin!</h1>
                    <p class="welcome-subtitle">Dashboard Perpustakaan SMA Ki Hajar Dewantoro</p>
                </div>
            </section>

            <!-- Statistics Cards -->
            <section class="stats-grid">
                <?php
                // Include database connection
                include '../koneksi.php';
                
                // 1. Total Member (Siswa + Staff/Guru)
                $queryTotalMember = "SELECT (SELECT COUNT(*) FROM siswa) + (SELECT COUNT(*) FROM staff_guru) AS total_member";
                $resultTotalMember = mysqli_query($conn, $queryTotalMember);
                $totalMember = mysqli_fetch_assoc($resultTotalMember)['total_member'];
                
                // 2. Total Buku
                $queryTotalBuku = "SELECT COUNT(*) AS total_buku FROM buku";
                $resultTotalBuku = mysqli_query($conn, $queryTotalBuku);
                $totalBuku = mysqli_fetch_assoc($resultTotalBuku)['total_buku'];
                
                // 3. Sedang Dipinjam (Belum dikembalikan dari semua tabel peminjaman)
                $querySedangDipinjam = "SELECT 
                                        (SELECT COUNT(*) FROM rekap_peminjaman_siswa WHERE status_pengembalian = '0') +
                                        (SELECT COUNT(*) FROM rekap_peminjaman_staff_guru WHERE status_pengembalian = '0') +
                                        (SELECT COUNT(*) FROM rekap_peminjaman_tamu WHERE status_pengembalian = '0') AS total_dipinjam";
                $resultSedangDipinjam = mysqli_query($conn, $querySedangDipinjam);
                $sedangDipinjam = mysqli_fetch_assoc($resultSedangDipinjam)['total_dipinjam'];
                
                // 4. Peminjaman Bulan Ini (baik yang sudah dikembalikan maupun belum)
                $currentMonth = date('m');
                $currentYear = date('Y');
                $queryPeminjamanBulanIni = "SELECT 
                                            (SELECT COUNT(*) FROM rekap_peminjaman_siswa 
                                            WHERE MONTH(tgl_pinjam) = $currentMonth AND YEAR(tgl_pinjam) = $currentYear) +
                                            (SELECT COUNT(*) FROM rekap_peminjaman_staff_guru 
                                            WHERE MONTH(tgl_pinjam) = $currentMonth AND YEAR(tgl_pinjam) = $currentYear) +
                                            (SELECT COUNT(*) FROM rekap_peminjaman_tamu 
                                            WHERE MONTH(tgl_pinjam) = $currentMonth AND YEAR(tgl_pinjam) = $currentYear) AS total_peminjaman";
                $resultPeminjamanBulanIni = mysqli_query($conn, $queryPeminjamanBulanIni);
                $peminjamanBulanIni = mysqli_fetch_assoc($resultPeminjamanBulanIni)['total_peminjaman'];
                ?>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?php echo $totalMember; ?></div>
                    <div class="stat-label">Total Member (Siswa + Staff/Guru)</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-number"><?php echo $totalBuku; ?></div>
                    <div class="stat-label">Total Buku</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-hand-holding"></i>
                    </div>
                    <div class="stat-number"><?php echo $sedangDipinjam; ?></div>
                    <div class="stat-label">Sedang Dipinjam</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-number"><?php echo $peminjamanBulanIni; ?></div>
                    <div class="stat-label">Peminjaman Bulan Ini</div>
                </div>
            </section>

            <!-- Recent Activities -->
            <section class="recent-activities mb-4">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="activity-card">
                            <h3 class="section-title">
                                <i class="fas fa-clock"></i>
                                Aktivitas Terbaru
                            </h3>
                            <div class="activity-list">
                                <?php
                                // Koneksi database
                                $conn = mysqli_connect("localhost", "root", "", "perpustakaanbaru");
                                if (!$conn) {
                                    die("Koneksi gagal: " . mysqli_connect_error());
                                }

                                // Query untuk mendapatkan aktivitas terbaru dari berbagai tabel
                                $query = "(
                                            SELECT 
                                                'peminjaman_siswa' AS jenis_aktivitas,
                                                nama_peminjam AS pelaku,
                                                CONCAT('Peminjaman Buku oleh Siswa (', judul_buku, ')') AS aktivitas,
                                                tgl_pinjam AS waktu,
                                                CASE WHEN status_pengembalian = '0' THEN 'borrow' ELSE 'return' END AS tipe_icon
                                            FROM rekap_peminjaman_siswa 
                                            ORDER BY id_rekap_siswa DESC 
                                            LIMIT 3
                                        )
                                        UNION
                                        (
                                            SELECT 
                                                'peminjaman_staff' AS jenis_aktivitas,
                                                nama_peminjam AS pelaku,
                                                CONCAT('Peminjaman Buku oleh Staff/Guru (', judul_buku, ')') AS aktivitas,
                                                tgl_pinjam AS waktu,
                                                CASE WHEN status_pengembalian = '0' THEN 'borrow' ELSE 'return' END AS tipe_icon
                                            FROM rekap_peminjaman_staff_guru 
                                            ORDER BY id_rekap_staff_guru DESC 
                                            LIMIT 3
                                        )
                                        UNION
                                        (
                                            SELECT 
                                                'peminjaman_tamu' AS jenis_aktivitas,
                                                nama_peminjam AS pelaku,
                                                CONCAT('Peminjaman Buku oleh Tamu (', judul_buku, ')') AS aktivitas,
                                                tgl_pinjam AS waktu,
                                                CASE WHEN status_pengembalian = '0' THEN 'borrow' ELSE 'return' END AS tipe_icon
                                            FROM rekap_peminjaman_tamu 
                                            ORDER BY id_rekap_tamu DESC 
                                            LIMIT 3
                                        )
                                        ORDER BY waktu DESC 
                                        LIMIT 8";

                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $iconClass = '';
                                        $iconBg = '';
                                        
                                        switch ($row['tipe_icon']) {
                                            case 'new-member':
                                                $iconClass = 'fas fa-user-plus';
                                                $iconBg = 'new-member';
                                                break;
                                            case 'new-book':
                                                $iconClass = 'fas fa-book-medical';
                                                $iconBg = 'new-book';
                                                break;
                                            case 'borrow':
                                                $iconClass = 'fas fa-hand-holding';
                                                $iconBg = 'borrow';
                                                break;
                                            case 'return':
                                                $iconClass = 'fas fa-undo';
                                                $iconBg = 'return';
                                                break;
                                            default:
                                                $iconClass = 'fas fa-info-circle';
                                                $iconBg = 'info';
                                        }
                                        
                                        // Format waktu - perbaikan untuk menampilkan jam yang benar
                                        if (strpos($row['waktu'], ':') !== false) {
                                            // Jika waktu sudah lengkap dengan jam
                                            $waktu = date('d M Y H:i', strtotime($row['waktu']));
                                        } else {
                                            // Jika hanya tanggal, tampilkan tanggal saja
                                            $waktu = date('d M Y', strtotime($row['waktu']));
                                        }
                                ?>
                                <div class="activity-item">
                                    <div class="activity-icon <?php echo $iconBg; ?>">
                                        <i class="<?php echo $iconClass; ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h5 class="activity-title"><?php echo $row['aktivitas']; ?></h5>
                                        <p class="activity-subtitle">Oleh: <?php echo $row['pelaku']; ?></p>
                                    </div>
                                    <div class="activity-time"><?php echo $waktu; ?></div>
                                </div>
                                <?php
                                    }
                                } else {
                                    echo '<div class="text-center py-3">Tidak ada aktivitas terbaru</div>';
                                }

                                mysqli_close($conn);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="summary-card">
                            <h3 class="section-title">
                                <i class="fas fa-exclamation-triangle"></i>
                                Perlu Perhatian
                            </h3>
                            <div class="alert-list">
                                <?php
                                // Koneksi database
                                $conn = mysqli_connect("localhost", "root", "", "perpustakaanbaru");
                                if (!$conn) {
                                    die("Koneksi gagal: " . mysqli_connect_error());
                                }

                                // Peringatan untuk buku yang stoknya hampir habis
                                $queryAlert = "SELECT judul_buku, stok FROM buku WHERE stok < 3 ORDER BY stok ASC LIMIT 99";
                                $resultAlert = mysqli_query($conn, $queryAlert);

                                if (mysqli_num_rows($resultAlert) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultAlert)) {
                                ?>
                                <div class="alert-item warning">
                                    <h5 class="alert-title">Stok Buku Hampir Habis</h5>
                                    <p class="alert-description">
                                        Buku "<?php echo $row['judul_buku']; ?>" tersisa <?php echo $row['stok']; ?> eksemplar
                                    </p>
                                </div>
                                <?php
                                    }
                                }

                                // Peringatan untuk peminjaman yang belum dikembalikan (lebih dari 7 hari)
                                $queryOverdue = "SELECT 
                                                nama_peminjam, 
                                                judul_buku, 
                                                DATEDIFF(CURDATE(), tgl_pinjam) AS hari_terlambat
                                            FROM (
                                                SELECT nama_peminjam, judul_buku, tgl_pinjam 
                                                FROM rekap_peminjaman_siswa 
                                                WHERE status_pengembalian = '0'
                                                UNION ALL
                                                SELECT nama_peminjam, judul_buku, tgl_pinjam 
                                                FROM rekap_peminjaman_staff_guru 
                                                WHERE status_pengembalian = '0'
                                                UNION ALL
                                                SELECT nama_peminjam, judul_buku, tgl_pinjam 
                                                FROM rekap_peminjaman_tamu 
                                                WHERE status_pengembalian = '0'
                                            ) AS semua_peminjaman
                                            WHERE DATEDIFF(CURDATE(), tgl_pinjam) > 7
                                            ORDER BY hari_terlambat DESC
                                            LIMIT 3";
                                $resultOverdue = mysqli_query($conn, $queryOverdue);

                                if (mysqli_num_rows($resultOverdue) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultOverdue)) {
                                ?>
                                <div class="alert-item danger">
                                    <h5 class="alert-title">Peminjaman Terlambat</h5>
                                    <p class="alert-description">
                                        <?php echo $row['nama_peminjam']; ?> - "<?php echo $row['judul_buku']; ?>" (<?php echo $row['hari_terlambat']; ?> hari)
                                    </p>
                                </div>
                                <?php
                                    }
                                }

                                mysqli_close($conn);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Actions -->
            <section class="quick-actions">
                <h2 class="section-title">
                    <i class="fas fa-bolt"></i>
                    Aksi Cepat
                </h2>
                <div class="action-grid">
                    <a href="../admin/akun-member.php" class="action-btn">
                        <i class="fas fa-user-plus"></i>
                        <span>Tambah Member</span>
                    </a>
                    <a href="../admin/daftar-buku.php" class="action-btn">
                        <i class="fas fa-book-medical"></i>
                        <span>Tambah Buku</span>
                    </a>
                    <a href="../admin/pinjam-buku-tamu.php" class="action-btn">
                        <i class="fas fa-handshake"></i>
                        <span>Proses Peminjaman</span>
                    </a>
                    <a href="../admin/rekapitulasi.php" class="action-btn">
                        <i class="fas fa-file-alt"></i>
                        <span>Generate Laporan</span>
                    </a>
                </div>
            </section>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
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

        // Mobile menu close on link click
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const navbar = document.querySelector('.navbar-collapse');
                if (navbar.classList.contains('show')) {
                    bootstrap.Collapse.getInstance(navbar).hide();
                }
            });
        });

        // Logout functionality
        document.getElementById('logoutBtn').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                // Add logout animation
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    // Replace with actual logout logic
                    alert('Logout berhasil!');
                    window.location.href = '../index.php';
                }, 500);
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add fade-in animation to elements when they come into view
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.stat-card, .quick-actions').forEach(el => {
            observer.observe(el);
        });

        // Add hover effects to stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add click effects to action buttons
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Add ripple effect
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = e.offsetX - 10 + 'px';
                ripple.style.top = e.offsetY - 10 + 'px';
                ripple.style.width = '20px';
                ripple.style.height = '20px';
                
                this.style.position = 'relative';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
                
                // Navigate normally (removed preventDefault)
                console.log('Navigating to:', this.getAttribute('href'));
            });
        });

        // Add ripple animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);    
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>