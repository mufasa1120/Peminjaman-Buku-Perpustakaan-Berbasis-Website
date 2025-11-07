<?php
session_start();
include "koneksi.php";

$id = intval($_GET['id'] ?? 0);

// Cek apakah file cover ada di direktori
$query = "SELECT cover FROM buku WHERE id_buku = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    $coverPath = '../assets/images/covers/' . $book['cover'];
    
    if (!empty($book['cover']) && file_exists($coverPath)) {
        $imageInfo = getimagesize($coverPath);
        header("Content-Type: " . $imageInfo['mime']);
        readfile($coverPath);
        exit;
    }
}

// Default cover fallback
$defaultPath = 'assets/images/default-cover.png';
if (file_exists($defaultPath)) {
    header("Content-Type: image/png");
    readfile($defaultPath);
} else {
    // Create blank image
    header("Content-Type: image/png");
    $im = imagecreate(200, 300);
    $bg = imagecolorallocate($im, 221, 221, 221);
    $textcolor = imagecolorallocate($im, 0, 0, 0);
    imagestring($im, 5, 50, 150, 'Cover Not Found', $textcolor);
    imagepng($im);
    imagedestroy($im);
}
exit;
?>