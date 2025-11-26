<?php
require_once 'config.php';
$conn = getConnection();

// Ambil data pengaturan
$query_setting = "SELECT * FROM pengaturan LIMIT 1";
$result_setting = $conn->query($query_setting);
$setting = $result_setting->fetch_assoc();

// Cek status pendaftaran
if ($setting['status_pendaftaran'] != 'buka') {
    header("Location: index.php");
    exit();
}

// Ambil data jurusan aktif
$query_jurusan = "SELECT * FROM jurusan WHERE status = 'aktif' ORDER BY nama_jurusan";
$result_jurusan = $conn->query($query_jurusan);

$success = false;
$error = '';
$no_pendaftaran = '';

// Proses form pendaftaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Generate nomor pendaftaran
    $no_pendaftaran = generateNoPendaftaran();
    
    // Ambil dan sanitasi data
    $jurusan_id = sanitize($_POST['jurusan_id']);
    $nama_lengkap = sanitize($_POST['nama_lengkap']);
    $nisn = sanitize($_POST['nisn']);
    $nik = sanitize($_POST['nik']);
    $tempat_lahir = sanitize($_POST['tempat_lahir']);
    $tanggal_lahir = sanitize($_POST['tanggal_lahir']);
    $jenis_kelamin = sanitize($_POST['jenis_kelamin']);
    $agama = sanitize($_POST['agama']);
    $anak_ke = sanitize($_POST['anak_ke']);
    $jumlah_saudara = sanitize($_POST['jumlah_saudara']);
    
    $alamat = sanitize($_POST['alamat']);
    $rt = sanitize($_POST['rt']);
    $rw = sanitize($_POST['rw']);
    $kelurahan = sanitize($_POST['kelurahan']);
    $kecamatan = sanitize($_POST['kecamatan']);
    $kota = sanitize($_POST['kota']);
    $provinsi = sanitize($_POST['provinsi']);
    $kode_pos = sanitize($_POST['kode_pos']);
    
    $no_hp = sanitize($_POST['no_hp']);
    $email = sanitize($_POST['email']);
    
    $asal_sekolah = sanitize($_POST['asal_sekolah']);
    $tahun_lulus = sanitize($_POST['tahun_lulus']);
    
    $nilai_matematika = sanitize($_POST['nilai_matematika']);
    $nilai_bahasa_indonesia = sanitize($_POST['nilai_bahasa_indonesia']);
    $nilai_bahasa_inggris = sanitize($_POST['nilai_bahasa_inggris']);
    $nilai_ipa = sanitize($_POST['nilai_ipa']);
    $rata_rata_nilai = sanitize($_POST['rata_rata_nilai']);
    
    $nama_ayah = sanitize($_POST['nama_ayah']);
    $pekerjaan_ayah = sanitize($_POST['pekerjaan_ayah']);
    $penghasilan_ayah = sanitize($_POST['penghasilan_ayah']);
    $nama_ibu = sanitize($_POST['nama_ibu']);
    $pekerjaan_ibu = sanitize($_POST['pekerjaan_ibu']);
    $penghasilan_ibu = sanitize($_POST['penghasilan_ibu']);
    $no_hp_ortu = sanitize($_POST['no_hp_ortu']);
    
    // Validasi data wajib
    if (empty($nama_lengkap) || empty($jurusan_id) || empty($tempat_lahir) || 
        empty($tanggal_lahir) || empty($alamat) || empty($no_hp) || 
        empty($asal_sekolah) || empty($tahun_lulus)) {
        $error = "Mohon lengkapi semua data yang wajib diisi!";
    } else {
        // Insert data ke database
        $query = "INSERT INTO pendaftar (
            no_pendaftaran, jurusan_id, nama_lengkap, nisn, nik,
            tempat_lahir, tanggal_lahir, jenis_kelamin, agama,
            anak_ke, jumlah_saudara, alamat, rt, rw, kelurahan,
            kecamatan, kota, provinsi, kode_pos, no_hp, email,
            asal_sekolah, tahun_lulus, nilai_matematika, nilai_bahasa_indonesia,
            nilai_bahasa_inggris, nilai_ipa, rata_rata_nilai,
            nama_ayah, pekerjaan_ayah, penghasilan_ayah,
            nama_ibu, pekerjaan_ibu, penghasilan_ibu, no_hp_ortu,
            status_pendaftaran
        ) VALUES (
            '$no_pendaftaran', '$jurusan_id', '$nama_lengkap', '$nisn', '$nik',
            '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$agama',
            '$anak_ke', '$jumlah_saudara', '$alamat', '$rt', '$rw', '$kelurahan',
            '$kecamatan', '$kota', '$provinsi', '$kode_pos', '$no_hp', '$email',
            '$asal_sekolah', '$tahun_lulus', '$nilai_matematika', '$nilai_bahasa_indonesia',
            '$nilai_bahasa_inggris', '$nilai_ipa', '$rata_rata_nilai',
            '$nama_ayah', '$pekerjaan_ayah', '$penghasilan_ayah',
            '$nama_ibu', '$pekerjaan_ibu', '$penghasilan_ibu', '$no_hp_ortu',
            'pending'
        )";
        
        if ($conn->query($query)) {
            $success = true;
        } else {
            $error = "Terjadi kesalahan saat menyimpan data: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - <?php echo SITE_NAME; ?></title>
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
                        <p>Formulir Pendaftaran Online</p>
                    </div>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="pendaftaran.php" class="active">Daftar</a></li>
                        <li><a href="cek_status.php">Cek Status</a></li>
                        <li><a href="admin/login.php">Admin</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 40px 20px;">
        <?php if ($success): ?>
            <!-- Sukses -->
            <div class="form-container" style="text-align: center;">
                <i class="fas fa-check-circle" style="font-size: 5rem; color: #28a745; margin-bottom: 20px;"></i>
                <h2 style="color: #28a745; margin-bottom: 20px;">Pendaftaran Berhasil!</h2>
                <div class="alert alert-success" style="text-align: left;">
                    <p><strong>Selamat! Pendaftaran Anda telah berhasil disubmit.</strong></p>
                    <p>Nomor Pendaftaran Anda adalah:</p>
                    <h3 style="color: #667eea; margin: 15px 0; font-size: 1.8rem;">
                        <?php echo $no_pendaftaran; ?>
                    </h3>
                    <p><strong>PENTING:</strong> Simpan nomor pendaftaran ini untuk cek status pendaftaran Anda.</p>
                    <button onclick="copyToClipboard('<?php echo $no_pendaftaran; ?>')" class="btn btn-primary">
                        <i class="fas fa-copy"></i> Salin Nomor Pendaftaran
                    </button>
                </div>
                <div style="margin-top: 30px;">
                    <a href="cetak_bukti.php?no=<?php echo $no_pendaftaran; ?>" target="_blank" class="btn btn-success">
                        <i class="fas fa-print"></i> Cetak Bukti Pendaftaran
                    </a>
                    <a href="cek_status.php" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cek Status Pendaftaran
                    </a>
                    <a href="index.php" class="btn">
                        <i class="fas fa-home"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Form Pendaftaran -->
            <div class="form-container">
                <h2 style="text-align: center; color: #667eea; margin-bottom: 30px;">
                    <i class="fas fa-edit"></i> Formulir Pendaftaran PPDB
                </h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Mohon isi formulir dengan lengkap dan benar. 
                    Field bertanda <span class="required">*</span> wajib diisi.
                </div>

                <form method="POST" action="" id="formPendaftaran">
                    <!-- Pilih Jurusan -->
                    <div class="form-section-title">
                        <i class="fas fa-graduation-cap"></i> Pilihan Jurusan
                    </div>
                    
                    <div class="form-group">
                        <label>Pilih Jurusan <span class="required">*</span></label>
                        <select name="jurusan_id" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <?php 
                            $result_jurusan->data_seek(0);
                            while ($jurusan = $result_jurusan->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $jurusan['id']; ?>">
                                    <?php echo $jurusan['nama_jurusan']; ?> (Kuota: <?php echo $jurusan['kuota']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Data Pribadi -->
                    <div class="form-section-title">
                        <i class="fas fa-user"></i> Data Pribadi
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama_lengkap" required 
                               placeholder="Sesuai dengan ijazah">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>NISN</label>
                            <input type="text" name="nisn" maxlength="10" 
                                   placeholder="10 digit" pattern="[0-9]{10}">
                        </div>
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" name="nik" maxlength="16" 
                                   placeholder="16 digit" pattern="[0-9]{16}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Tempat Lahir <span class="required">*</span></label>
                            <input type="text" name="tempat_lahir" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir <span class="required">*</span></label>
                            <input type="date" name="tanggal_lahir" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Jenis Kelamin <span class="required">*</span></label>
                            <select name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Agama <span class="required">*</span></label>
                            <select name="agama" required>
                                <option value="">-- Pilih --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Anak Ke-</label>
                            <input type="number" name="anak_ke" min="1" placeholder="Contoh: 1">
                        </div>
                        <div class="form-group">
                            <label>Jumlah Saudara</label>
                            <input type="number" name="jumlah_saudara" min="0" placeholder="Contoh: 2">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="form-section-title">
                        <i class="fas fa-map-marker-alt"></i> Alamat
                    </div>

                    <div class="form-group">
                        <label>Alamat Lengkap <span class="required">*</span></label>
                        <textarea name="alamat" rows="3" required 
                                  placeholder="Jalan, nomor rumah, dll"></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>RT</label>
                            <input type="text" name="rt" placeholder="001">
                        </div>
                        <div class="form-group">
                            <label>RW</label>
                            <input type="text" name="rw" placeholder="002">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Kelurahan/Desa</label>
                            <input type="text" name="kelurahan">
                        </div>
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Kota/Kabupaten</label>
                            <input type="text" name="kota">
                        </div>
                        <div class="form-group">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Kode Pos</label>
                        <input type="text" name="kode_pos" maxlength="5" pattern="[0-9]{5}">
                    </div>

                    <!-- Kontak -->
                    <div class="form-section-title">
                        <i class="fas fa-phone"></i> Kontak
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nomor HP/WhatsApp <span class="required">*</span></label>
                            <input type="tel" name="no_hp" required 
                                   placeholder="08xxxxxxxxxx" pattern="[0-9]{10,13}">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="email@example.com">
                        </div>
                    </div>

                    <!-- Asal Sekolah -->
                    <div class="form-section-title">
                        <i class="fas fa-school"></i> Asal Sekolah
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Sekolah Asal <span class="required">*</span></label>
                            <input type="text" name="asal_sekolah" required 
                                   placeholder="SMP/MTs ...">
                        </div>
                        <div class="form-group">
                            <label>Tahun Lulus <span class="required">*</span></label>
                            <input type="number" name="tahun_lulus" required 
                                   min="2020" max="2030" placeholder="2025">
                        </div>
                    </div>

                    <!-- Nilai -->
                    <div class="form-section-title">
                        <i class="fas fa-chart-bar"></i> Nilai Rata-rata Rapor
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Matematika</label>
                            <input type="number" id="nilai_matematika" name="nilai_matematika" 
                                   min="0" max="100" step="0.01" 
                                   placeholder="85.50" onchange="calculateAverage()">
                        </div>
                        <div class="form-group">
                            <label>Bahasa Indonesia</label>
                            <input type="number" id="nilai_bahasa_indonesia" name="nilai_bahasa_indonesia" 
                                   min="0" max="100" step="0.01" 
                                   placeholder="85.50" onchange="calculateAverage()">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Bahasa Inggris</label>
                            <input type="number" id="nilai_bahasa_inggris" name="nilai_bahasa_inggris" 
                                   min="0" max="100" step="0.01" 
                                   placeholder="85.50" onchange="calculateAverage()">
                        </div>
                        <div class="form-group">
                            <label>IPA</label>
                            <input type="number" id="nilai_ipa" name="nilai_ipa" 
                                   min="0" max="100" step="0.01" 
                                   placeholder="85.50" onchange="calculateAverage()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Rata-rata Nilai (Otomatis)</label>
                        <input type="number" id="rata_rata_nilai" name="rata_rata_nilai" 
                               step="0.01" readonly style="background: #f0f0f0;">
                    </div>

                    <!-- Data Orang Tua -->
                    <div class="form-section-title">
                        <i class="fas fa-users"></i> Data Orang Tua
                    </div>

                    <h4 style="margin: 20px 0 10px 0; color: #667eea;">Data Ayah</h4>
                    <div class="form-group">
                        <label>Nama Ayah</label>
                        <input type="text" name="nama_ayah">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah">
                        </div>
                        <div class="form-group">
                            <label>Penghasilan Ayah</label>
                            <select name="penghasilan_ayah">
                                <option value="">-- Pilih --</option>
                                <option value="< Rp 1.000.000">< Rp 1.000.000</option>
                                <option value="Rp 1.000.000 - Rp 3.000.000">Rp 1.000.000 - Rp 3.000.000</option>
                                <option value="Rp 3.000.000 - Rp 5.000.000">Rp 3.000.000 - Rp 5.000.000</option>
                                <option value="> Rp 5.000.000">> Rp 5.000.000</option>
                            </select>
                        </div>
                    </div>

                    <h4 style="margin: 20px 0 10px 0; color: #667eea;">Data Ibu</h4>
                    <div class="form-group">
                        <label>Nama Ibu</label>
                        <input type="text" name="nama_ibu">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu">
                        </div>
                        <div class="form-group">
                            <label>Penghasilan Ibu</label>
                            <select name="penghasilan_ibu">
                                <option value="">-- Pilih --</option>
                                <option value="< Rp 1.000.000">< Rp 1.000.000</option>
                                <option value="Rp 1.000.000 - Rp 3.000.000">Rp 1.000.000 - Rp 3.000.000</option>
                                <option value="Rp 3.000.000 - Rp 5.000.000">Rp 3.000.000 - Rp 5.000.000</option>
                                <option value="> Rp 5.000.000">> Rp 5.000.000</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>No. HP Orang Tua</label>
                        <input type="tel" name="no_hp_ortu" 
                               placeholder="08xxxxxxxxxx" pattern="[0-9]{10,13}">
                    </div>

                    <!-- Submit -->
                    <div style="text-align: center; margin-top: 30px;">
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="fas fa-paper-plane"></i> Submit Pendaftaran
                        </button>
                        <a href="index.php" class="btn" style="margin-left: 10px;">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
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
