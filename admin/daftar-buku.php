<?php
include "../koneksi.php"; // file koneksi ke database

// Ambil semua buku dari database (MySQLi)
$query = "SELECT * FROM buku";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$bukuList = $result->fetch_all(MYSQLI_ASSOC);

// Hitung total buku
$totalBuku = count($bukuList);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku - Perpustakaan</title>
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
            border: 2px solid #e0e0f0;
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

        .add-btn {
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

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
        }

        .total-books {
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

        /* Modal Improvements */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: var(--gradient-primary);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.25rem;
            border-bottom: none;
        }

        .modal-header .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #f0f0f0;
            border-radius: 0 0 16px 16px;
            padding: 1.25rem;
        }

        /* Style khusus untuk tombol aksi */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
            min-width: 70px;
            justify-content: center;
        }

        .btn-action i {
            font-size: 0.8rem;
        }

        .btn-action.btn-warning {
            background: linear-gradient(135deg, #ffc107, #ffab00);
            border: none;
            color: #212529;
        }

        .btn-action.btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Responsive untuk tombol aksi */
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 0.3rem;
            }
            
            .btn-action {
                width: 100%;
                padding: 0.5rem;
            }
            
            .table tbody td:last-child {
                text-align: center;
                padding-left: 1rem;
            }
        }

        /* Form Improvements */
        .form-control {
            border: 2px solid #e0e0f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(244, 196, 48, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
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
            
            .add-btn {
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
            
            .table tbody td:last-child {
                text-align: center;
                padding-left: 1rem;
            }
            
            .table tbody td:last-child::before {
                display: none;
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
                    <i class="fas fa-book"></i>
                </div>
                <div class="header-title">Perpustakaan SMA KI HAJAR DEWANTORO</div>
            </div>
            <div class="header-actions">
                <a href="dashboard-admin.php" class="header-btn">
                    <i class="fas fa-home"></i>
                    <span class="d-none d-md-inline">Dashboard</span>
                </a>
                <button class="header-btn" id="cetakDataBtn">
                    <i class="fas fa-print"></i>
                    <span class="d-none d-md-inline">Cetak</span>
                </button>
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
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Section -->
        <section class="welcome-section">
            <div class="welcome-content">
                <h1 class="welcome-title">Daftar Buku Perpustakaan</h1>
                <p class="welcome-subtitle">Kelola koleksi buku perpustakaan</p>
            </div>
        </section>

        <!-- Table Section -->
        <section class="table-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">
                    <i class="fas fa-book-open"></i>
                    Data Buku
                </h2>
                <div class="total-books">
                    Total Buku: <?= $totalBuku ?>
                </div>
            </div>
            
            <div class="search-box">
                <input type="text" class="search-input" id="searchInput" placeholder="Cari buku...">
                <button type="button" class="add-btn" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Buku</span>
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Tahun Terbit</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bookTableBody">
                        <?php if (!empty($bukuList)): ?>
                            <?php foreach ($bukuList as $index => $buku): ?>
                                <tr>
                                    <td data-label="No"><?= $index + 1 ?></td>
                                    <td data-label="Judul Buku"><?= htmlspecialchars($buku['judul_buku']) ?></td>
                                    <td data-label="Penulis"><?= htmlspecialchars($buku['penulis']) ?></td>
                                    <td data-label="Tahun Terbit"><?= htmlspecialchars($buku['tahun_terbit']) ?></td>
                                    <td data-label="Kategori"><?= htmlspecialchars($buku['genre']) ?></td>
                                    <td data-label="Stok">
                                        <span class="badge <?= $buku['stok'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                            <?= htmlspecialchars($buku['stok']) ?>
                                        </span>
                                    </td>
                                    <!-- Improved Delete Button HTML -->
                                    <td data-label="Aksi">
                                        <div class="action-buttons">
                                            <button type="button" class="btn-action btn-warning edit-btn" 
                                                data-id="<?= $buku['id_buku'] ?>"
                                                title="Edit buku">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button type="button" class="btn-action btn-danger delete-btn" 
                                                data-id="<?= $buku['id_buku'] ?>"
                                                data-judul="<?= htmlspecialchars($buku['judul_buku']) ?>"
                                                title="Hapus buku">
                                                <i class="fas fa-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data buku ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Modal for Adding Book -->
    <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookModalLabel">Tambah Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBookForm" action="add-book.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="coverImage" class="form-label">Cover Buku</label>
                            <input type="file" class="form-control" name="cover" id="cover" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="judulBuku" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="judulBuku" name="judul_buku" required>
                        </div>
                        <div class="mb-3">
                            <label for="penulis" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" required>
                        </div>
                        <div class="mb-3">
                            <label for="tahunTerbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="tahunTerbit" name="tahun_terbit" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" id="kategori" name="kategori" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="stokBuku" class="form-label">Stok Buku</label>
                            <input type="number" class="form-control" id="stokBuku" name="stok_buku" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Book -->
    <div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookModalLabel">Edit Data Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBookForm" action="edit-book.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="editIdBuku">
                        <input type="hidden" name="cover_lama" id="editCoverLama">
                        
                        <div class="mb-3">
                            <label for="editCoverImage" class="form-label">Cover Buku (Biarkan kosong jika tidak ingin mengubah)</label>
                            <input type="file" class="form-control" name="cover_image" id="editCoverImage" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="editJudulBuku" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="editJudulBuku" name="judul_buku" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPenulis" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="editPenulis" name="penulis" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTahunTerbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="editTahunTerbit" name="tahun_terbit" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKategori" class="form-label">Kategori</label>
                            <input type="text" class="form-control" id="editKategori" name="genre" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDeskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editStokBuku" class="form-label">Stok Buku</label>
                            <input type="number" class="form-control" id="editStokBuku" name="stok" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            const rows = document.querySelectorAll('#bookTableBody tr');

            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td'));
                let found = false;
                
                // Skip the last cell (aksi)
                for (let i = 1; i < cells.length - 1; i++) {
                    if (cells[i].textContent.toLowerCase().includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }
                
                row.style.display = found ? '' : 'none';
            });
        });

        // Edit button functionality
        // Edit button functionality - Modified to redirect to edit-book.php
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                // Redirect to edit-book.php with the book ID as parameter
                window.location.href = '../admin/edit-book.php?id=' + id;
            });
        });

        // Delete button functionality
        // Delete button functionality - Improved version
        document.addEventListener('DOMContentLoaded', function() {
            // Function to handle delete button clicks
            function handleDeleteClick(button) {
                const id = button.getAttribute('data-id');
                const judulBuku = button.closest('tr').querySelector('td[data-label="Judul Buku"]').textContent;
                
                // Show confirmation dialog with book title
                if (confirm(`Apakah Anda yakin ingin menghapus buku "${judulBuku}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                    // Show loading state
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
                    
                    // Redirect to delete script
                    window.location.href = 'delete-book.php?id=' + id;
                }
            }
            
            // Initial setup for existing delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    handleDeleteClick(this);
                });
            });
            
            // For dynamically added content (if any)
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
                    e.preventDefault();
                    const button = e.target.classList.contains('delete-btn') ? e.target : e.target.closest('.delete-btn');
                    handleDeleteClick(button);
                }
            });
        });

        // Alternative method using event delegation (more reliable)
        document.addEventListener('click', function(e) {
            // Check if clicked element is a delete button or inside a delete button
            const deleteBtn = e.target.closest('.delete-btn');
            if (deleteBtn) {
                e.preventDefault();
                
                const id = deleteBtn.getAttribute('data-id');
                const row = deleteBtn.closest('tr');
                const judulBuku = row.querySelector('td[data-label="Judul Buku"]').textContent.trim();
                
                console.log('Delete button clicked for ID:', id); // Debug log
                
                // Enhanced confirmation dialog
                const confirmMessage = `Apakah Anda yakin ingin menghapus buku berikut?\n\nJudul: ${judulBuku}\nID: ${id}\n\nTindakan ini tidak dapat dibatalkan.`;
                
                if (confirm(confirmMessage)) {
                    // Show loading state
                    deleteBtn.disabled = true;
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menghapus...</span>';
                    
                    // Add slight delay to show loading state
                    setTimeout(() => {
                        window.location.href = 'delete-book.php?id=' + id;
                    }, 500);
                }
            }
        });

        // Print functionality
        document.getElementById('cetakDataBtn').addEventListener('click', function() {
            // Clone the printable section
            const printContent = document.querySelector('.table-responsive').cloneNode(true);
            
            // Create a new window
            const printWindow = window.open('', '_blank');
            
            // Add styles and content
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Cetak Daftar Buku</title>
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
                        <h2>Daftar Buku Perpustakaan</h2>
                        <p>Total Buku: <?= $totalBuku ?></p>
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