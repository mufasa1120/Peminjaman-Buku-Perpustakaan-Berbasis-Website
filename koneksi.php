<?php
$servername = "localhost";
$username = "root"; // Ganti sesuai pengaturanmu
$password = "";    // Ganti sesuai pengaturanmu
$dbname = "perpustakaanbaru";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>