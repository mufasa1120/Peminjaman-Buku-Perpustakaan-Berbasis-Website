<?php
include "../koneksi.php";

$role = $_GET['role'];

if ($role === 'siswa') {
    $query = "SELECT id_siswa AS id, nama, nisn, no_telp, kelas, password FROM siswa";
} else {
    $query = "SELECT id_guru AS id, nama, nig, role, password FROM staff_guru";
}

$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>