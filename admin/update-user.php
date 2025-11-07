<?php
include "../koneksi.php";

header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");

$data = json_decode(file_get_contents('php://input'), true);

// Validasi data
$requiredFields = ['role', 'old_nuptk', 'nama', 'nuptk'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap!']);
        exit;
    }
}

$role = $data['role'];
$oldNuptk = $data['old_nuptk'];
$nama = $data['nama'];
$nuptk = $data['nuptk'];
$password = $data['password'] ?? null;
$no_telp = $data['no_telp'] ?? null;
$kelas = $data['kelas'] ?? null;
$guruRole = $data['guruRole'] ?? null;

try {
    // Cek apakah data baru sudah digunakan oleh orang lain
    if ($oldNuptk !== $nuptk) {
        $table = ($role === 'siswa') ? 'siswa' : 'staff_guru';
        $field = ($role === 'siswa') ? 'nisn' : 'nig';
        
        $check = $conn->prepare("SELECT $field FROM $table WHERE $field = ?");
        $check->bind_param("s", $nuptk);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            throw new Exception(($role === 'siswa' ? 'NISN' : 'NIG') . " sudah digunakan!");
        }
    }

    // Siapkan query update (password plain text)
    if ($role === 'siswa') {
        if ($password) {
            $stmt = $conn->prepare("UPDATE siswa SET nama=?, nisn=?, no_telp=?, kelas=?, password=? WHERE nisn=?");
            $stmt->bind_param("ssssss", $nama, $nuptk, $no_telp, $kelas, $password, $oldNuptk);
        } else {
            $stmt = $conn->prepare("UPDATE siswa SET nama=?, nisn=?, no_telp=?, kelas=? WHERE nisn=?");
            $stmt->bind_param("sssss", $nama, $nuptk, $no_telp, $kelas, $oldNuptk);
        }
    } else {
        if ($password) {
            $stmt = $conn->prepare("UPDATE staff_guru SET nama=?, nig=?, role=?, password=? WHERE nig=?");
            $stmt->bind_param("sssss", $nama, $nuptk, $guruRole, $password, $oldNuptk);
        } else {
            $stmt = $conn->prepare("UPDATE staff_guru SET nama=?, nig=?, role=? WHERE nig=?");
            $stmt->bind_param("ssss", $nama, $nuptk, $guruRole, $oldNuptk);
        }
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
    } else {
        throw new Exception("Gagal memperbarui data: " . $stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($check)) $check->close();
    $conn->close();
}
?>