<?php
require_once 'config.php';
$conn = getConnection();

// Ambil data pengaturan
$query_setting = "SELECT * FROM pengaturan LIMIT 1";
$result_setting = $conn->query($query_setting);
$setting = $result_setting->fetch_assoc();

$pendaftar = null;
$error = '';

// Proses pencarian
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_pendaftaran = sanitize($_POST['no_pendaftaran']);
    
    if (empty($no_pendaftaran)) {
        $error = "Mohon masukkan nomor pendaftaran!";
    } else {
        $query = "SELECT p.*, j.nama_jurusan, j.kode_jurusan 
                  FROM pendaftar p 
                  JOIN jurusan j ON p.jurusan_id = j.id 
                  WHERE p.no_pendaftaran = '$no_pendaftaran'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $pendaftar = $result->fetch_assoc();
        } else {
            $error = "Nomor pendaftaran tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran - <?php echo SITE_NAME; ?></title>
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
                        <p>Cek Status Pendaftaran</p>
                    </div>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="pendaftaran.php">Daftar</a></li>
                        <li><a href="cek_status.php" class="active">Cek Status</a></li>
                        <li><a href="admin/login.php">Admin</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 40px 20px;">
        <div class="form-container" style="max-width: 600px;">
            <h2 style="text-align: center; color: #667eea; margin-bottom: 30px;">
                <i class="fas fa-search"></i> Cek Status Pendaftaran
            </h2>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Form Pencarian -->
            <form method="POST" action="">
                <div class="form-group">
                    <label>Nomor Pendaftaran</label>
                    <input type="text" name="no_pendaftaran" required 
                           placeholder="Contoh: PPDB202501xxxx"
                           value="<?php echo isset($_POST['no_pendaftaran']) ? $_POST['no_pendaftaran'] : ''; ?>">
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cek Status
                    </button>
                </div>
            </form>

            <!-- Hasil Pencarian -->
            <?php if ($pendaftar): ?>
                <hr style="margin: 40px 0; border: none; border-top: 2px solid #eee;">
                
                <div style="text-align: center; margin-bottom: 30px;">
                    <h3 style="color: #333; margin-bottom: 15px;">Status Pendaftaran</h3>
                    <?php
                    $status_class = '';
                    $status_icon = '';
                    $status_text = '';
                    
                    switch($pendaftar['status_pendaftaran']) {
                        case 'pending':
                            $status_class = 'badge-pending';
                            $status_icon = 'fa-clock';
                            $status_text = 'Menunggu Verifikasi';
                            break;
                        case 'verifikasi':
                            $status_class = 'badge-verifikasi';
                            $status_icon = 'fa-sync';
                            $status_text = 'Sedang Diverifikasi';
                            break;
                        case 'diterima':
                            $status_class = 'badge-diterima';
                            $status_icon = 'fa-check-circle';
                            $status_text = 'DITERIMA';
                            break;
                        case 'ditolak':
                            $status_class = 'badge-ditolak';
                            $status_icon = 'fa-times-circle';
                            $status_text = 'Ditolak';
                            break;
                    }
                    ?>
                    <div style="font-size: 3rem; color: #667eea; margin-bottom: 15px;">
                        <i class="fas <?php echo $status_icon; ?>"></i>
                    </div>
                    <span class="badge <?php echo $status_class; ?>" style="font-size: 1.3rem; padding: 10px 25px;">
                        <?php echo $status_text; ?>
                    </span>
                </div>

                <!-- Info Pendaftar -->
                <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; margin-top: 30px;">
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td style="padding: 10px 0; border: none; font-weight: 600; width: 40%;">Nomor Pendaftaran</td>
                            <td style="padding: 10px 0; border: none;">: <?php echo $pendaftar['no_pendaftaran']; ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; border: none; font-weight: 600;">Nama Lengkap</td>
                            <td style="padding: 10px 0; border: none;">: <?php echo $pendaftar['nama_lengkap']; ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; border: none; font-weight: 600;">Jurusan</td>
                            <td style="padding: 10px 0; border: none;">: <?php echo $pendaftar['nama_jurusan']; ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; border: none; font-weight: 600;">Tanggal Daftar</td>
                            <td style="padding: 10px 0; border: none;">: <?php echo formatTanggalIndo(date('Y-m-d', strtotime($pendaftar['tanggal_daftar']))); ?></td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; border: none; font-weight: 600;">No. HP</td>
                            <td style="padding: 10px 0; border: none;">: <?php echo $pendaftar['no_hp']; ?></td>
                        </tr>
                        <?php if (!empty($pendaftar['email'])): ?>
                        <tr>
                            <td style="padding: 10px 0; border: none; font-weight: 600;">Email</td>
                            <td style="padding: 10px 0; border: none;">: <?php echo $pendaftar['email']; ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>

                <!-- Catatan Admin -->
                <?php if (!empty($pendaftar['catatan_admin'])): ?>
                <div class="alert alert-info" style="margin-top: 20px;">
                    <strong><i class="fas fa-comment"></i> Catatan dari Admin:</strong><br>
                    <?php echo nl2br($pendaftar['catatan_admin']); ?>
                </div>
                <?php endif; ?>

                <!-- Informasi Berdasarkan Status -->
                <?php if ($pendaftar['status_pendaftaran'] == 'pending'): ?>
                    <div class="alert alert-warning" style="margin-top: 20px;">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Informasi:</strong> Data Anda sedang menunggu verifikasi dari panitia PPDB. 
                        Mohon tunggu 1-3 hari kerja.
                    </div>
                <?php elseif ($pendaftar['status_pendaftaran'] == 'verifikasi'): ?>
                    <div class="alert alert-info" style="margin-top: 20px;">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Informasi:</strong> Data Anda sedang dalam proses verifikasi oleh panitia PPDB. 
                        Hasil akan segera diumumkan.
                    </div>
                <?php elseif ($pendaftar['status_pendaftaran'] == 'diterima'): ?>
                    <div class="alert alert-success" style="margin-top: 20px;">
                        <i class="fas fa-check-circle"></i> 
                        <strong>SELAMAT!</strong> Anda diterima sebagai calon siswa <?php echo $setting['nama_sekolah']; ?>. 
                        Silakan lakukan daftar ulang sesuai jadwal yang ditentukan. Hubungi sekolah untuk informasi lebih lanjut.
                    </div>
                <?php elseif ($pendaftar['status_pendaftaran'] == 'ditolak'): ?>
                    <div class="alert alert-danger" style="margin-top: 20px;">
                        <i class="fas fa-times-circle"></i> 
                        <strong>Mohon Maaf,</strong> pendaftaran Anda belum dapat kami terima. 
                        <?php if (!empty($pendaftar['catatan_admin'])): ?>
                            Silakan cek catatan admin di atas untuk informasi lebih lanjut.
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Button Cetak -->
                <div style="text-align: center; margin-top: 30px;">
                    <a href="cetak_bukti.php?no=<?php echo $pendaftar['no_pendaftaran']; ?>" target="_blank" class="btn btn-primary">
                        <i class="fas fa-print"></i> Cetak Bukti Pendaftaran Resmi
                    </a>
                    <a href="cek_status.php" class="btn">
                        <i class="fas fa-search"></i> Cek Nomor Lain
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $setting['nama_sekolah']; ?>. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
<?php $conn->close(); ?>
