<?php
session_start();

// Koneksi ke database
include "../koneksi.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID buku dari parameter GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil cover dan image_type
$stmt = $conn->prepare("SELECT cover, image_type FROM buku WHERE id_buku = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($cover, $image_type);
    $stmt->fetch();
    
    // Kirim header sesuai tipe gambar
    header("Content-Type: " . $image_type);
    echo $cover;
} else {
    // Jika tidak ada gambar, kirim gambar default
    header("Content-Type: image/png");
    readfile("../assets/images/default-cover.png");
}

$stmt->close();
$conn->close();
exit;
?>