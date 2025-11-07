<?php
include "../koneksi.php";

// Ambil data dari form
$data = json_decode(file_get_contents('php://input'), true);

// Pastikan data tidak kosong
if (empty($data)) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak boleh kosong!']);
    exit;
}

$nama = $data['nama'] ?? '';
$nuptk = $data['nuptk'] ?? '';
$role = $data['role'] ?? '';
$password = $data['password'] ?? '';
$guruRole = $data['guruRole'] ?? 'staff';
$no_telp = $data['no_telp'] ?? '';
$kelas = $data['kelas'] ?? '-';

// Validasi input
if (empty($nama) || empty($nuptk) || empty($role) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi!']);
    exit;
}

// Simpan password sebagai plain text (tanpa hashing)
$plainPassword = $password;

// Validasi NISN/NIG berdasarkan role
if ($role === 'siswa') {
    $check = $conn->prepare("SELECT * FROM siswa WHERE nisn = ?");
    $check->bind_param("s", $nuptk);
} else if ($role === 'staff-guru') {
    $check = $conn->prepare("SELECT * FROM staff_guru WHERE nig = ?");
    $check->bind_param("s", $nuptk);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Role tidak valid!']);
    exit;
}

$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => ($role === 'siswa' ? 'NISN' : 'NIG') . ' sudah terdaftar!']);
    exit;
}

// Siapkan query sesuai role
if ($role === 'siswa') {
    $stmt = $conn->prepare("INSERT INTO siswa (nama, nisn, no_telp, kelas, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama, $nuptk, $no_telp, $kelas, $plainPassword);
} else if ($role === 'staff-guru') {
    // Validasi tipe staff/guru
    if (!in_array($guruRole, ['staff', 'guru'])) {
        echo json_encode(['status' => 'error', 'message' => 'Tipe Staff/Guru tidak valid!']);
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO staff_guru (nama, nig, role, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $nuptk, $guruRole, $plainPassword);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Role tidak dikenali!']);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Akun berhasil dibuat!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal membuat akun: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>