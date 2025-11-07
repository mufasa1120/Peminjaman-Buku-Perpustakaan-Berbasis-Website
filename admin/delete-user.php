<?php
include "../koneksi.php";
$data = json_decode(file_get_contents('php://input'), true);

$role = $data['role'] ?? '';
$id = $data['id'] ?? '';  // Ubah dari nuptk ke id

if (empty($role) || empty($id)) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap!']);
    exit;
}

try {
    if ($role === 'siswa') {
        $stmt = $conn->prepare("DELETE FROM siswa WHERE nisn = ?");
        $stmt->bind_param("s", $id);  // Gunakan id sebagai parameter
    } elseif ($role === 'staff-guru') {
        $stmt = $conn->prepare("DELETE FROM staff_guru WHERE nig = ?");
        $stmt->bind_param("s", $id);  // Gunakan id sebagai parameter
    } else {
        throw new Exception("Role tidak valid!");
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Akun berhasil dihapus']);
    } else {
        throw new Exception("Gagal menghapus akun: " . $stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conn->close();
}
?>