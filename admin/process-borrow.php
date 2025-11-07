<?php
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $notelpon = $_POST['notelpon'];
    $keperluan = $_POST['keperluan'];
    $id_buku = intval($_POST['id_buku']);
    
    // Ambil tanggal dari form dan validasi format
    $tgl_pinjam = $_POST['tanggal_daftar'] ?? date('Y-m-d'); // Jika kosong, gunakan tanggal hari ini
    if (!DateTime::createFromFormat('Y-m-d', $tgl_pinjam)) {
        die('Tanggal tidak valid!');
    }

    // Hapus query INSERT kedua yang tidak perlu
    // Hanya gunakan satu query berikut ini:
    $stmt = $conn->prepare("INSERT INTO rekap_peminjaman_tamu (nama_peminjam, notelpon, keperluan, id_buku, tgl_pinjam) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sssss", $nama, $notelpon, $keperluan, $id_buku, $tgl_pinjam);
    $stmt->execute();

    // Optional: Kurangi stok buku
    $stmt2 = $conn->prepare("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?");
    $stmt2->bind_param("i", $id_buku);
    $stmt2->execute();

    header("Location: dashboard-admin.php?success=1");
    exit;
}

$conn->close();
?>