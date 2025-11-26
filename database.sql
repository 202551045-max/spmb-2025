-- Database untuk Aplikasi PPDB SMK Rohmatul Ummah
-- Buat database terlebih dahulu: CREATE DATABASE ppdb_smk_rohmatul_ummah;

CREATE DATABASE IF NOT EXISTS ppdb_smk_rohmatul_ummah;
USE ppdb_smk_rohmatul_ummah;

-- Tabel Admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Jurusan
CREATE TABLE IF NOT EXISTS jurusan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_jurusan VARCHAR(10) NOT NULL UNIQUE,
    nama_jurusan VARCHAR(100) NOT NULL,
    kuota INT NOT NULL DEFAULT 0,
    deskripsi TEXT,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Pendaftar
CREATE TABLE IF NOT EXISTS pendaftar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_pendaftaran VARCHAR(20) NOT NULL UNIQUE,
    jurusan_id INT NOT NULL,
    
    -- Data Pribadi
    nama_lengkap VARCHAR(100) NOT NULL,
    nisn VARCHAR(10),
    nik VARCHAR(16),
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    agama ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu') NOT NULL,
    anak_ke INT,
    jumlah_saudara INT,
    
    -- Alamat
    alamat TEXT NOT NULL,
    rt VARCHAR(5),
    rw VARCHAR(5),
    kelurahan VARCHAR(50),
    kecamatan VARCHAR(50),
    kota VARCHAR(50),
    provinsi VARCHAR(50),
    kode_pos VARCHAR(10),
    
    -- Kontak
    no_hp VARCHAR(15) NOT NULL,
    email VARCHAR(100),
    
    -- Asal Sekolah
    asal_sekolah VARCHAR(100) NOT NULL,
    tahun_lulus YEAR NOT NULL,
    
    -- Nilai
    nilai_matematika DECIMAL(5,2),
    nilai_bahasa_indonesia DECIMAL(5,2),
    nilai_bahasa_inggris DECIMAL(5,2),
    nilai_ipa DECIMAL(5,2),
    rata_rata_nilai DECIMAL(5,2),
    
    -- Data Orang Tua
    nama_ayah VARCHAR(100),
    pekerjaan_ayah VARCHAR(50),
    penghasilan_ayah VARCHAR(50),
    nama_ibu VARCHAR(100),
    pekerjaan_ibu VARCHAR(50),
    penghasilan_ibu VARCHAR(50),
    no_hp_ortu VARCHAR(15),
    
    -- Dokumen Upload
    foto VARCHAR(255),
    ijazah VARCHAR(255),
    kartu_keluarga VARCHAR(255),
    
    -- Status Pendaftaran
    status_pendaftaran ENUM('pending', 'verifikasi', 'diterima', 'ditolak') DEFAULT 'pending',
    catatan_admin TEXT,
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tanggal_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (jurusan_id) REFERENCES jurusan(id)
);

-- Tabel Pengumuman
CREATE TABLE IF NOT EXISTS pengumuman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Pengaturan
CREATE TABLE IF NOT EXISTS pengaturan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(100),
    alamat_sekolah TEXT,
    telp_sekolah VARCHAR(15),
    email_sekolah VARCHAR(100),
    tahun_ajaran VARCHAR(20),
    tanggal_mulai_pendaftaran DATE,
    tanggal_akhir_pendaftaran DATE,
    status_pendaftaran ENUM('buka', 'tutup') DEFAULT 'buka',
    logo VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert data admin default
INSERT INTO admin (username, password, nama_lengkap, email) VALUES 
('admin', MD5('admin123'), 'Administrator', 'admin@smkrohmatulummah.sch.id');

-- Insert data jurusan
INSERT INTO jurusan (kode_jurusan, nama_jurusan, kuota, deskripsi, status) VALUES 
('TKJ', 'Teknik Komputer dan Jaringan', 36, 'Program keahlian yang mempelajari tentang cara instalasi PC, instalasi LAN, memperbaiki PC, dan mempelajari program software', 'aktif'),
('RPL', 'Rekayasa Perangkat Lunak', 36, 'Program keahlian yang mempelajari dan mendalami semua cara-cara pengembangan perangkat lunak termasuk pembuatan, pemeliharaan, manajemen organisasi pengembangan perangkat lunak dan manajemen kualitas', 'aktif'),
('OTKP', 'Otomasi dan Tata Kelola Perkantoran', 36, 'Program keahlian yang mempelajari tentang pengelolaan dan penanganan administrasi kantor dan kesekretarisan', 'aktif'),
('AKL', 'Akuntansi dan Keuangan Lembaga', 36, 'Program keahlian yang mempelajari tentang pengelolaan keuangan, pencatatan transaksi, dan pelaporan keuangan', 'aktif');

-- Insert data pengaturan
INSERT INTO pengaturan (nama_sekolah, alamat_sekolah, telp_sekolah, email_sekolah, tahun_ajaran, tanggal_mulai_pendaftaran, tanggal_akhir_pendaftaran, status_pendaftaran) VALUES 
('SMK Rohmatul Ummah', 'Jl. Pendidikan No. 123, Kota Anda', '(021) 12345678', 'info@smkrohmatulummah.sch.id', '2025/2026', '2025-01-01', '2025-07-31', 'buka');

-- Insert pengumuman
INSERT INTO pengumuman (judul, isi, tanggal_mulai, tanggal_selesai, status) VALUES 
('Penerimaan Peserta Didik Baru 2025/2026', 'Pendaftaran PPDB SMK Rohmatul Ummah Tahun Ajaran 2025/2026 dibuka mulai tanggal 1 Januari 2025 sampai 31 Juli 2025. Daftarkan diri Anda sekarang!', '2025-01-01', '2025-07-31', 'aktif');
