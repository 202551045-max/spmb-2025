<?php
require_once 'config.php';
$conn = getConnection();

// Ambil data pengaturan
$query_setting = "SELECT * FROM pengaturan LIMIT 1";
$result_setting = $conn->query($query_setting);
$setting = $result_setting->fetch_assoc();

// Ambil data jurusan aktif
$query_jurusan = "SELECT * FROM jurusan WHERE status = 'aktif' ORDER BY nama_jurusan";
$result_jurusan = $conn->query($query_jurusan);

// Ambil pengumuman aktif
$today = date('Y-m-d');
$query_pengumuman = "SELECT * FROM pengumuman 
                      WHERE status = 'aktif' 
                      AND (tanggal_mulai IS NULL OR tanggal_mulai <= '$today')
                      AND (tanggal_selesai IS NULL OR tanggal_selesai >= '$today')
                      ORDER BY created_at DESC";
$result_pengumuman = $conn->query($query_pengumuman);

// Hitung total pendaftar
$query_count = "SELECT COUNT(*) as total FROM pendaftar";
$result_count = $conn->query($query_count);
$total_pendaftar = $result_count->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Beranda</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-img">
                        <i class="fas fa-graduation-cap" style="font-size: 2rem; color: #667eea;"></i>
                    </div>
                    <div>
                        <h1><?php echo $setting['nama_sekolah']; ?></h1>
                        <p>Penerimaan Peserta Didik Baru <?php echo $setting['tahun_ajaran']; ?></p>
                    </div>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php" class="active">Beranda</a></li>
                        <li><a href="pendaftaran.php">Daftar</a></li>
                        <li><a href="cek_status.php">Cek Status</a></li>
                        <li><a href="admin/login.php">Admin</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h2>Selamat Datang di PPDB Online</h2>
            <h3><?php echo $setting['nama_sekolah']; ?></h3>
            <p>Tahun Ajaran <?php echo $setting['tahun_ajaran']; ?></p>
            <p style="margin-top: 20px; font-size: 1.1rem;">
                <?php if ($setting['status_pendaftaran'] == 'buka'): ?>
                    <i class="fas fa-check-circle"></i> Pendaftaran Dibuka
                <?php else: ?>
                    <i class="fas fa-times-circle"></i> Pendaftaran Ditutup
                <?php endif; ?>
            </p>
            <div style="margin-top: 30px;">
                <?php if ($setting['status_pendaftaran'] == 'buka'): ?>
                    <a href="pendaftaran.php" class="btn">
                        <i class="fas fa-edit"></i> Daftar Sekarang
                    </a>
                <?php endif; ?>
                <a href="cek_status.php" class="btn">
                    <i class="fas fa-search"></i> Cek Status Pendaftaran
                </a>
            </div>
        </div>
    </section>

    <!-- Pengumuman -->
    <?php if ($result_pengumuman->num_rows > 0): ?>
    <section class="info-section" style="padding: 40px 20px; background: #fff3cd;">
        <div class="container">
            <h2 class="section-title" style="margin-bottom: 20px;">
                <i class="fas fa-bullhorn"></i> Pengumuman
            </h2>
            <?php while ($pengumuman = $result_pengumuman->fetch_assoc()): ?>
            <div class="alert alert-warning" style="text-align: left;">
                <h3 style="margin-bottom: 10px;"><?php echo $pengumuman['judul']; ?></h3>
                <p><?php echo nl2br($pengumuman['isi']); ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Info Section -->
    <section class="info-section">
        <div class="container">
            <h2 class="section-title">Informasi Pendaftaran</h2>
            <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Periode Pendaftaran</h3>
                    <p>
                        <?php echo formatTanggalIndo($setting['tanggal_mulai_pendaftaran']); ?><br>
                        s/d<br>
                        <?php echo formatTanggalIndo($setting['tanggal_akhir_pendaftaran']); ?>
                    </p>
                </div>
                <div class="info-card">
                    <i class="fas fa-users"></i>
                    <h3>Total Pendaftar</h3>
                    <p style="font-size: 2rem; font-weight: bold; color: #667eea;">
                        <?php echo $total_pendaftar; ?>
                    </p>
                    <p>Calon Siswa Terdaftar</p>
                </div>
                <div class="info-card">
                    <i class="fas fa-file-alt"></i>
                    <h3>Persyaratan</h3>
                    <p>
                        - Ijazah/Surat Keterangan Lulus<br>
                        - Kartu Keluarga<br>
                        - Foto 3x4 (3 lembar)<br>
                        - Rapor Semester 1-5
                    </p>
                </div>
                <div class="info-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Gratis Biaya Pendaftaran</h3>
                    <p>
                        Pendaftaran online 100% gratis tanpa dipungut biaya apapun
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Jurusan Section -->
    <section class="info-section" style="background: white;">
        <div class="container">
            <h2 class="section-title">Program Keahlian</h2>
            <div class="jurusan-list">
                <?php while ($jurusan = $result_jurusan->fetch_assoc()): ?>
                    <?php
                    // Hitung jumlah pendaftar per jurusan
                    $jur_id = $jurusan['id'];
                    $query_jml = "SELECT COUNT(*) as jml FROM pendaftar WHERE jurusan_id = $jur_id";
                    $result_jml = $conn->query($query_jml);
                    $jml_pendaftar = $result_jml->fetch_assoc()['jml'];
                    ?>
                    <div class="jurusan-card">
                        <h4><?php echo $jurusan['nama_jurusan']; ?></h4>
                        <p style="color: #666; margin: 10px 0;">
                            <?php echo $jurusan['deskripsi']; ?>
                        </p>
                        <div class="kuota">
                            <i class="fas fa-users"></i>
                            Kuota: <?php echo $jurusan['kuota']; ?> siswa
                        </div>
                        <div class="kuota" style="background: #e3f2fd; color: #1976d2;">
                            <i class="fas fa-user-check"></i>
                            Pendaftar: <?php echo $jml_pendaftar; ?> siswa
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Alur Pendaftaran -->
    <section class="info-section">
        <div class="container">
            <h2 class="section-title">Alur Pendaftaran</h2>
            <div class="info-grid">
                <div class="info-card">
                    <div style="background: #667eea; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 1.5rem; font-weight: bold;">1</div>
                    <h3>Daftar Online</h3>
                    <p>Isi formulir pendaftaran online dengan lengkap dan benar</p>
                </div>
                <div class="info-card">
                    <div style="background: #667eea; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 1.5rem; font-weight: bold;">2</div>
                    <h3>Verifikasi</h3>
                    <p>Data akan diverifikasi oleh panitia PPDB</p>
                </div>
                <div class="info-card">
                    <div style="background: #667eea; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 1.5rem; font-weight: bold;">3</div>
                    <h3>Pengumuman</h3>
                    <p>Cek status penerimaan secara online</p>
                </div>
                <div class="info-card">
                    <div style="background: #667eea; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 1.5rem; font-weight: bold;">4</div>
                    <h3>Daftar Ulang</h3>
                    <p>Siswa yang diterima melakukan daftar ulang</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section class="info-section" style="background: white;">
        <div class="container">
            <h2 class="section-title">Hubungi Kami</h2>
            <div style="text-align: center; max-width: 600px; margin: 0 auto;">
                <p style="margin-bottom: 20px; font-size: 1.1rem;">
                    <i class="fas fa-map-marker-alt" style="color: #667eea;"></i>
                    <strong>Alamat:</strong><br>
                    <?php echo $setting['alamat_sekolah']; ?>
                </p>
                <p style="margin-bottom: 20px; font-size: 1.1rem;">
                    <i class="fas fa-phone" style="color: #667eea;"></i>
                    <strong>Telepon:</strong> <?php echo $setting['telp_sekolah']; ?>
                </p>
                <p style="margin-bottom: 20px; font-size: 1.1rem;">
                    <i class="fas fa-envelope" style="color: #667eea;"></i>
                    <strong>Email:</strong> <?php echo $setting['email_sekolah']; ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $setting['nama_sekolah']; ?>. All Rights Reserved.</p>
            <p>Sistem Penerimaan Peserta Didik Baru Online</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>
