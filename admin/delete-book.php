<?php
// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../koneksi.php";

// Verifikasi koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk redirect dengan pesan
function redirectWithMessage($message, $type = 'error') {
    header("Location: daftar-buku.php?{$type}=" . urlencode($message));
    exit;
}

// Cek apakah ada parameter ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirectWithMessage('ID buku tidak valid', 'error');
}

$id_buku = intval($_GET['id']);

// Cek apakah buku ada dalam database
$checkSql = "SELECT id_buku, judul_buku, cover FROM buku WHERE id_buku = $id_buku";
$result = $conn->query($checkSql);

if (!$result) {
    redirectWithMessage('Error query: ' . $conn->error, 'error');
}

if ($result->num_rows === 0) {
    redirectWithMessage('Buku tidak ditemukan', 'error');
}

$buku = $result->fetch_assoc();

// Cek apakah ada tabel peminjaman
$tableCheckSql = "SHOW TABLES LIKE 'peminjaman'";
$tableCheckResult = $conn->query($tableCheckSql);

if ($tableCheckResult && $tableCheckResult->num_rows > 0) {
    // Tabel peminjaman ada, cek apakah buku sedang dipinjam
    $pinjamSql = "SELECT COUNT(*) as jumlah_pinjam FROM peminjaman WHERE id_buku = $id_buku AND status_pinjam = 'dipinjam'";
    $pinjamResult = $conn->query($pinjamSql);
    
    if ($pinjamResult) {
        $pinjamData = $pinjamResult->fetch_assoc();
        if ($pinjamData['jumlah_pinjam'] > 0) {
            redirectWithMessage('Buku tidak dapat dihapus karena sedang dipinjam', 'error');
        }
    }
}

// Mulai transaction
$conn->begin_transaction();

try {
    // Hapus record terkait di tabel peminjaman jika ada
    if ($tableCheckResult && $tableCheckResult->num_rows > 0) {
        $deletePinjamSql = "DELETE FROM peminjaman WHERE id_buku = $id_buku";
        $conn->query($deletePinjamSql);
    }
    
    // Hapus buku dari database
    $deleteBukuSql = "DELETE FROM buku WHERE id_buku = $id_buku";
    $deleteResult = $conn->query($deleteBukuSql);
    
    if (!$deleteResult) {
        throw new Exception("Error menghapus buku: " . $conn->error);
    }
    
    if ($conn->affected_rows > 0) {
        // Hapus file cover jika ada
        if (!empty($buku['cover'])) {
            $coverPath = "../assets/images/covers/" . $buku['cover'];
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }
        
        // Commit transaction
        $conn->commit();
        redirectWithMessage('Buku "' . $buku['judul_buku'] . '" berhasil dihapus', 'success');
    } else {
        throw new Exception("Tidak ada buku yang dihapus");
    }
    
} catch (Exception $e) {
    // Rollback transaction jika ada error
    $conn->rollback();
    redirectWithMessage('Gagal menghapus buku: ' . $e->getMessage(), 'error');
}

// Tutup koneksi
$conn->close();
?>