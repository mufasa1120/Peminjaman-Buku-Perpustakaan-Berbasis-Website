<?php
session_start();

// Koneksi ke database
include "koneksi.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_user = $_POST['username'];
    $input_pass = $_POST['password'];

    // Cek Admin dulu
    if ($input_user === 'admin' && $input_pass === 'kihajardewantoro123') {
        $_SESSION['user_type'] = 'admin';
        $_SESSION['id_admin'] = 1; // Atau ambil dari database jika perlu
        echo json_encode([
            'success' => true,
            'redirect' => 'admin/dashboard-admin.php'
        ]);
        exit;
    }

    // Cek Siswa (password plain text)
    $stmt = $conn->prepare("SELECT * FROM siswa WHERE nama = ? OR nisn = ?");
    $stmt->bind_param("ss", $input_user, $input_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($input_pass === $row['password']) {
            $_SESSION['user_type'] = 'siswa';
            $_SESSION['id_user'] = $row['id_siswa']; // Konsisten dengan id_user
            $_SESSION['id_siswa'] = $row['id_siswa'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['nisn'] = $row['nisn'];
            echo json_encode([
                'success' => true,
                'redirect' => 'user/katalog-pinjam.php'
            ]);
            exit;
        }
    }

    // Cek Staff/Guru (password plain text)
    $stmt = $conn->prepare("SELECT * FROM staff_guru WHERE nama = ? OR nig = ?");
    $stmt->bind_param("ss", $input_user, $input_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($input_pass === $row['password']) {
            // Set session lengkap untuk staff/guru
            $_SESSION['user_type'] = $row['role']; // 'staff' atau 'guru'
            $_SESSION['id_user'] = $row['id_guru']; // Konsisten dengan id_user
            $_SESSION['id_guru'] = $row['id_guru'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['nig'] = $row['nig'];
            $_SESSION['role'] = $row['role']; // Tambahan untuk memudahkan
            
            echo json_encode([
                'success' => true,
                'redirect' => 'staff-guru/katalog-pinjam.php'
            ]);
            exit;
        }
    }

    // Jika semua gagal
    echo json_encode([
        'success' => false,
        'message' => 'Username atau Password salah!'
    ]);
}
?>