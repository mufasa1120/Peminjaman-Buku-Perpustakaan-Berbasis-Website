<?php
session_start();

// Koneksi ke database
include "../koneksi.php";

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

define('BASE_PATH', realpath(dirname(__FILE__)));
$cover_base_path = BASE_PATH . '/../assets/images/covers/';
$cover_web_path = '../assets/images/covers/';

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
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            color: var(--primary-color) !important;
            text-decoration: none;
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
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 600;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Admin Icon Styles */
        .admin-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
            position: relative;
            overflow: hidden;
        }

        .admin-icon:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 25px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .admin-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .admin-icon:hover::before {
            left: 100%;
        }

        .admin-icon i {
            font-size: 20px;
            z-index: 1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            padding-top: 100px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .back-button {
            display: flex;
            align-items: center;
            background: var(--gradient-primary);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(44, 57, 104, 0.4);
            color: white;
        }

        .back-button i {
            margin-right: 8px;
        }

        /* Logout Button Styles */
        .logout-btn {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            border: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
            cursor: pointer;
            outline: none;
            margin-top: 20px;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(229, 62, 62, 0.4);
            background: linear-gradient(135deg, #c53030 0%, #9b2c2c 100%);
            color: white;
        }

        .logout-btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 10px rgba(229, 62, 62, 0.3);
        }

        .logout-icon {
            margin-right: 8px;
            font-size: 14px;
        }

        .logout-text {
            display: inline;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .search-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
        }

        .search-container {
            display: flex;
            margin-bottom: 25px;
        }

        .search-input {
            flex: 1;
            padding: 16px 24px;
            border: 2px solid #e8ecf4;
            border-radius: 15px 0 0 15px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(44, 57, 104, 0.1);
        }

        .search-button {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 0 30px;
            border-radius: 0 15px 15px 0;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(44, 57, 104, 0.3);
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
            padding: 14px 18px;
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            background: white;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            cursor: pointer;
            font-weight: 500;
        }

        .filter-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(44, 57, 104, 0.1);
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
            padding: 12px 18px;
            border: 2px solid #e8ecf4;
            border-radius: 12px;
            background: white;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            cursor: pointer;
            min-width: 200px;
            font-weight: 500;
        }

        .sort-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(44, 57, 104, 0.1);
        }

        .total-books {
            background: var(--gradient-primary);
            color: white;
            padding: 12px 20px;
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
            border-bottom: 1px solid rgba(44, 57, 104, 0.1);
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
            border-bottom: 1px solid rgba(44, 57, 104, 0.1);
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
            min-height: 200px;
        }

        .book-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-primary);
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            word-break: break-word;
            hyphens: auto;
            min-height: 52px; /* Ensures consistent height for 2 lines */
        }

        .book-author {
            color: var(--primary-color);
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
            background: linear-gradient(135deg, rgba(44, 57, 104, 0.1) 0%, rgba(61, 75, 122, 0.1) 100%);
            color: var(--primary-color);
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
            align-self: flex-start;
            margin-top: auto;
            border: 1px solid rgba(44, 57, 104, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
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

        /* Responsive Design untuk Mobile dan Tablet */
        @media (max-width: 1024px) {
            .container {
                padding: 15px;
                padding-top: 90px;
            }
            
            .page-title {
                font-size: 28px;
            }
            
            .search-section {
                padding: 25px;
            }
            
            .filters-section {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 15px;
            }
            
            .books-section {
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 15px;
            }
            
            .book-card {
                border-radius: 15px;
            }
            
            .book-cover,
            .default-cover {
                height: 200px;
            }
            
            .book-info {
                padding: 18px;
                min-height: 180px;
            }
            
            .book-title {
                font-size: 18px;
                margin-bottom: 10px;
                -webkit-line-clamp: 2;
                min-height: 44px;
            }
            
            .book-author {
                font-size: 13px;
            }
            
            .book-year {
                font-size: 12px;
            }
            
            .book-genre {
                font-size: 11px;
                padding: 6px 12px;
            }
            
            .book-stock {
                font-size: 12px;
                padding: 6px 10px;
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 12px;
                padding-top: 85px;
            }
            
            .header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
            }
            
            .page-title {
                font-size: 24px;
            }
            
            .back-button {
                padding: 10px 20px;
                font-size: 14px;
            }

            .logout-btn {
                padding: 10px 16px;
                font-size: 14px;
            }

            .logout-text {
                display: none;
            }

            .logout-icon {
                margin-right: 0;
                font-size: 16px;
            }
            
            .search-section {
                padding: 20px;
            }
            
            .filters-section {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
            
            .filter-select {
                padding: 12px 14px;
                font-size: 13px;
            }
            
            .sort-section {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            
            .sort-select {
                min-width: auto;
                padding: 12px 14px;
                font-size: 13px;
            }
            
            .books-section {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 12px;
            }
            
            .book-cover,
            .default-cover {
                height: 180px;
            }
            
            .book-info {
                padding: 15px;
                min-height: 160px;
            }
            
            .book-title {
                font-size: 16px;
                margin-bottom: 8px;
                line-height: 1.2;
                -webkit-line-clamp: 2;
                min-height: 38px;
            }
            
            .book-author {
                font-size: 12px;
                margin-bottom: 6px;
            }
            
            .book-year {
                font-size: 11px;
                margin-bottom: 12px;
            }
            
            .book-genre {
                font-size: 10px;
                padding: 5px 10px;
            }
            
            .book-stock {
                font-size: 11px;
                padding: 5px 8px;
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
                padding-top: 80px;
            }
            
            .brand-text {
                font-size: 0.9rem;
            }
            
            .logo-navbar {
                width: 40px;
                height: 40px;
            }
            
            .admin-icon {
                width: 40px;
                height: 40px;
            }

            .admin-icon i {
                font-size: 18px;
            }

            .header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                gap: 8px;
            }

            .page-title {
                font-size: 20px;
            }

            .logout-btn {
                padding: 10px 12px;
                min-width: 44px;
                height: 44px;
                border-radius: 50%;
            }

            .logout-text {
                display: none;
            }

            .logout-icon {
                margin-right: 0;
                font-size: 16px;
            }
            
            .search-section {
                padding: 15px;
            }
            
            .search-container {
                flex-direction: column;
                gap: 8px;
            }
            
            .search-input {
                border-radius: 12px;
                padding: 12px 18px;
                font-size: 14px;
            }
            
            .search-button {
                border-radius: 12px;
                padding: 12px;
                font-size: 14px;
            }
            
            .filters-section {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .books-section {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 10px;
            }
            
            .book-card {
                border-radius: 12px;
            }
            
            .book-cover,
            .default-cover {
                height: 140px;
            }
            
            .default-cover {
                font-size: 18px;
            }
            
            .book-info {
                padding: 12px;
                min-height: 140px;
            }
            
            .book-title {
                font-size: 14px;
                margin-bottom: 6px;
                -webkit-line-clamp: 2;
                line-height: 1.2;
                min-height: 34px;
                word-break: break-word;
            }
            
            .book-author {
                font-size: 11px;
                margin-bottom: 4px;
            }
            
            .book-year {
                font-size: 10px;
                margin-bottom: 8px;
            }
            
            .book-genre {
                font-size: 9px;
                padding: 4px 8px;
            }
            
            .book-stock {
                font-size: 10px;
                padding: 4px 6px;
                margin-top: 8px;
            }
        }

        /* Khusus untuk perangkat sangat kecil */
        @media (max-width: 320px) {
            .books-section {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            
            .book-cover,
            .default-cover {
                height: 120px;
            }
            
            .book-info {
                padding: 10px;
                min-height: 120px;
            }
            
            .book-title {
                font-size: 13px;
                -webkit-line-clamp: 2;
                line-height: 1.1;
                min-height: 30px;
                word-break: break-word;
                overflow-wrap: break-word;
            }

            .page-title {
                font-size: 18px;
            }

            .filters-section {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }
    </style>
</head>
<body>

<!-- Modern Navbar -->
<nav class="navbar navbar-expand-lg modern-navbar">
    <div class="container-fluid px-3 px-lg-4">
        <!-- Brand -->
        <a class="navbar-brand" href="#">
            <div class="logo-navbar">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <span class="brand-text">SMA KI HAJAR DEWANTORO</span>
        </a>
        
        <!-- Admin Icon -->
        <a href="https://Wa.me/6289503319638?text=Halo%20Admin,%20saya%20ingin%20bertanya%20tentang%20perpustakaan" 
           class="admin-icon" 
           target="_blank" 
           title="Hubungi Admin via WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>
</nav>

<div class="container">
    <div class="header">
        <button type="button" class="btn logout-btn" id="logoutBtn" aria-label="Logout dari sistem">
            <i class="fas fa-sign-out-alt logout-icon" aria-hidden="true"></i>
            <span class="logout-text">Logout</span>
        </button>
        <h1 class="page-title">Katalog Buku Perpustakaan</h1>
    </div>

    <div class="search-section">
        <form method="GET" action="">
            <div class="search-container">
                <input 
                    type="text" 
                    class="search-input" 
                    name="search" 
                    placeholder="Cari buku berdasarkan judul, penulis, tahun, atau genre..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >
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
                        <?php if (!empty($book['cover'])): 
                            $cover_file = $cover_base_path . $book['cover'];
                            if (file_exists($cover_file)): ?>
                                <img src="<?php echo $cover_web_path . htmlspecialchars($book['cover']); ?>" 
                                    alt="<?php echo htmlspecialchars($book['judul_buku']); ?>" 
                                    class="book-cover"
                                    onerror="this.onerror=null; this.src='/assets/images/default-cover.png'">
                            <?php else: ?>
                                <div class="default-cover">
                                    <?php echo substr($book['judul_buku'], 0, 1); ?>
                                </div>
                            <?php endif; ?>
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
                <i class="fas fa-book-open-reader" style="font-size: 48px; margin-bottom: 15px;"></i>
                <h3>Tidak ada buku yang ditemukan</h3>
                <p>Silakan coba dengan kata kunci atau filter yang berbeda</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Logout functionality
        document.getElementById('logoutBtn').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                // Add logout animation
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    // Replace with actual logout logic
                    alert('Logout berhasil!');
                    window.location.href = '../index.php';
                }, 500);
            }
        });

</script>

</body>
</html>