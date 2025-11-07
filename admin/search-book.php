<?php
include "../koneksi.php";

$term = $_GET['term'] ?? '';

// Cari buku berdasarkan judul atau penulis
$query = "SELECT id_buku, judul_buku AS judul, penulis FROM buku WHERE judul_buku LIKE ? OR penulis LIKE ?";
$stmt = $conn->prepare($query);
$searchTerm = "%$term%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);

$stmt->close();
$conn->close();
?>