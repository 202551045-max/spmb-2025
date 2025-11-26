<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ppdb_smk_rohmatul_ummah');

// Konfigurasi Aplikasi
define('BASE_URL', 'http://localhost/spmb/');
define('SITE_NAME', 'PPDB SMK Rohmatul Ummah');

// Konfigurasi Upload
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 2097152); // 2MB dalam bytes

// Koneksi Database
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    return $conn;
}

// Fungsi untuk memulai session
function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Fungsi untuk cek login admin
function isAdminLoggedIn() {
    startSession();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Fungsi untuk redirect
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Fungsi untuk sanitasi input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Fungsi untuk format tanggal Indonesia
function formatTanggalIndo($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Fungsi untuk generate nomor pendaftaran
function generateNoPendaftaran() {
    $tahun = date('Y');
    $bulan = date('m');
    $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    return "PPDB{$tahun}{$bulan}{$random}";
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');
?>
