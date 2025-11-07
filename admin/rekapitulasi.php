<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "../koneksi.php";

// Ambil role dari URL (default: siswa)
$role = isset($_GET['role']) ? $_GET['role'] : 'siswa';

// Tangkap tahun dan bulan dulu (default ke tahun sekarang)
$bulan = isset($_GET['bulan']) ? strtoupper($_GET['bulan']) : 'JANUARI';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Query default (kosong)
$results = [];
$total = 0;

// Ambil data sesuai role
if ($role === 'siswa') {
    $bulan_map = [
        'JANUARI' => 'January',
        'FEBRUARI' => 'February',
        'MARET' => 'March',
        'APRIL' => 'April',
        'MEI' => 'May',
        'JUNI' => 'June',
        'JULI' => 'July',
        'AGUSTUS' => 'August',
        'SEPTEMBER' => 'September',
        'OKTOBER' => 'October',
        'NOVEMBER' => 'November',
        'DESEMBER' => 'December'
    ];
    $bulan_en = $bulan_map[$bulan] ?? $bulan;

    if (!isset($tahun)) {
        $tahun = date('Y');
    }

    $stmt = $conn->prepare("SELECT 
                            r.id_rekap_siswa,
                            r.nama_peminjam AS nama,
                            r.no_telpon,
                            r.kelas,
                            r.judul_buku,
                            r.tgl_pinjam,
                            r.status_pengembalian,
                            r.id_buku
                          FROM rekap_peminjaman_siswa r
                          WHERE MONTHNAME(r.tgl_pinjam) = ? AND YEAR(r.tgl_pinjam) = ?");
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ss", $bulan_en, $tahun);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);
    $total = count($results);

} elseif ($role === 'staff-guru') {
    $bulan_map = [
        'JANUARI' => 'January',
        'FEBRUARI' => 'February',
        'MARET' => 'March',
        'APRIL' => 'April',
        'MEI' => 'May',
        'JUNI' => 'June',
        'JULI' => 'July',
        'AGUSTUS' => 'August',
        'SEPTEMBER' => 'September',
        'OKTOBER' => 'October',
        'NOVEMBER' => 'November',
        'DESEMBER' => 'December'
    ];
    $bulan_en = $bulan_map[$bulan] ?? $bulan;

    $stmt = $conn->prepare("SELECT 
                            r.id_rekap_staff_guru,
                            r.nama_peminjam AS nama,
                            r.id_buku,
                            b.judul_buku,
                            r.tgl_pinjam,
                            r.status_pengembalian
                          FROM rekap_peminjaman_staff_guru r
                          LEFT JOIN buku b ON r.id_buku = b.id_buku
                          WHERE MONTHNAME(r.tgl_pinjam) = ? AND YEAR(r.tgl_pinjam) = ?");
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ss", $bulan_en, $tahun);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);
    $total = count($results);

} elseif ($role === 'tamu') {
    $bulan_map = [
        'JANUARI' => 'January',
        'FEBRUARI' => 'February',
        'MARET' => 'March',
        'APRIL' => 'April',
        'MEI' => 'May',
        'JUNI' => 'June',
        'JULI' => 'July',
        'AGUSTUS' => 'August',
        'SEPTEMBER' => 'September',
        'OKTOBER' => 'October',
        'NOVEMBER' => 'November',
        'DESEMBER' => 'December'
    ];
    $bulan_en = $bulan_map[$bulan] ?? $bulan;

    if (!isset($tahun)) {
        $tahun = date('Y');
    }

    $stmt = $conn->prepare("SELECT 
                            r.id_rekap_tamu,
                            r.nama_peminjam AS nama, 
                            r.notelpon, 
                            r.id_buku, 
                            COALESCE(b.judul_buku, r.judul_buku) AS judul_buku, 
                            r.keperluan,
                            r.tgl_pinjam, 
                            r.status_pengembalian 
                          FROM rekap_peminjaman_tamu r
                          LEFT JOIN buku b ON r.id_buku = b.id_buku
                          WHERE MONTHNAME(r.tgl_pinjam) = ? 
                          AND YEAR(r.tgl_pinjam) = ?");
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $tahun_int = (int)$tahun;
    $stmt->bind_param("si", $bulan_en, $tahun_int);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);
    $total = count($results);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi - Perpustakaan</title>
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
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --header-height: 70px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
            padding-top: var(--header-height);
        }

        /* Full Width Header */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--header-height);
            background: var(--gradient-primary);
            color: white;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            z-index: 1100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-logo {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .header-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .header-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .header-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            z-index: 1000;
            position: fixed;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
            padding: 1.5rem 1rem;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .tab-item span {
            display: none;
        }

        .sidebar.collapsed .tab-item {
            justify-content: center;
            padding: 15px 0;
        }

        /* Back Button Styling */
        .back-button {
            margin-bottom: 1.5rem;
        }

        .back-button .btn {
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
            width: 100%;
        }

        .back-button .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 57, 104, 0.2);
        }

        /* Tab Items */
        .tab-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: var(--text-secondary);
            transition: all 0.3s ease;
            border-radius: 8px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .tab-item:hover {
            background-color: rgba(44, 57, 104, 0.05);
            color: var(--primary-color);
        }

        .tab-item.active {
            background-color: rgba(44, 57, 104, 0.1);
            color: var(--primary-color);
            font-weight: 600;
        }

        .tab-item i {
            font-size: 1.1rem;
            margin-right: 12px;
            min-width: 20px;
            text-align: center;
        }

        /* Navigation Section Title */
        .nav-section-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0;
            margin-top: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background-color: rgba(44, 57, 104, 0.05);
            border-radius: 8px;
            width: 100%;
            border: none;
            text-align: left;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .nav-section-title:hover {
            background-color: rgba(44, 57, 104, 0.1);
        }

        .nav-section-title i:first-child {
            font-size: 1rem;
            color: var(--primary-color);
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            font-size: 0.8rem;
            margin-left: auto;
        }

        /* Rotate icon ketika expanded */
        .nav-section-title[aria-expanded="true"] .toggle-icon {
            transform: rotate(180deg);
        }

        /* Style untuk konten yang di-collapse */
        #userTabs {
            margin-top: 8px;
            margin-bottom: 1rem;
        }

        /* Accordion Styling */
        .accordion-item {
            border: 1px solid #e0e0e0;
            border-radius: 8px !important;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .accordion-button {
            background-color: transparent;
            border: none;
            padding: 12px 15px;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(44, 57, 104, 0.05);
            color: var(--primary-color);
            box-shadow: none;
        }

        .accordion-body {
            padding: 0;
        }

        /* Navigation Pills dalam Accordion */
        .nav-pills .nav-link {
            background-color: transparent;
            border-radius: 6px;
            margin-bottom: 4px;
            padding: 8px 15px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .nav-pills .nav-link:hover {
            background-color: rgba(44, 57, 104, 0.05);
            color: var(--primary-color);
        }

        .nav-pills .nav-link.active {
            background-color: rgba(44, 57, 104, 0.1);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Welcome Section */
        .welcome-section {
            background: var(--gradient-primary);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Table Section */
        .table-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--accent-color);
        }

        .search-box {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 10px rgba(44, 57, 104, 0.08);
        }

        .search-input {
            flex: 1;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(244, 196, 48, 0.25);
            outline: none;
        }

        .print-btn {
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
        }

        .total-peminjam {
            background: var(--gradient-primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: var(--gradient-primary);
            color: white;
            padding: 1rem;
            font-weight: 600;
            border: none;
            position: sticky;
            top: 0;
        }

        .table thead th:first-child {
            border-radius: 12px 0 0 12px;
        }

        .table thead th:last-child {
            border-radius: 0 12px 12px 0;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(44, 57, 104, 0.05);
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .btn-action i {
            font-size: 0.9rem;
        }

        /* Toggle Button */
        .btn-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .sidebar {
                width: var(--sidebar-collapsed);
            }
            
            .sidebar .nav-section-title,
            .sidebar .tab-item span {
                display: none;
            }
            
            .sidebar .tab-item {
                justify-content: center;
                padding: 15px 0;
            }
            
            .main-content {
                margin-left: var(--sidebar-collapsed);
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .header-title {
                display: none;
            }
            
            .welcome-title {
                font-size: 1.5rem;
            }
            
            .search-box {
                flex-direction: column;
            }
            
            .print-btn {
                width: 100%;
                justify-content: center;
            }
            
            .table thead {
                display: none;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            
            .table tbody td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .table tbody td::before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                width: calc(50% - 1rem);
                padding-right: 1rem;
                font-weight: 600;
                text-align: left;
                color: var(--text-primary);
            }
        }
    </style>
</head>
<body>
    <!-- Main Header -->
    <header class="main-header">
        <div class="header-content">
            <div class="header-brand">
                <button class="btn-toggle d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="header-title">Perpustakaan SMA KI HAJAR DEWANTORO</div>
            </div>
            <div class="header-actions">
                <a href="dashboard-admin.php" class="header-btn">
                    <i class="fas fa-home"></i>
                    <span class="d-none d-md-inline">Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Tombol Back -->
        <div class="back-button">
            <a href="dashboard-admin.php" class="btn">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>

        <!-- Tab Pengguna -->
        <!-- Tombol toggle yang akan mengontrol collapse -->
        <button class="nav-section-title" type="button" data-bs-toggle="collapse" data-bs-target="#userTabs" aria-expanded="true" aria-controls="userTabs">
            <i class="fas fa-users"></i>
            <span>Jenis Pengguna</span>
            <i class="fas fa-chevron-down ms-auto toggle-icon"></i>
        </button>

        <div class="collapse show" id="userTabs">
            <a href="?role=siswa&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="tab-item <?= $role == 'siswa' ? 'active' : '' ?>">
                <i class="fas fa-user-graduate"></i>
                <span>Siswa</span>
            </a>
            <a href="?role=staff-guru&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="tab-item <?= $role == 'staff-guru' ? 'active' : '' ?>">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Staff/Guru</span>
            </a>
            <a href="?role=tamu&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="tab-item <?= $role == 'tamu' ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Tamu</span>
            </a>
        </div>

        <!-- Tahun -->
        <h3 class="nav-section-title">Tahun</h3>
        <div class="accordion" id="yearAccordion">
            <?php
            $years = range(2025, 2030);
            foreach ($years as $year):
            ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?= $year ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $year ?>" aria-expanded="false" aria-controls="collapse<?= $year ?>">
                            <?= $year ?>
                        </button>
                    </h2>
                    <div id="collapse<?= $year ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $year ?>" data-bs-parent="#yearAccordion">
                        <div class="accordion-body p-0">
                            <!-- Navigasi Bulan -->
                            <ul class="nav nav-pills flex-column">
                                <?php
                                $months = [
                                    'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI',
                                    'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'
                                ];
                                foreach ($months as $month):
                                ?>
                                    <li class="nav-item">
                                        <a href="?role=<?= $role ?>&bulan=<?= $month ?>&tahun=<?= $year ?>" class="nav-link <?= ($bulan == $month && (isset($_GET['tahun']) && $_GET['tahun'] == $year)) ? 'active' : '' ?>">
                                            <?= $month ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <div class="welcome-content">
                <h1 class="welcome-title">Rekapitulasi Peminjaman</h1>
                <p class="welcome-subtitle">Perpustakaan SMA Ki Hajar Dewantoro</p>
            </div>
        </section>

        <!-- Table Section -->
        <section class="table-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">
                    <i class="fas fa-table"></i>
                    Data Rekapitulasi
                </h2>
                <div class="total-peminjam">
                    Total Peminjam: <?= $total ?>
                </div>
            </div>
            
            <div class="search-box">
                <input type="text" class="search-input" id="searchInput" placeholder="Cari di tabel...">
                <button type="button" class="print-btn" id="printBtn">
                    <i class="fas fa-print"></i>
                    <span>Cetak Data</span>
                </button>
            </div>
            
            <div class="table-responsive">
                <?php if ($role === 'siswa'): ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>No Telp</th>
                                <th>Kelas</th>
                                <th>Judul Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="rekapTableBody">
                            <?php if (!empty($results)): ?>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td data-label="No"><?= htmlspecialchars($row['id_rekap_siswa']) ?></td>
                                        <td data-label="Nama Siswa"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td data-label="No Telp"><?= htmlspecialchars($row['no_telpon'] ?? '-') ?></td>
                                        <td data-label="Kelas"><?= htmlspecialchars($row['kelas'] ?? '-') ?></td>
                                        <td data-label="Judul Buku">
                                            <?= !empty($row['judul_buku']) ? htmlspecialchars($row['judul_buku']) : 
                                            (!empty($row['id_buku']) ? 'Buku ID: '.htmlspecialchars($row['id_buku']) : '-') ?>
                                        </td>
                                        <td data-label="Tanggal Pinjam"><?= htmlspecialchars($row['tgl_pinjam']) ?></td>
                                        <td data-label="Status">
                                            <?= ($row['status_pengembalian'] == '1') ? 
                                                '<span class="badge bg-success">Sudah Dikembalikan</span>' : 
                                                '<span class="badge bg-warning text-dark">Belum Dikembalikan</span>' ?>
                                        </td>
                                        <td data-label="Aksi">
                                            <?php if ($row['status_pengembalian'] != '1'): ?>
                                                <a href="return-book.php?id=<?= $row['id_rekap_siswa'] ?>&id_buku=<?= $row['id_buku'] ?>&nama=<?= urlencode($row['nama']) ?>&role=<?= $role ?>" 
                                                    class="btn-action btn btn-success btn-sm" 
                                                    onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                                    <i class="fas fa-undo-alt"></i>
                                                    <span class="d-none d-md-inline">Kembalikan</span>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php elseif ($role === 'staff-guru'): ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Judul Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="rekapTableBody">
                            <?php if (!empty($results)): ?>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td data-label="No"><?= htmlspecialchars($row['id_rekap_staff_guru']) ?></td>
                                        <td data-label="Nama"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td data-label="Judul Buku">
                                            <?= !empty($row['judul_buku']) ? htmlspecialchars($row['judul_buku']) : 
                                            (!empty($row['id_buku']) ? 'Buku ID: '.htmlspecialchars($row['id_buku']) : '-') ?>
                                        </td>
                                        <td data-label="Tanggal Pinjam"><?= htmlspecialchars($row['tgl_pinjam']) ?></td>
                                        <td data-label="Status">
                                            <?= ($row['status_pengembalian'] == '1') ? 
                                                '<span class="badge bg-success">Sudah Dikembalikan</span>' : 
                                                '<span class="badge bg-warning text-dark">Belum Dikembalikan</span>' ?>
                                        </td>
                                        <td data-label="Aksi">
                                            <?php if ($row['status_pengembalian'] != '1'): ?>
                                                <a href="return-book.php?id_rekap_staff_guru=<?= $row['id_rekap_staff_guru'] ?>&id_buku=<?= $row['id_buku'] ?>&nama=<?= urlencode($row['nama']) ?>&role=<?= $role ?>" 
                                                    class="btn-action btn btn-success btn-sm" 
                                                    onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                                    <i class="fas fa-undo-alt"></i>
                                                    <span class="d-none d-md-inline">Kembalikan</span>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <!-- Tampilan untuk tamu -->
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No. Telepon</th>
                                <th>Judul Buku</th>
                                <th>Keperluan</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="rekapTableBody">
                            <?php if (!empty($results)): ?>
                                <?php foreach ($results as $row): ?>
                                    <tr>
                                        <td data-label="No"><?= htmlspecialchars($row['id_rekap_tamu']) ?></td>
                                        <td data-label="Nama"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td data-label="No. Telepon"><?= htmlspecialchars($row['notelpon'] ?? '-') ?></td>
                                        <td data-label="Judul Buku">
                                            <?= !empty($row['judul_buku']) ? htmlspecialchars($row['judul_buku']) : 
                                            (!empty($row['id_buku']) ? 'Buku ID: '.htmlspecialchars($row['id_buku']) : '-') ?>
                                        </td>
                                        <td data-label="Keperluan"><?= htmlspecialchars($row['keperluan'] ?? '-') ?></td>
                                        <td data-label="Tanggal Pinjam"><?= htmlspecialchars($row['tgl_pinjam']) ?></td>
                                        <td data-label="Status">
                                            <?= ($row['status_pengembalian'] == '1') ? 
                                                '<span class="badge bg-success">Sudah Dikembalikan</span>' : 
                                                '<span class="badge bg-warning text-dark">Belum Dikembalikan</span>' ?>
                                        </td>
                                        <td data-label="Aksi">
                                            <?php if ($row['status_pengembalian'] != '1'): ?>
                                                <a href="return-book.php?id=<?= $row['id_rekap_tamu'] ?>&id_buku=<?= $row['id_buku'] ?>&role=<?= $role ?>" 
                                                    class="btn-action btn btn-success btn-sm" 
                                                    onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                                    <i class="fas fa-undo-alt"></i>
                                                    <span class="d-none d-md-inline">Kembalikan</span>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#rekapTableBody tr');

            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                let found = false;
                
                // Skip the last cell (aksi)
                for (let i = 0; i < cells.length - 1; i++) {
                    if (cells[i].textContent.toLowerCase().includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }
                
                row.style.display = found ? '' : 'none';
            });
        });

        // Print functionality
        document.getElementById('printBtn').addEventListener('click', function() {
            // Clone the printable section
            const printContent = document.querySelector('.table-responsive').cloneNode(true);
            
            // Create a new window
            const printWindow = window.open('', '_blank');
            
            // Add styles and content
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Cetak Rekapitulasi</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            h2 { text-align: center; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #2c3968; color: white; }
                            .footer { margin-top: 30px; text-align: right; }
                            @page { size: auto; margin: 5mm; }
                        </style>
                    </head>
                    <body>
                        <h2>Rekapitulasi Peminjaman - <?= ucfirst(str_replace('-', '/', $role)) ?></h2>
                        <p>Bulan: <?= $bulan ?> <?= $tahun ?></p>
                        <p>Total Peminjam: <?= $total ?></p>
                        ${printContent.innerHTML}
                        <div class="footer">
                            <p>Dicetak pada: ${new Date().toLocaleString()}</p>
                            <p>Perpustakaan SMA KI HAJAR DEWANTORO</p>
                        </div>
                    </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
            
            // Delay print to ensure content is loaded
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        });
    </script>
</body>
</html>