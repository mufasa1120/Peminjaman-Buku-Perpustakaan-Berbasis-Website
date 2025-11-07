<?php
session_start();

// Script ../staff/detail-buku.php

// Koneksi ke database
include "../koneksi.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID buku dari URL
$id_buku = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Dapatkan data buku
$query_buku = "SELECT * FROM buku WHERE id_buku = $id_buku";
$result_buku = $conn->query($query_buku);
$buku = $result_buku->fetch_assoc();

if (!$buku) {
    die("Buku tidak ditemukan");
}

// Dapatkan buku serupa
$genre = $buku['genre'];
$query_serupa = "SELECT * FROM buku WHERE genre = '$genre' AND id_buku != $id_buku LIMIT 5";
$result_serupa = $conn->query($query_serupa);
$buku_serupa = [];
while ($row = $result_serupa->fetch_assoc()) {
    $buku_serupa[] = $row;
}

// Proses peminjaman buku
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pinjam'])) {
    // Pengecekan session untuk staff/guru
    if ((isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['staff', 'guru'])) || 
        (isset($_SESSION['role']) && in_array($_SESSION['role'], ['staff', 'guru']))) {
        
        // Cek stok tersedia
        if ($buku['stok'] <= 0) {
            $_SESSION['error_message'] = "Maaf, stok buku ini sudah habis";
            header("Location: detail-buku.php?id=".$id_buku);
            exit();
        }

        $id_user = $_SESSION['id_user'] ?? $_SESSION['id_guru'];
        $nama_peminjam = $_SESSION['nama'];
        $tgl_pinjam = date('Y-m-d');
        $judul_buku = $conn->real_escape_string($buku['judul_buku']);
        
        // Ambil data NIG dan role
        $query_user = "SELECT nig, role FROM staff_guru WHERE id_guru = ?";
        $stmt_user = $conn->prepare($query_user);
        if (!$stmt_user) {
            $_SESSION['error_message'] = "Error prepare data user: " . $conn->error;
            header("Location: detail-buku.php?id=".$id_buku);
            exit();
        }
        $stmt_user->bind_param("i", $id_user);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        
        if ($result_user->num_rows > 0) {
            $data_user = $result_user->fetch_assoc();
            $nig = $conn->real_escape_string($data_user['nig']);
            $role = $data_user['role'];
            
            // Kurangi stok buku
            $stok_baru = $buku['stok'] - 1;
            $update_stok = $conn->query("UPDATE buku SET stok = $stok_baru WHERE id_buku = $id_buku");
            
            // Tambahkan ke rekap peminjaman
            $query_insert = "INSERT INTO rekap_peminjaman_staff_guru 
                            (id_buku, judul_buku, nama_peminjam, nig, tgl_pinjam, role_peminjam) 
                            VALUES 
                            (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($query_insert);
            
            if (!$stmt_insert) {
                $_SESSION['error_message'] = "Error prepare insert: " . $conn->error;
                $conn->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = $id_buku");
                header("Location: detail-buku.php?id=".$id_buku);
                exit();
            }
            
            $bind_result = $stmt_insert->bind_param("isssss", $id_buku, $judul_buku, $nama_peminjam, $nig, $tgl_pinjam, $role);
            
            if (!$bind_result) {
                $_SESSION['error_message'] = "Error binding parameters: " . $stmt_insert->error;
                $conn->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = $id_buku");
                header("Location: detail-buku.php?id=".$id_buku);
                exit();
            }
            
            if ($stmt_insert->execute()) {
                $_SESSION['success_message'] = "Buku berhasil dipinjam!";
            } else {
                $_SESSION['error_message'] = "Gagal meminjam buku: " . $stmt_insert->error;
                // Kembalikan stok jika gagal
                $conn->query("UPDATE buku SET stok = stok + 1 WHERE id_buku = $id_buku");
            }
            header("Location: detail-buku.php?id=".$id_buku);
            exit();
        } else {
            $_SESSION['error_message'] = "Data staff/guru tidak ditemukan";
            header("Location: detail-buku.php?id=".$id_buku);
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Anda harus login sebagai staff/guru untuk meminjam buku";
        header("Location: detail-buku.php?id=".$id_buku);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - Perpustakaan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        }

        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
        }

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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(10px);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: var(--gradient-primary);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
        }

        .back-button:hover {
            background: var(--gradient-primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 57, 104, 0.4);
            color: white;
        }

        .back-button-text {
            display: inline;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border-left: 4px solid #2e7d32;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
            border-left: 4px solid #c62828;
        }

        .book-detail {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(10px);
        }

        .book-header {
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }

        .book-cover {
            width: 300px;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .default-cover {
            width: 300px;
            height: 400px;
            background: var(--gradient-primary);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
            font-weight: bold;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .book-info {
            flex: 1;
        }

        .book-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .book-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }

        .book-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: var(--gradient-primary);
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .book-meta i {
            font-size: 16px;
        }

        .book-description {
            background: rgba(244, 196, 48, 0.1);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 16px;
            color: var(--text-primary);
            border-left: 4px solid var(--accent-color);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-pinjam {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(44, 57, 104, 0.3);
        }

        .btn-pinjam:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44, 57, 104, 0.4);
            background: var(--gradient-accent);
            color: var(--primary-color);
        }

        .btn-pinjam:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        .similar-books {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            box-shadow: var(--card-shadow);
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
        }

        .similar-book {
            text-decoration: none;
            color: inherit;
            background: white;
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
        }

        .similar-book:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(44, 57, 104, 0.2);
            border-color: var(--primary-color);
            color: inherit;
        }

        .similar-book-cover {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .similar-default-cover {
            width: 100%;
            height: 200px;
            background: var(--gradient-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            font-weight: bold;
            color: white;
            margin-bottom: 15px;
        }

        .similar-book-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .similar-book-author {
            font-size: 14px;
            color: var(--secondary-color);
            font-weight: 500;
        }

        /* Responsive Design - Tablet */
        @media (max-width: 1024px) {
            .container {
                padding: 15px;
            }

            .book-header {
                gap: 30px;
            }

            .book-cover, .default-cover {
                width: 250px;
                height: 330px;
            }

            .book-title {
                font-size: 28px;
            }

            .book-detail {
                padding: 30px;
            }

            .similar-books {
                padding: 30px;
            }

            .books-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 20px;
            }
        }

        /* Responsive Design - Mobile Large */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header {
                padding: 20px;
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 24px;
            }

            .back-button {
                font-size: 14px;
                padding: 10px 16px;
            }

            .back-button-text {
                display: none;
            }

            .book-detail {
                padding: 25px;
                margin-bottom: 20px;
            }

            .book-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 25px;
            }

            .book-cover, .default-cover {
                width: 220px;
                height: 290px;
            }

            .default-cover {
                font-size: 100px;
            }

            .book-title {
                font-size: 24px;
                text-align: center;
            }

            .book-meta {
                justify-content: center;
                gap: 10px;
            }

            .book-meta span {
                font-size: 12px;
                padding: 6px 12px;
            }

            .book-description {
                padding: 20px;
                font-size: 14px;
            }

            .btn-pinjam {
                font-size: 15px;
                padding: 12px 25px;
                width: 100%;
                justify-content: center;
            }

            .similar-books {
                padding: 25px;
            }

            .section-title {
                font-size: 20px;
                margin-bottom: 20px;
            }

            .books-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 15px;
            }

            .similar-book {
                padding: 15px;
            }

            .similar-book-cover {
                height: 160px;
            }

            .similar-default-cover {
                height: 160px;
                font-size: 50px;
            }

            .similar-book-title {
                font-size: 14px;
            }

            .similar-book-author {
                font-size: 12px;
            }
        }

        /* Responsive Design - Mobile Small */
        @media (max-width: 480px) {
            .container {
                padding: 8px;
            }

            .header {
                padding: 15px;
            }

            .page-title {
                font-size: 20px;
            }

            .back-button {
                font-size: 13px;
                padding: 8px 14px;
            }

            .book-detail {
                padding: 20px;
            }

            .book-cover, .default-cover {
                width: 180px;
                height: 240px;
            }

            .default-cover {
                font-size: 80px;
            }

            .book-title {
                font-size: 20px;
            }

            .book-meta {
                gap: 8px;
            }

            .book-meta span {
                font-size: 11px;
                padding: 5px 10px;
            }

            .book-description {
                padding: 15px;
                font-size: 13px;
            }

            .btn-pinjam {
                font-size: 14px;
                padding: 10px 20px;
            }

            .similar-books {
                padding: 20px;
            }

            .section-title {
                font-size: 18px;
                margin-bottom: 15px;
            }

            .books-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .similar-book {
                padding: 12px;
            }

            .similar-book-cover {
                height: 140px;
            }

            .similar-default-cover {
                height: 140px;
                font-size: 40px;
            }

            .similar-book-title {
                font-size: 13px;
            }

            .similar-book-author {
                font-size: 11px;
            }
        }

        /* Responsive Design - Mobile Extra Small */
        @media (max-width: 375px) {
            .container {
                padding: 5px;
            }

            .header {
                padding: 12px;
            }

            .page-title {
                font-size: 18px;
            }

            .back-button {
                font-size: 12px;
                padding: 6px 12px;
            }

            .book-detail {
                padding: 15px;
            }

            .book-cover, .default-cover {
                width: 160px;
                height: 210px;
            }

            .default-cover {
                font-size: 70px;
            }

            .book-title {
                font-size: 18px;
            }

            .book-meta span {
                font-size: 10px;
                padding: 4px 8px;
            }

            .book-description {
                padding: 12px;
                font-size: 12px;
            }

            .btn-pinjam {
                font-size: 13px;
                padding: 8px 16px;
            }

            .similar-books {
                padding: 15px;
            }

            .section-title {
                font-size: 16px;
            }

            .books-grid {
                gap: 10px;
            }

            .similar-book {
                padding: 10px;
            }

            .similar-book-cover {
                height: 120px;
            }

            .similar-default-cover {
                height: 120px;
                font-size: 35px;
            }

            .similar-book-title {
                font-size: 12px;
            }

            .similar-book-author {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="katalog-pinjam.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span class="back-button-text">Kembali ke Katalog</span>
            </a>
            <h1 class="page-title">Detail Buku</h1>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="book-detail">
            <div class="book-header">
                <?php if (!empty($buku['cover'])): ?>
                    <img src="../assets/images/covers/<?php echo htmlspecialchars($buku['cover']); ?>" 
                        alt="<?php echo htmlspecialchars($buku['judul_buku']); ?>" 
                        class="book-cover"
                        onerror="this.onerror=null; this.src='../assets/images/default-cover.png'">
                <?php else: ?>
                    <div class="default-cover">
                        <?php echo substr($buku['judul_buku'], 0, 1); ?>
                    </div>
                <?php endif; ?>
                
                <div class="book-info">
                    <h1 class="book-title"><?php echo $buku['judul_buku']; ?></h1>
                    
                    <div class="book-meta">
                        <span><i class="fas fa-user-pen"></i> <?php echo $buku['penulis']; ?></span>
                        <span><i class="fas fa-calendar"></i> <?php echo $buku['tahun_terbit']; ?></span>
                        <span><i class="fas fa-tag"></i> <?php echo $buku['genre']; ?></span>
                        <span><i class="fas fa-copy"></i> Tersedia: <?php echo $buku['stok']; ?> buku</span>
                    </div>
                    
                    <div class="book-description">
                    <?php 
                        // Tampilkan deskripsi dari database jika ada
                        if (!empty($buku['deskripsi'])) {
                            echo nl2br(htmlspecialchars($buku['deskripsi']));
                        } else {
                            // Fallback jika deskripsi kosong
                            echo "<p>Deskripsi buku belum tersedia.</p>";
                        }
                        ?>
                    </div>
                    
                    <div class="action-buttons">
                        <form method="post">
                            <button type="submit" name="pinjam" class="btn-pinjam" <?php echo ($buku['stok'] <= 0) ? 'disabled' : ''; ?>>
                                <i class="fas fa-book"></i> Pinjam Buku
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="similar-books">
            <h2 class="section-title">Buku Serupa</h2>
            
            <?php if (count($buku_serupa) > 0): ?>
                <div class="books-grid">
                    <?php foreach ($buku_serupa as $buku): ?>
                        <a href="detail-buku.php?id=<?php echo $buku['id_buku']; ?>" class="similar-book">
                            <?php if (!empty($buku['cover'])): ?>
                                <img src="../assets/images/covers/<?php echo htmlspecialchars($buku['cover']); ?>" 
                                    alt="<?php echo htmlspecialchars($buku['judul_buku']); ?>" 
                                    class="similar-book-cover"
                                    onerror="this.onerror=null; this.src='../assets/images/default-cover.png'">
                            <?php else: ?>
                                <div class="similar-default-cover">
                                    <?php echo substr($buku['judul_buku'], 0, 1); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="similar-book-title"><?php echo $buku['judul_buku']; ?></h3>
                            <p class="similar-book-author"><?php echo $buku['penulis']; ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Tidak ada buku serupa yang ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>