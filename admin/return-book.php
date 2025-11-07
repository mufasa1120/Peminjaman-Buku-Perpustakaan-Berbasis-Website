<?php
session_start();
include "../koneksi.php";

// Ambil semua parameter yang diperlukan
$id_peminjaman = $_GET['id'] ?? ($_GET['id_rekap_tamu'] ?? ($_GET['id_rekap_staff_guru'] ?? null));
$id_buku = $_GET['id_buku'] ?? null;
$role = $_GET['role'] ?? '';
$nama = $_GET['nama'] ?? '';

// Validasi parameter
if (!$id_buku || !$role) {
    die("Parameter tidak lengkap");
}

// Tentukan tabel dan kolom ID berdasarkan role
switch ($role) {
    case 'siswa':
        $table = 'rekap_peminjaman_siswa';
        $id_column = 'id_rekap_siswa';
        $status_column = 'status_pengembalian';
        $id_peminjaman = $_GET['id'] ?? null;
        break;
    case 'staff-guru':
        $table = 'rekap_peminjaman_staff_guru';
        $id_column = 'id_rekap_staff_guru';
        $status_column = 'status_pengembalian';
        $id_peminjaman = $_GET['id_rekap_staff_guru'] ?? null;
        break;
    case 'tamu':
        $table = 'rekap_peminjaman_tamu';
        $id_column = 'id_rekap_tamu';
        $status_column = 'status_pengembalian';
        $id_peminjaman = $_GET['id'] ?? null;
        break;
    default:
        die("Role tidak valid");
}

// Mulai transaksi
$conn->begin_transaction();

try {
    // 1. Update status pengembalian
    if ($id_peminjaman) {
        $sql_update = "UPDATE $table SET $status_column = '1' WHERE $id_column = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $id_peminjaman);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Gagal mengupdate status pengembalian: " . $conn->error);
        }
    }

    // 2. Update stok buku
    $sql_buku = "UPDATE buku SET stok = stok + 1 WHERE id_buku = ?";
    $stmt_buku = $conn->prepare($sql_buku);
    $stmt_buku->bind_param("i", $id_buku);
    
    if (!$stmt_buku->execute()) {
        throw new Exception("Gagal mengupdate stok buku: " . $conn->error);
    }

    // Commit transaksi jika semua berhasil
    $conn->commit();
    
    // Set session message
    $_SESSION['success_message'] = "Buku berhasil dikembalikan oleh " . htmlspecialchars($nama);
    
    // Redirect kembali
    header("Location: rekapitulasi.php?role=$role");
    exit();

} catch (Exception $e) {
    // Rollback jika terjadi error
    $conn->rollback();
    
    // Set session error message
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
    
    // Redirect kembali
    header("Location: rekapitulasi.php?role=$role");
    exit();
}
?>