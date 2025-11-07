<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include file koneksi
include "../koneksi.php";

// Pastikan folder upload ada dan bisa ditulisi
$upload_dir = __DIR__ . '/../assets/images/covers/';
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        die("Gagal membuat folder upload. Pastikan path benar: " . $upload_dir);
    }
}

// Verifikasi folder bisa ditulisi
if (!is_writable($upload_dir)) {
    die("Folder upload tidak bisa ditulisi. Set permission folder ke 755 atau 777.");
}

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input wajib
    if (empty($_POST['judul_buku']) || empty($_POST['penulis'])) {
        die("Judul buku dan penulis harus diisi");
    }

    // Ambil data dari form
    $judul = trim($_POST['judul_buku']);
    $penulis = trim($_POST['penulis']);
    $tahun = isset($_POST['tahun_terbit']) ? (int)$_POST['tahun_terbit'] : 0;
    $kategori = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
    $stok = isset($_POST['stok_buku']) ? (int)$_POST['stok_buku'] : 0;
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    $tanggal = date('Y-m-d');
    $cover_filename = null;

    // Handle upload gambar
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        // Validasi jenis file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $image_type = finfo_file($finfo, $_FILES['cover']['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($image_type, $allowed_types)) {
            die("Jenis file tidak diperbolehkan. Harap upload file gambar (JPG/PNG/GIF).");
        }
        
        // Validasi ukuran file (max 2MB)
        if ($_FILES['cover']['size'] > 2097152) {
            die("Ukuran file terlalu besar. Maksimal 2MB.");
        }
        
        // Generate nama file unik
        $file_ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
        $cover_filename = uniqid('cover_', true) . '.' . strtolower($file_ext);
        $target_file = $upload_dir . $cover_filename;
        
        // Pindahkan file ke folder upload
        if (!move_uploaded_file($_FILES['cover']['tmp_name'], $target_file)) {
            die("Gagal mengupload gambar. Error: " . $_FILES['cover']['error']);
        }
    }

    // Simpan ke database
    try {
        $query = "INSERT INTO buku 
                    (judul_buku, penulis, tahun_terbit, genre, stok, tanggal_ditambahkan, cover, deskripsi) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param(
            "ssisisss", 
            $judul, 
            $penulis, 
            $tahun, 
            $kategori, 
            $stok, 
            $tanggal, 
            $cover_filename,
            $deskripsi
        );

        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . htmlspecialchars($stmt->error));
        }

        // Jika berhasil, redirect ke halaman daftar buku
        $stmt->close();
        $conn->close();
        header("Location: daftar-buku.php");
        exit;

    } catch (Exception $e) {
        // Jika gagal, hapus file yang sudah diupload (jika ada)
        if (isset($target_file) && file_exists($target_file)) {
            unlink($target_file);
        }
        
        // Tampilkan pesan error
        die("Error: " . $e->getMessage() . "<br>Query: " . $query);
    }
} else {
    // Jika bukan request POST, redirect
    header("Location: tambah-buku.php");
    exit;
}
?>