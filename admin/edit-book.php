<?php
// Aktifkan error reporting hanya untuk development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
error_reporting(0);
ini_set('display_errors', 0);

include "../koneksi.php";

// Verifikasi koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$upload_dir = "../assets/images/covers/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Jika ada parameter ID di URL (GET request) - tampilkan form edit
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_buku = intval($_GET['id']);
    
    // Ambil data buku dari database
    $sql = "SELECT * FROM buku WHERE id_buku = $id_buku";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $buku = $result->fetch_assoc();
    } else {
        header("Location: daftar-buku.php?error=book_not_found");
        exit;
    }
}

// Jika form disubmit (POST request) - proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        header("Location: daftar-buku.php?error=invalid_id");
        exit;
    }

    // Ambil data dari form
    $id_buku = intval($_POST['id']);
    $judul = $conn->real_escape_string($_POST['judul_buku']);
    $penulis = $conn->real_escape_string($_POST['penulis']);
    $tahun = intval($_POST['tahun_terbit']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $cover_lama = isset($_POST['cover_lama']) ? $conn->real_escape_string($_POST['cover_lama']) : '';
    
    // Handle stok - gunakan nilai lama jika tidak diisi
    $stok = isset($_POST['stok']) && $_POST['stok'] !== '' ? intval($_POST['stok']) : null;

    // Dapatkan stok saat ini jika tidak diubah
    if ($stok === null) {
        $sql = "SELECT stok FROM buku WHERE id_buku = $id_buku";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stok = $row['stok'];
        } else {
            $stok = 0;
        }
    }

    // Handle file upload
    $cover_filename = $cover_lama;
    if (!empty($_FILES['cover']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $cover_filename = uniqid('cover_', true) . '.' . $ext;
            $target = $upload_dir . $cover_filename;
            
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $target)) {
                // Hapus file lama jika ada
                if (!empty($cover_lama) && file_exists($upload_dir . $cover_lama)) {
                    unlink($upload_dir . $cover_lama);
                }
            } else {
                $cover_filename = $cover_lama;
                header("Location: daftar-buku.php?error=upload_failed");
                exit;
            }
        } else {
            header("Location: daftar-buku.php?error=invalid_file_format");
            exit;
        }
    }

    // Bangun query UPDATE
    $sql = "UPDATE buku SET 
            judul_buku = '$judul',
            penulis = '$penulis',
            tahun_terbit = $tahun,
            genre = '$genre',
            stok = $stok,
            deskripsi = '$deskripsi',
            cover = '$cover_filename'
            WHERE id_buku = $id_buku";

    // Eksekusi query
    if ($conn->query($sql)) {
        header("Location: daftar-buku.php?success=update_berhasil");
        exit;
    } else {
        // Jika gagal, hapus file yang baru diupload
        if ($cover_filename !== $cover_lama && file_exists($upload_dir . $cover_filename)) {
            unlink($upload_dir . $cover_filename);
        }
        header("Location: daftar-buku.php?error=update_gagal");
        exit;
    }
}

// Jika tidak ada parameter ID dan bukan POST request
if (!isset($_GET['id'])) {
    header("Location: daftar-buku.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Perpustakaan SMA KI HAJAR DEWANTORO</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
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
            --danger-color: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--gradient-primary);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            color: var(--text-primary);
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            z-index: -1;
            animation: backgroundShift 20s ease-in-out infinite;
        }

        @keyframes backgroundShift {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            bottom: 10%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .container {
            position: relative;
            z-index: 10;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        /* Back Button */
        .back-btn {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
            z-index: 20;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(44, 57, 104, 0.4);
            color: white;
        }

        /* Main Card */
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 3rem;
            margin: 2rem auto;
            max-width: 800px;
            position: relative;
            overflow: visible;
        }

        .main-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 16px 16px 0 0;
        }

        /* Header */
        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-title {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .form-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 400;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            color: var(--text-primary);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: var(--primary-color);
            font-size: 1rem;
        }

        /* Input Styles */
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(244, 196, 48, 0.25);
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        /* Textarea */
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* File Upload */
        .file-upload-container {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .file-upload-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1rem;
            border: 2px dashed #e0e0e0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            color: var(--text-secondary);
        }

        .file-upload-label:hover {
            border-color: var(--accent-color);
            color: var(--primary-color);
        }

        .current-cover {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(244, 196, 48, 0.1);
            border-radius: 12px;
        }

        .current-cover img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .current-cover-info {
            flex: 1;
        }

        .current-cover-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .current-cover-text {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            gap: 1rem;
        }

        .btn-back {
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.2);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 57, 104, 0.3);
            color: white;
        }

        .btn-submit {
            background: var(--gradient-accent);
            border: none;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(244, 196, 48, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244, 196, 48, 0.3);
            background: var(--gradient-primary);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Success/Error Messages */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.5s ease-out;
        }

        .alert-success {
            background: linear-gradient(135deg, var(--success-color), #218838);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger-color), #c82333);
            color: white;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .main-card {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .form-subtitle {
                font-size: 0.9rem;
            }

            .back-btn {
                top: 0.5rem;
                left: 0.5rem;
                padding: 8px 16px;
                font-size: 0.9rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-back, .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Form Animation */
        .form-group {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }
        .form-group:nth-child(7) { animation-delay: 0.7s; }

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

        /* Row Responsive */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Background Elements -->
    <div class="floating-element">
        <i class="bi bi-book" style="font-size: 3rem; color: rgba(255, 255, 255, 0.3);"></i>
    </div>
    <div class="floating-element">
        <i class="bi bi-journal-bookmark" style="font-size: 2.5rem; color: rgba(255, 255, 255, 0.3);"></i>
    </div>
    <div class="floating-element">
        <i class="bi bi-pencil-square" style="font-size: 2rem; color: rgba(255, 255, 255, 0.3);"></i>
    </div>

    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="form-header">
                <h1 class="form-title">
                    <i class="bi bi-pencil-square"></i>
                    Edit Data Buku
                </h1>
                <p class="form-subtitle">Perbarui informasi buku di Perpustakaan SMA KI HAJAR DEWANTORO</p>
            </div>

            <!-- Form Edit -->
            <form id="editForm" action="edit-book.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($buku['id_buku']) ?>">
                <input type="hidden" name="cover_lama" value="<?= htmlspecialchars($buku['cover']) ?>">
                
                <!-- Cover Upload -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-image"></i>
                        Cover Buku
                    </label>
                    <div class="file-upload-container">
                        <?php if (!empty($buku['cover'])): ?>
                            <div class="current-cover">
                                <img src="../assets/images/covers/<?= htmlspecialchars($buku['cover']) ?>" 
                                     alt="Cover saat ini">
                                <div class="current-cover-info">
                                    <div class="current-cover-title">Cover Saat Ini</div>
                                    <div class="current-cover-text">Biarkan kosong jika tidak ingin mengubah cover</div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="file-upload-wrapper">
                            <input type="file" class="file-upload-input" name="cover" id="cover" accept="image/*">
                            <label for="cover" class="file-upload-label">
                                <i class="bi bi-cloud-upload"></i>
                                <span>Pilih file cover baru (JPG, PNG, GIF)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Judul Buku -->
                <div class="form-group">
                    <label for="judul_buku" class="form-label">
                        <i class="bi bi-book"></i>
                        Judul Buku
                    </label>
                    <input type="text" class="form-control" id="judul_buku" name="judul_buku" 
                           value="<?= htmlspecialchars($buku['judul_buku']) ?>" 
                           placeholder="Masukkan judul buku" required>
                </div>

                <!-- Penulis & Tahun Terbit -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="penulis" class="form-label">
                            <i class="bi bi-person"></i>
                            Penulis
                        </label>
                        <input type="text" class="form-control" id="penulis" name="penulis" 
                               value="<?= htmlspecialchars($buku['penulis']) ?>" 
                               placeholder="Nama penulis" required>
                    </div>
                    <div class="form-group">
                        <label for="tahun_terbit" class="form-label">
                            <i class="bi bi-calendar-event"></i>
                            Tahun Terbit
                        </label>
                        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" 
                               value="<?= htmlspecialchars($buku['tahun_terbit']) ?>" 
                               placeholder="Contoh: 2023" required>
                    </div>
                </div>

                <!-- Genre & Stok -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="genre" class="form-label">
                            <i class="bi bi-tags"></i>
                            Kategori/Genre
                        </label>
                        <input type="text" class="form-control" id="genre" name="genre" 
                               value="<?= htmlspecialchars($buku['genre']) ?>" 
                               placeholder="Contoh: Fiksi, Sejarah, dll" required>
                    </div>
                    <div class="form-group">
                        <label for="stok" class="form-label">
                            <i class="bi bi-box-seam"></i>
                            Stok Buku
                        </label>
                        <input type="number" class="form-control" id="stok" name="stok" 
                               value="<?= htmlspecialchars($buku['stok']) ?>" 
                               placeholder="Jumlah stok" min="0" required>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label for="deskripsi" class="form-label">
                        <i class="bi bi-card-text"></i>
                        Deskripsi Buku
                    </label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" 
                              placeholder="Masukkan deskripsi atau sinopsis buku..." required><?= htmlspecialchars($buku['deskripsi']) ?></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="daftar-buku.php" class="btn-back">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // File upload preview
        document.getElementById('cover').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = document.querySelector('.file-upload-label span');
            
            if (file) {
                label.textContent = `File dipilih: ${file.name}`;
                label.parentElement.style.borderColor = 'var(--accent-color)';
                label.parentElement.style.color = 'var(--primary-color)';
            } else {
                label.textContent = 'Pilih file cover baru (JPG, PNG, GIF)';
                label.parentElement.style.borderColor = '#e0e0e0';
                label.parentElement.style.color = 'var(--text-secondary)';
            }
        });

        // Loading animation untuk submit button
        document.getElementById('editForm').addEventListener('submit', function() {
            const submitBtn = document.querySelector('.btn-submit');
            const btnText = submitBtn.querySelector('span');
            const btnIcon = submitBtn.querySelector('i');
            
            btnIcon.className = 'loading';
            btnText.textContent = 'Menyimpan...';
            submitBtn.disabled = true;
        });

        // Form validation
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const requiredFields = ['judul_buku', 'penulis', 'tahun_terbit', 'genre', 'stok', 'deskripsi'];
            let isValid = true;
            
            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--danger-color)';
                    isValid = false;
                } else {
                    field.style.borderColor = '#e0e0e0';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi!');
                
                // Reset button
                const submitBtn = document.querySelector('.btn-submit');
                const btnText = submitBtn.querySelector('span');
                const btnIcon = submitBtn.querySelector('i');
                
                btnIcon.className = 'bi bi-check-circle';
                btnText.textContent = 'Simpan Perubahan';
                submitBtn.disabled = false;
            }
        });

        // Auto-resize textarea
        const textarea = document.getElementById('deskripsi');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>
</body>
</html>