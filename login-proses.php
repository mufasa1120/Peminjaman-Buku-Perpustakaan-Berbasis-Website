<?php 
session_start(); 

// Koneksi ke database
include "koneksi.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk mendapatkan semua buku
function getBooks($conn, $search = '', $genre = '', $penulis = '', $tahun = '', $sort = '') {
    $query = "SELECT * FROM buku WHERE 1=1";
    
    // Filter pencarian
    if (!empty($search)) {
        $query .= " AND (judul_buku LIKE '%$search%' 
                   OR penulis LIKE '%$search%' 
                   OR tahun_terbit LIKE '%$search%'
                   OR genre LIKE '%$search%')";
    }
    
    // Filter genre
    if (!empty($genre)) {
        $query .= " AND genre = '$genre'";
    }
    
    // Filter penulis
    if (!empty($penulis)) {
        $query .= " AND penulis = '$penulis'";
    }
    
    // Filter tahun
    if (!empty($tahun)) {
        $query .= " AND tahun_terbit = '$tahun'";
    }
    
    // Pengurutan
    if (!empty($sort)) {
        switch($sort) {
            case 'judul_asc':
                $query .= " ORDER BY judul_buku ASC";
                break;
            case 'judul_desc':
                $query .= " ORDER BY judul_buku DESC";
                break;
            case 'tanggal_asc':
                $query .= " ORDER BY tanggal_ditambahkan ASC";
                break;
            case 'tanggal_desc':
                $query .= " ORDER BY tanggal_ditambahkan DESC";
                break;
        }
    } else {
        $query .= " ORDER BY tanggal_ditambahkan DESC";
    }
    
    $result = $conn->query($query);
    $books = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    
    return $books;
}

// Mendapatkan nilai filter dari URL
$search = isset($_GET['search']) ? $_GET['search'] : '';
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';
$penulis_filter = isset($_GET['penulis']) ? $_GET['penulis'] : '';
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$sort_filter = isset($_GET['sort']) ? $_GET['sort'] : '';

// Mendapatkan buku berdasarkan filter
$books = getBooks($conn, $search, $genre_filter, $penulis_filter, $tahun_filter, $sort_filter);
$total_buku = count($books);

// Mendapatkan pilihan unik untuk filter
$genres = [];
$penuliss = [];
$tahuns = [];

$result = $conn->query("SELECT DISTINCT genre FROM buku");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $genres[] = $row['genre'];
    }
}

$result = $conn->query("SELECT DISTINCT penulis FROM buku");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $penuliss[] = $row['penulis'];
    }
}

$result = $conn->query("SELECT DISTINCT tahun_terbit FROM buku ORDER BY tahun_terbit DESC");
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tahuns[] = $row['tahun_terbit'];
    }
}

// Debugging - cek path cover
function getCoverPath($cover_filename) {
    // Path absolut untuk pengecekan file
    $base_path = dirname(__FILE__) . '/assets/images/covers/';
    
    // Path relatif untuk ditampilkan di HTML
    $web_path = 'assets/images/covers/';
    
    // Jika cover_filename tidak kosong dan file exist
    if (!empty($cover_filename)) {
        // Cek ekstensi file
        $possible_extensions = ['', '.jpg', '.jpeg', '.png', '.gif'];
        foreach ($possible_extensions as $ext) {
            if (file_exists($base_path . $cover_filename . $ext)) {
                return $web_path . $cover_filename . $ext;
            }
        }
    }
    
    // Jika tidak ada cover, return path default
    return 'assets/images/default-cover.png';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku - Perpustakaan SMA KI HAJAR DEWANTORO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3968;
            --secondary-color: #3d4b7a;
            --accent-color: #f4c430;
            --light-bg: #f8f9fc;
            --card-shadow: 0 8px 32px rgba(44, 57, 104, 0.12);
            --text-primary: #2c3968;
            --text-secondary: #6c757d;
            --gradient-primary: linear-gradient(135deg, #2c3968 0%, #3d4b7a 100%);
            --gradient-accent: linear-gradient(135deg, #f4c430 0%, #f39c12 100%);
            --navbar-height: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
            padding-top: var(--navbar-height);
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(244, 196, 48, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(44, 57, 104, 0.05) 0%, transparent 50%),
                linear-gradient(135deg, #f8f9fc 0%, #e8ecf4 100%);
            z-index: -1;
        }

        .modern-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(44, 57, 104, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            height: var(--navbar-height);
            display: flex;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            color: var(--primary-color) !important;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo-navbar {
            width: 45px;
            height: 45px;
            margin-right: 12px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.2);
            background: var(--gradient-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 600;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            white-space: nowrap;
            overflow: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 15px;
        }

        .content-header {
            background: var(--gradient-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .content-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .content-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 2;
        }

        .search-section {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .search-container {
            display: flex;
            margin-bottom: 1.5rem;
            gap: 10px;
        }

        .search-input {
            position: relative;
            flex: 1;
        }

        .search-input i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            z-index: 2;
        }

        .search-input input {
            padding: 12px 16px 12px 45px;
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }

        .search-input input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 57, 104, 0.25);
            outline: none;
        }

        .search-button {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0 30px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .search-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 57, 104, 0.4);
        }

        .filters-section {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            font-size: 14px;
            margin-bottom: 10px;
            color: var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-select {
            padding: 12px 16px;
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
        }

        .filter-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 57, 104, 0.25);
            outline: none;
        }

        .sort-section {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: 15px;
            gap: 15px;
        }

        .sort-label {
            font-size: 14px;
            color: var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sort-select {
            padding: 12px 16px;
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 200px;
            background: white;
        }

        .sort-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 57, 104, 0.25);
            outline: none;
        }

        .total-books {
            background: var(--gradient-primary);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
        }

        .books-section {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .book-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .book-cover {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        .default-cover {
            width: 100%;
            height: 240px;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
        }

        .default-cover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
        }

        .book-info {
            padding: 24px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .book-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-primary);
            line-height: 1.3;
            /* Handling long titles */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 2.6em; /* 2 lines height */
        }

        .book-author {
            color: var(--primary-color);
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .book-year {
            color: var(--text-secondary);
            font-size: 13px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .book-genre {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            color: var(--primary-color);
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            align-self: flex-start;
            margin-top: auto;
            border: 1px solid rgba(102, 126, 234, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .book-stock {
            display: flex;
            align-items: center;
            margin-top: 15px;
            color: #38a169;
            font-size: 13px;
            font-weight: 600;
            gap: 6px;
            padding: 8px 12px;
            background: rgba(56, 161, 105, 0.1);
            border-radius: 20px;
            border: 1px solid rgba(56, 161, 105, 0.2);
        }

        .no-books {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: var(--text-primary);
            font-size: 18px;
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .no-books i {
            color: var(--accent-color);
            margin-bottom: 20px;
            font-size: 48px;
        }

        .no-books h3 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .book-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .login-button {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(44, 57, 104, 0.4);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 1199px) {
            .container {
                max-width: 960px;
            }
            
            .content-title {
                font-size: 1.8rem;
            }
            
            .books-section {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            }
        }

        /* Tablet Layout (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .container {
                max-width: 100%;
                padding: 15px;
            }
            
            /* Align filter width dengan books section */
            .filters-section {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
            
            .books-section {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }
            
            .book-card {
                min-height: 350px;
            }
            
            .book-cover, .default-cover {
                height: 160px;
            }
            
            .book-info {
                padding: 15px;
            }
            
            .book-title {
                font-size: 16px;
                margin-bottom: 8px;
                min-height: 2.4em;
            }
            
            .book-author, .book-year {
                font-size: 12px;
            }
            
            .book-genre {
                font-size: 10px;
                padding: 6px 12px;
            }
            
            .sort-section {
                flex-direction: row;
                justify-content: space-between;
            }
            
            .sort-select {
                min-width: 180px;
            }
        }

        /* Mobile Layout (481px - 767px) */
        @media (min-width: 481px) and (max-width: 767px) {
            :root {
                --navbar-height: 70px;
            }
            
            .modern-navbar {
                height: var(--navbar-height);
                padding: 0.75rem 0;
            }
            
            .container {
                max-width: 100%;
                padding: 12px;
            }
            
            .content-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .content-title {
                font-size: 1.5rem;
            }
            
            .search-section {
                padding: 1.25rem;
            }
            
            .search-container {
                flex-direction: column;
                gap: 10px;
            }
            
            .search-button {
                padding: 12px 20px;
                width: 100%;
            }
            
            /* Fix: Align filter with books section - 2 columns each */
            .filters-section {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            
            .sort-section {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            
            .sort-select {
                min-width: 100%;
            }
            
            /* 2 kolom untuk mobile landscape */
            .books-section {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            
            .book-card {
                min-height: 320px;
            }
            
            .book-cover, .default-cover {
                height: 140px;
            }
            
            .book-info {
                padding: 12px;
            }
            
            .book-title {
                font-size: 14px;
                margin-bottom: 6px;
                line-height: 1.2;
                min-height: 2.4em;
            }
            
            .book-author, .book-year {
                font-size: 11px;
                margin-bottom: 4px;
            }
            
            .book-genre {
                font-size: 9px;
                padding: 4px 8px;
                margin-top: 8px;
            }
            
            .book-stock {
                font-size: 10px;
                padding: 4px 8px;
                margin-top: 8px;
            }
            
            .brand-text {
                font-size: 0.9rem;
            }
            
            .logo-navbar {
                width: 40px;
                height: 40px;
            }
        }

        /* Small Mobile Layout (320px - 480px) */
        @media (max-width: 480px) {
            :root {
                --navbar-height: 65px;
            }
            
            .modern-navbar {
                height: var(--navbar-height);
                padding: 0.5rem 0;
            }
            
            .container {
                max-width: 100%;
                padding: 10px;
            }
            
            .content-header {
                padding: 1.25rem;
                margin-bottom: 1rem;
            }
            
            .content-title {
                font-size: 1.3rem;
            }
            
            .search-section {
                padding: 1rem;
            }
            
            .search-container {
                flex-direction: column;
                gap: 8px;
            }
            
            .search-input input {
                padding: 10px 14px 10px 40px;
                font-size: 14px;
            }
            
            .search-button {
                padding: 10px 16px;
                font-size: 14px;
            }
            
            /* Fix: Single column for filters on small mobile */
            .filters-section {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .filter-select {
                padding: 10px 14px;
                font-size: 14px;
            }
            
            .sort-section {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            
            .sort-select {
                min-width: 100%;
                padding: 10px 14px;
                font-size: 14px;
            }
            
            /* Single column untuk mobile kecil */
            .books-section {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .book-card {
                min-height: 280px;
                border-radius: 15px;
                max-width: 100%;
            }
            
            .book-cover, .default-cover {
                height: 180px;
            }
            
            .book-info {
                padding: 15px;
            }
            
            .book-title {
                font-size: 16px;
                margin-bottom: 8px;
                line-height: 1.3;
                min-height: 2.6em;
            }
            
            .book-author, .book-year {
                font-size: 13px;
                margin-bottom: 6px;
            }
            
            .book-genre {
                font-size: 11px;
                padding: 6px 12px;
                margin-top: 10px;
            }
            
            .book-stock {
                font-size: 12px;
                padding: 6px 10px;
                margin-top: 10px;
            }
            
            .brand-text {
                font-size: 0.8rem;
            }
            
            .logo-navbar {
                width: 35px;
                height: 35px;
                margin-right: 6px;
            }
            
            .login-button {
                padding: 8px 12px;
                font-size: 0.8rem;
            }
            
            .total-books {
                padding: 8px 16px;
                font-size: 12px;
            }
        }

        /* Extra Small Mobile (kurang dari 320px) */
        @media (max-width: 320px) {
            .brand-text {
                font-size: 0.7rem;
                max-width: 150px;
                text-overflow: ellipsis;
            }
            
            .login-button span {
                display: none;
            }
            
            .login-button {
                padding: 8px 10px;
                min-width: 40px;
            }
            
            .books-section {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .book-card {
                min-height: 260px;
            }
            
            .book-cover, .default-cover {
                height: 160px;
            }
            
            .book-info {
                padding: 12px;
            }
            
            .book-title {
                font-size: 14px;
                line-height: 1.2;
                min-height: 2.4em;
            }
            
            .book-author, .book-year {
                font-size: 11px;
            }
            
            .book-genre {
                font-size: 9px;
                padding: 4px 8px;
            }
            
            .book-stock {
                font-size: 10px;
                padding: 4px 6px;
            }
        }

        /* Landscape Orientation untuk Mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            /* Align filters and books to same grid pattern */
            .filters-section {
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
            }
            
            .books-section {
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
            }
            
            .book-card {
                min-height: 260px;
            }
            
            .book-cover, .default-cover {
                height: 100px;
            }
            
            .book-title {
                font-size: 13px;
                min-height: 2.2em;
            }
            
            .content-header {
                padding: 1rem;
            }
            
            .content-title {
                font-size: 1.3rem;
            }
        }

        /* Navbar responsive */
        .navbar-container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0 15px;
        }

        /* Modal Responsive Styles */
        @media (max-width: 991px) {
            .modal-dialog {
                max-width: 90%;
                margin: 1rem;
            }
            
            .modal-content {
                border-radius: 16px;
            }
        }

        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 95%;
                margin: 0.5rem;
            }
            
            .modal-body {
                padding: 1.25rem !important;
            }
        }

        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 100%;
                margin: 0;
                height: 100vh;
                display: flex;
                align-items: center;
            }
            
            .modal-content {
                border-radius: 0;
                border: none;
                max-height: 90vh;
            }
            
            .modal-body {
                padding: 1rem !important;
                overflow-y: auto;
            }
        }

        /* Touch Optimization */
        @media (hover: none) and (pointer: coarse) {
            .form-control,
            .btn,
            .filter-select,
            .sort-select {
                min-height: 44px;
            }
        }
    </style>
</head>
<body>

<!-- Modern Navbar -->
<nav class="navbar navbar-expand-lg modern-navbar">
    <div class="container-fluid px-3 px-lg-4">
        <!-- Brand -->
        <a class="navbar-brand" href="index.php">
            <div class="logo-navbar">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <span class="brand-text">SMA KI HAJAR DEWANTORO</span>
        </a>

        <!-- Login Button -->
        <div class="ms-auto">
            <a href="#" class="login-button" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login</span>
            </a>
        </div>
    </div>
</nav>

<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--gradient-primary); color: white;">
                <h5 class="modal-title" id="loginModalLabel">
                    <i class="fas fa-sign-in-alt"></i> Login Perpustakaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Login Form -->
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user"></i> Username / NISN / NIG
                        </label>
                        <input type="text" class="form-control" id="username" placeholder="Username / NISN / NIG" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input type="password" class="form-control" id="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="background: var(--gradient-primary); border: none; padding: 12px; border-radius: 12px; font-weight: 600;">
                        LOGIN
                    </button>
                </form>

                <!-- Notification Area -->
                <div id="notification" class="mt-3 text-danger"></div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="content-header">
        <h1 class="content-title">Katalog Buku Perpustakaan</h1>
    </div>

    <div class="search-section">
        <form method="GET" action="">
            <div class="search-container">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari buku berdasarkan judul, penulis, tahun, atau genre..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >
                </div>
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>

            <div class="filters-section">
                <div class="filter-group">
                    <label class="filter-label">Filter Genre</label>
                    <select class="filter-select" name="genre" onchange="this.form.submit()">
                        <option value="">Semua Genre</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?php echo $genre; ?>" <?php echo $genre_filter == $genre ? 'selected' : ''; ?>>
                                <?php echo $genre; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Filter Penulis</label>
                    <select class="filter-select" name="penulis" onchange="this.form.submit()">
                        <option value="">Semua Penulis</option>
                        <?php foreach ($penuliss as $penulis): ?>
                            <option value="<?php echo $penulis; ?>" <?php echo $penulis_filter == $penulis ? 'selected' : ''; ?>>
                                <?php echo $penulis; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Filter Tahun Terbit</label>
                    <select class="filter-select" name="tahun" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        <?php foreach ($tahuns as $tahun): ?>
                            <option value="<?php echo $tahun; ?>" <?php echo $tahun_filter == $tahun ? 'selected' : ''; ?>>
                                <?php echo $tahun; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="sort-section">
                <span class="sort-label">Urutkan:</span>
                <select class="sort-select" name="sort" onchange="this.form.submit()">
                    <option value="">-- Pilih Pengurutan --</option>
                    <option value="judul_asc" <?php echo $sort_filter == 'judul_asc' ? 'selected' : ''; ?>>Judul A-Z</option>
                    <option value="judul_desc" <?php echo $sort_filter == 'judul_desc' ? 'selected' : ''; ?>>Judul Z-A</option>
                    <option value="tanggal_desc" <?php echo $sort_filter == 'tanggal_desc' ? 'selected' : ''; ?>>Terbaru</option>
                    <option value="tanggal_asc" <?php echo $sort_filter == 'tanggal_asc' ? 'selected' : ''; ?>>Terlama</option>
                </select>
            </div>

            <div class="total-books">
                <i class="fas fa-book"></i> Total Buku: <?php echo $total_buku; ?>
            </div>
        </form>
    </div>

     <div class="books-section">
        <?php if (count($books) > 0): ?>    
            <?php foreach ($books as $book): ?>
                <a href="detail-buku.php?id=<?php echo $book['id_buku']; ?>" class="book-card-link">
                    <div class="book-card">
                        <?php 
                            $cover_path = getCoverPath($book['cover']);
                            if ($cover_path !== 'assets/images/default-cover.png'): 
                        ?>
                            <img src="<?php echo $cover_path; ?>" 
                                 alt="<?php echo htmlspecialchars($book['judul_buku']); ?>" 
                                 class="book-cover"
                                 onerror="this.onerror=null; this.src='assets/images/default-cover.png'">
                        <?php else: ?>
                            <div class="default-cover">
                                <?php echo substr($book['judul_buku'], 0, 1); ?>
                            </div>
                        <?php endif; ?>

                        <div class="book-info">
                            <h3 class="book-title"><?php echo $book['judul_buku']; ?></h3>
                            <p class="book-author"><i class="fas fa-user-pen"></i> <?php echo $book['penulis']; ?></p>
                            <p class="book-year"><i class="fas fa-calendar"></i> Tahun: <?php echo $book['tahun_terbit']; ?></p>
                            <span class="book-genre"><?php echo $book['genre']; ?></span>
                            <div class="book-stock">
                                <i class="fas fa-copy"></i> Tersedia: <?php echo $book['stok']; ?> buku
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-books">
                <i class="fas fa-book-open-reader"></i>
                <h3>Tidak ada buku yang ditemukan</h3>
                <p>Silakan coba dengan kata kunci atau filter yang berbeda</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> 

<!-- JavaScript untuk Login -->
<!-- Bagian JavaScript di akhir file -->
<script>
    // Handle form submission
    document.getElementById('loginForm')?.addEventListener('submit', function (event) {
        event.preventDefault();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        const notification = document.getElementById('notification');
        notification.textContent = '';

        fetch('login-check.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                notification.textContent = data.message || 'Username atau Password salah!';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            notification.textContent = 'Terjadi kesalahan pada koneksi.';
        });
    });

    // Pastikan Bootstrap JS sudah dimuat sebelum ini
    document.addEventListener('DOMContentLoaded', function() {
        // Redirect to login when clicking a book (for guests)
        document.querySelectorAll('.book-card-link').forEach(link => {
            link.addEventListener('click', function(e) {
                <?php if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true): ?>
                    e.preventDefault();
                    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                    loginModal.show();
                    const notification = document.getElementById('notification');
                    if (notification) {
                        notification.textContent = 'Silakan login terlebih dahulu untuk melihat detail buku.';
                    }
                <?php endif; ?>
            });
        });
    });
</script>

</body>
</html>