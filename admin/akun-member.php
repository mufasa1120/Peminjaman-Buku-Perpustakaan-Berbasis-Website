<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Member - Perpustakaan</title>
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
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --sidebar-width: 280px;
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

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
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

        /* Search Controls */
        .search-controls {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            position: relative;
            flex: 1;
            min-width: 250px;
        }

        .search-input i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            z-index: 2;
        }

        .search-input .form-control {
            padding: 12px 16px 12px 45px;
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .search-input .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 57, 104, 0.25);
        }

        .btn-add {
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

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(244, 196, 48, 0.4);
            color: white;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .data-table {
            padding: 2rem;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: var(--gradient-primary);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table thead th:first-child {
            border-radius: 12px 0 0 12px;
        }

        .table thead th:last-child {
            border-radius: 0 12px 12px 0;
        }

        .table tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e8ecf4;
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9fc 0%, #e8ecf4 100%);
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
        }

        /* Buttons */
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
            border-radius: 8px;
            font-weight: 500;
            margin-right: 0.5rem;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background: var(--gradient-accent);
            color: white;
            box-shadow: 0 2px 8px rgba(244, 196, 48, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(244, 196, 48, 0.4);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
            color: white;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 2rem;
            text-align: center;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        /* Loading State */
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e8ecf4;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(44, 57, 104, 0.3);
        }

        .modal-header {
            background: var(--gradient-primary);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 1.5rem 2rem;
            border: none;
        }

        .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .modal-title i {
            margin-right: 12px;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-label i {
            margin-right: 8px;
            color: var(--accent-color);
        }

        .form-control, .form-select {
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            padding: 12px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 57, 104, 0.25);
        }

        .btn-submit {
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
            width: 100%;
            justify-content: center;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(44, 57, 104, 0.4);
            color: white;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(200, 35, 51, 0.1));
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        /* Mobile Styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 100%;
                border-radius: 0;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1035;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .mobile-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .mobile-toggle {
                display: block;
                background: var(--gradient-primary);
                border: none;
                color: white;
                padding: 8px 12px;
                border-radius: 8px;
                margin-right: 1rem;
            }

            .content-header {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .content-title {
                font-size: 1.5rem;
            }

            .search-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                min-width: auto;
            }

            .header-title {
                display: none;
            }
        }

        @media (min-width: 993px) {
            .mobile-toggle {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="header-content">
            <div class="header-brand">
                <button class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-logo">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="header-title">Manajemen Akun Member</div>
            </div>
            <div class="header-actions">
                <a href="dashboard-admin.php" class="header-btn">
                    <i class="fas fa-home"></i>
                    <span class="d-none d-md-inline">Dashboard</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <!-- Tombol Back -->
            <div class="back-button">
                <a href="dashboard-admin.php" class="btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>

            <div class="sidebar-header">
                <h2 class="sidebar-title">
                    <i class="fas fa-users-cog"></i>
                    Daftar Akun
                </h2>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Kategori Pengguna</div>
                <a href="javascript:void(0)" class="tab-item active" data-role="siswa" aria-current="page">
                    <i class="fas fa-user-graduate"></i>
                    <span>Siswa</span>
                </a>
                <a href="javascript:void(0)" class="tab-item" data-role="staff-guru">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Staff/Guru</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Welcome Section -->
            <section class="welcome-section">
                <div class="welcome-content">
                    <h1 class="welcome-title">Manajemen Akun Member</h1>
                    <p class="welcome-subtitle">Perpustakaan SMA Ki Hajar Dewantoro</p>
                </div>
            </section>

            <!-- Search Controls -->
            <div class="search-controls">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari berdasarkan nama, NISN/NIG atau kelas...">
                </div>
                <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus"></i>Tambah Akun
                </button>
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <div class="data-table">
                    <div class="table-responsive">
                        <table class="table" id="userTable">
                            <!-- Tabel untuk Siswa -->
                            <thead class="siswa-table">
                                <tr>
                                    <th>Nama</th>
                                    <th>NISN</th>
                                    <th>No. Telp</th>
                                    <th>Kelas</th>
                                    <th>Password</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <!-- Tabel untuk Staff-Guru -->
                            <thead class="staff-guru-table d-none">
                                <tr>
                                    <th>Nama</th>
                                    <th>NIG</th>
                                    <th>Password</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                <!-- Data akan dimuat secara dinamis -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Loading State -->
                <div class="loading d-none" id="loadingState">
                    <div class="spinner"></div>
                </div>
                <!-- Empty State -->
                <div class="empty-state text-center p-5 d-none" id="emptyState">
                    <i class="fas fa-user-slash"></i>
                    <h5>Tidak ada data ditemukan</h5>
                    <p class="text-muted">Belum ada akun yang terdaftar untuk kategori ini.</p>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Adding User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">
                        <i class="fas fa-user-plus"></i>Registrasi Akun Siswa & Guru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Alert Messages -->
                    <div class="alert alert-success d-none" id="successAlert"></div>
                    <div class="alert alert-danger d-none" id="errorAlert"></div>
                    <form id="addUserForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user"></i>Nama Lengkap
                                </label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nuptk" class="form-label">
                                    <i class="fas fa-id-card"></i><span id="idLabel">NISN</span>
                                </label>
                                <input type="text" class="form-control" id="nuptk" name="nuptk" required>
                            </div>
                        </div>
                        <!-- No. Telepon untuk Siswa -->
                        <div class="row">
                            <div class="col-md-6 mb-3" id="noTelpGroup">
                                <label for="no_telp" class="form-label">
                                    <i class="fas fa-phone"></i>No. Telepon
                                </label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Contoh: 081288276543">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-briefcase"></i>Jabatan
                                </label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    <option value="siswa">Siswa</option>
                                    <option value="staff-guru">Staff/Guru</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="kelasGroup" style="display: none;">
                                <label for="kelas" class="form-label">
                                    <i class="fas fa-school"></i>Kelas
                                </label>
                                <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Contoh: 10 IPA 2">
                            </div>
                        </div>
                        <!-- Tipe Staff/Guru (hanya muncul jika role=staff-guru) -->
                        <div class="row">
                            <div class="col-md-6 mb-3" id="guruRoleGroup" style="display: none;">
                                <label for="guruRole" class="form-label">
                                    <i class="fas fa-user-tie"></i>Tipe Staff
                                </label>
                                <select class="form-select" id="guruRole" name="guruRole">
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="staff">Staff</option>
                                    <option value="guru">Guru</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i>Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-check-circle"></i>Buat Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        let currentRole = 'siswa'; // Inisialisasi role awal
        let userData = { siswa: [], 'staff-guru': [] };
        let filteredData = [];

        // Mobile menu functionality
        document.getElementById('mobileToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        document.getElementById('mobileOverlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Fungsi untuk menghindari XSS
        function escapeHtml(unsafe) {
            return unsafe.toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Fungsi untuk memuat data pengguna
        async function loadUserData(role) {
            try {
                const response = await fetch(`get-users.php?role=${role}`);
                const data = await response.json();
                userData[role] = data;
                filteredData = [...data];
                renderUserTable(filteredData);
                toggleTableHeaders(role);
            } catch (error) {
                console.error('Error fetching data:', error);
                showAlert('error', 'Gagal memuat data!');
            }
        }

    // Fungsi untuk mengirim data pengguna baru
    async function submitForm() {
        const form = document.getElementById('addUserForm');
        const isEdit = form.dataset.editIndex !== undefined;
        
        const formData = {
            nama: document.getElementById('username').value,
            nuptk: document.getElementById('nuptk').value,
            role: document.getElementById('role').value,
            password: document.getElementById('password').value,
            no_telp: document.getElementById('no_telp').value || '',
            kelas: document.getElementById('kelas')?.value || '-',
            guruRole: document.getElementById('guruRole')?.value || 'staff'
        };

        // Jika edit, tambahkan ID pengguna
        if (isEdit) {
            formData.id = form.dataset.userId;
        }

        try {
            const endpoint = isEdit ? 'update-user.php' : 'save-user.php';
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            if (result.status === 'success') {
                showAlert('success', isEdit ? 'Akun berhasil diperbarui!' : 'Akun berhasil ditambahkan!');
                loadUserData(currentRole);
                
                // Reset form dan hapus data edit
                form.reset();
                delete form.dataset.editIndex;
                delete form.dataset.userId;
                
                // Sembunyikan modal
                bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
            } else {
                showAlert('error', result.message);
            }
        } catch (error) {
            showAlert('error', 'Terjadi kesalahan server');
        }
    }

    // Fungsi untuk mengedit pengguna
    async function editUser(index) {
        const user = filteredData[index];
        const isSiswa = currentRole === 'siswa';
        
        // Generate modal HTML dengan desain yang sama dengan modal tambah
        const modalHtml = `
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">
                            <i class="fas fa-user-edit"></i>Edit Akun ${escapeHtml(user.nama)}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success d-none" id="editSuccessAlert"></div>
                        <div class="alert alert-danger d-none" id="editErrorAlert"></div>
                        <form id="editUserForm">
                            <input type="hidden" name="old_nuptk" value="${isSiswa ? user.nisn : user.nig}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editNama" class="form-label">
                                        <i class="fas fa-user"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" id="editNama" name="nama" value="${escapeHtml(user.nama)}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editNuptk" class="form-label">
                                        <i class="fas fa-id-card"></i>${isSiswa ? 'NISN' : 'NIG'}
                                    </label>
                                    <input type="text" class="form-control" id="editNuptk" name="nuptk" value="${isSiswa ? user.nisn : user.nig}" required>
                                </div>
                            </div>
                            ${isSiswa ? `
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editNoTelp" class="form-label">
                                        <i class="fas fa-phone"></i>No. Telepon
                                    </label>
                                    <input type="text" class="form-control" id="editNoTelp" name="no_telp" value="${user.no_telp || ''}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editKelas" class="form-label">
                                        <i class="fas fa-school"></i>Kelas
                                    </label>
                                    <input type="text" class="form-control" id="editKelas" name="kelas" value="${user.kelas || ''}">
                                </div>
                            </div>
                            ` : ''}
                            ${!isSiswa ? `
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editGuruRole" class="form-label">
                                        <i class="fas fa-user-tie"></i>Tipe Staff
                                    </label>
                                    <select class="form-select" id="editGuruRole" name="guruRole">
                                        <option value="staff" ${user.role === 'staff' ? 'selected' : ''}>Staff</option>
                                        <option value="guru" ${user.role === 'guru' ? 'selected' : ''}>Guru</option>
                                    </select>
                                </div>
                            </div>
                            ` : ''}
                            <div class="mb-4">
                                <label for="editPassword" class="form-label">
                                    <i class="fas fa-lock"></i>Password Baru
                                </label>
                                <input type="password" class="form-control" id="editPassword" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                <div class="form-text">Minimal 8 karakter</div>
                            </div>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-check-circle"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        // Tambahkan modal ke DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Tampilkan modal
        const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        editModal.show();
        
        // Handle form submission
        document.getElementById('editUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                role: currentRole,
                old_nuptk: formData.get('old_nuptk'),
                nama: formData.get('nama'),
                nuptk: formData.get('nuptk'),
                password: formData.get('password') || null
            };
            
            if (currentRole === 'siswa') {
                data.no_telp = formData.get('no_telp');
                data.kelas = formData.get('kelas');
            } else {
                data.guruRole = formData.get('guruRole');
            }
            
            try {
                // Tampilkan loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
                
                const response = await fetch('update-user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    document.getElementById('editSuccessAlert').textContent = 'Akun berhasil diperbarui!';
                    document.getElementById('editSuccessAlert').classList.remove('d-none');
                    document.getElementById('editErrorAlert').classList.add('d-none');
                    
                    // Muat ulang data setelah 1 detik
                    setTimeout(() => {
                        loadUserData(currentRole);
                        editModal.hide();
                    }, 1000);
                } else {
                    throw new Error(result.message || 'Gagal memperbarui akun');
                }
            } catch (error) {
                document.getElementById('editErrorAlert').textContent = error.message;
                document.getElementById('editErrorAlert').classList.remove('d-none');
                document.getElementById('editSuccessAlert').classList.add('d-none');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check-circle"></i>Simpan Perubahan';
                }
            }
        });
        
        // Hapus modal saat ditutup
        document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    // Fungsi untuk menghapus pengguna
    async function deleteUser(index) {
        const user = filteredData[index];
        if (confirm(`Hapus ${user.nama}?`)) {
            try {
                const response = await fetch('delete-user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        role: currentRole, 
                        id: currentRole === 'siswa' ? user.nisn : user.nig 
                    })
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showAlert('success', 'Akun berhasil dihapus');
                    loadUserData(currentRole); // Muat ulang data setelah penghapusan
                } else {
                    showAlert('error', result.message || 'Gagal menghapus akun');
                }
            } catch (error) {
                showAlert('error', 'Terjadi kesalahan saat menghapus akun');
            }
        }
    }

    // Fungsi untuk merender tabel
    function renderUserTable(data) {
        const tbody = document.getElementById('userTableBody');
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            document.getElementById('emptyState').classList.remove('d-none');
            document.querySelector('.data-table').classList.add('d-none');
            return;
        }

        data.forEach((user, index) => {
            const row = document.createElement('tr');
            
            if (currentRole === 'siswa') {
                row.innerHTML = `
                    <td><strong>${escapeHtml(user.nama)}</strong></td>
                    <td><span class="badge bg-secondary">${escapeHtml(user.nisn)}</span></td>
                    <td>${user.no_telp || '-'}</td>
                    <td>${user.kelas || '-'}</td>
                    <td><code>••••••••</code></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editUser(${index})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser(${index})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                `;
            } else {
                row.innerHTML = `
                    <td><strong>${escapeHtml(user.nama)}</strong></td>
                    <td><span class="badge bg-secondary">${escapeHtml(user.nig)}</span></td>
                    <td><code>••••••••</code></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editUser(${index})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser(${index})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                `;
            }
            
            tbody.appendChild(row);
        });
        
        document.getElementById('emptyState').classList.add('d-none');
        document.querySelector('.data-table').classList.remove('d-none');
    }

    // Fungsi untuk menampilkan/menyembunyikan header tabel
    function toggleTableHeaders(role) {
        document.querySelectorAll('.staff-guru-table, .siswa-table').forEach(el => el.classList.add('d-none'));
        
        if (role === 'siswa') {
            document.querySelector('.siswa-table').classList.remove('d-none');
        } else {
            document.querySelector('.staff-guru-table').classList.remove('d-none');
        }
    }

    // Fungsi untuk menampilkan alert
    function showAlert(type, message) {
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        
        if (type === 'success') {
            successAlert.textContent = message;
            successAlert.classList.remove('d-none');
            errorAlert.classList.add('d-none');
        } else {
            errorAlert.textContent = message;
            errorAlert.classList.remove('d-none');
            successAlert.classList.add('d-none');
        }
        
        setTimeout(() => {
            successAlert.classList.add('d-none');
            errorAlert.classList.add('d-none');
        }, 3000);
    }

    // Fungsi untuk memfilter pengguna
    function filterUsers(query) {
        const data = userData[currentRole] || [];
        if (!query) {
            filteredData = [...data];
        } else {
            filteredData = data.filter(user => 
                user.nama.toLowerCase().includes(query) ||
                (currentRole === 'siswa' ? user.nisn.includes(query) : user.nig.includes(query)) ||
                (user.kelas && user.kelas.toLowerCase().includes(query))
            );
        }
        renderUserTable(filteredData);
    }

    // Fungsi untuk mengubah tab
    function switchTab(role, tabElement) {
        document.querySelectorAll('.tab-item').forEach(tab => tab.classList.remove('active'));
        tabElement.classList.add('active');
        currentRole = role;
        toggleTableHeaders(role);
        loadUserData(role);
        document.getElementById('searchInput').value = '';
        if (window.innerWidth <= 992) {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('mobileOverlay').classList.remove('show');
        }
    }

    // Setup handler untuk input pencarian
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            filterUsers(query);
        });

        // Event listener untuk form tambah user
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        // Event listener untuk tab
        document.querySelectorAll('.tab-item').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const role = this.dataset.role;
                switchTab(role, this);
            });
        });

        // Inisialisasi form handlers
        const roleSelect = document.getElementById('role');
        const kelasGroup = document.getElementById('kelasGroup');
        const noTelpGroup = document.getElementById('noTelpGroup');
        const guruRoleGroup = document.getElementById('guruRoleGroup');
        const idLabel = document.getElementById('idLabel');

        roleSelect.addEventListener('change', function() {
            const selectedRole = this.value;

            if (selectedRole === 'siswa') {
                kelasGroup.style.display = 'block';
                noTelpGroup.style.display = 'block';
                guruRoleGroup.style.display = 'none';
                idLabel.textContent = 'NISN';
            } else {
                kelasGroup.style.display = 'none';
                noTelpGroup.style.display = 'none';
                guruRoleGroup.style.display = 'block';
                idLabel.textContent = 'NIG';
            }
        });

        // Muat data awal untuk siswa
        loadUserData('siswa');
    });
    </script>
</body>
</html>