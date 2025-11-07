CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Password akan di-hash
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin (username, password) 
VALUES ('admin', MD5('kihajardewantoro123'));

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    nisn CHAR(10), -- untuk siswa
    nuptk CHAR(16), -- untuk guru
    kelas VARCHAR(10), -- hanya untuk siswa
    role ENUM('siswa', 'staff', 'jabatan') NOT NULL,
    jabatan ENUM('staff', 'guru') NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NOT NULL,
    genre VARCHAR(50),
    stok INT NOT NULL,
    tanggal_ditambahkan DATE NOT NULL,
    cover VARCHAR(255) -- path atau nama file gambar cover
);

CREATE TABLE rekap_peminjaman_siswa (
    id_rekap_siswa INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_buku INT NOT NULL,
    nama_peminjam VARCHAR(100) NOT NULL,
    tgl_pinjam DATE NOT NULL,
    bulan_pinjam VARCHAR(20) NOT NULL, -- misal: Januari, Februari dll
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku)
);

CREATE TABLE rekap_peminjaman_staff_guru (
    id_rekap_staff_guru INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_buku INT NOT NULL,
    nama_peminjam VARCHAR(100) NOT NULL,
    tgl_pinjam DATE NOT NULL,
    bulan_pinjam VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku)
);

CREATE TABLE rekap_peminjaman_tamu (
    id_rekap_tamu INT AUTO_INCREMENT PRIMARY KEY,
    nama_peminjam VARCHAR(100) NOT NULL,
    keperluan TEXT,
    tgl_pinjam DATE NOT NULL,
    bulan_pinjam VARCHAR(20) NOT NULL
);