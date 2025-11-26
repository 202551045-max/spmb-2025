<?php
require_once 'config.php';
$conn = getConnection();

// Ambil data pendaftar berdasarkan no_pendaftaran
if (!isset($_GET['no'])) {
    header("Location: cek_status.php");
    exit();
}

$no_pendaftaran = sanitize($_GET['no']);

$query = "SELECT p.*, j.nama_jurusan, j.kode_jurusan 
          FROM pendaftar p 
          JOIN jurusan j ON p.jurusan_id = j.id 
          WHERE p.no_pendaftaran = '$no_pendaftaran'";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    header("Location: cek_status.php");
    exit();
}

$p = $result->fetch_assoc();

// Ambil data pengaturan
$query_setting = "SELECT * FROM pengaturan LIMIT 1";
$result_setting = $conn->query($query_setting);
$setting = $result_setting->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran - <?php echo $p['no_pendaftaran']; ?></title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            padding: 25mm 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Header / Kop Surat */
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 30px;
            position: relative;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo-icon {
            font-size: 40px;
            color: white;
        }

        .kop-surat h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0 5px 0;
            letter-spacing: 1px;
        }

        .kop-surat h2 {
            font-size: 18px;
            font-weight: normal;
            margin: 5px 0;
            color: #333;
        }

        .kop-surat p {
            font-size: 12px;
            margin: 3px 0;
            color: #555;
        }

        /* Judul Surat */
        .judul-surat {
            text-align: center;
            margin: 30px 0 20px 0;
        }

        .judul-surat h3 {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .nomor-surat {
            font-size: 12px;
            margin-top: 5px;
        }

        /* Content */
        .content {
            font-size: 13px;
            line-height: 1.8;
            text-align: justify;
        }

        .pembuka {
            margin: 20px 0;
        }

        /* Data Tabel */
        .data-pendaftar {
            margin: 25px 0;
            width: 100%;
        }

        .data-pendaftar table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-pendaftar td {
            padding: 8px 10px;
            vertical-align: top;
        }

        .data-pendaftar .label {
            width: 40%;
            font-weight: normal;
        }

        .data-pendaftar .separator {
            width: 20px;
            text-align: center;
        }

        .data-pendaftar .value {
            font-weight: bold;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffc107;
        }

        .status-verifikasi {
            background: #cfe2ff;
            color: #084298;
            border: 2px solid #0d6efd;
        }

        .status-diterima {
            background: #d1e7dd;
            color: #0f5132;
            border: 2px solid #198754;
        }

        .status-ditolak {
            background: #f8d7da;
            color: #842029;
            border: 2px solid #dc3545;
        }

        /* Penutup */
        .penutup {
            margin: 30px 0 50px 0;
        }

        /* TTD Section */
        .ttd-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .ttd-box {
            width: 45%;
        }

        .ttd-left {
            text-align: center;
        }

        .ttd-right {
            text-align: center;
        }

        .tanggal {
            margin-bottom: 10px;
        }

        .jabatan {
            font-weight: bold;
            margin-bottom: 80px;
        }

        .nama-ttd {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .nip {
            font-size: 12px;
        }

        /* Stempel */
        .stempel {
            position: absolute;
            right: 100px;
            bottom: 80px;
            width: 120px;
            height: 120px;
            border: 3px solid #dc3545;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: rotate(-15deg);
            opacity: 0.8;
        }

        .stempel-text {
            text-align: center;
            font-weight: bold;
            color: #dc3545;
            line-height: 1.2;
        }

        .stempel-text .besar {
            font-size: 16px;
        }

        .stempel-text .kecil {
            font-size: 10px;
        }

        /* Catatan */
        .catatan {
            margin-top: 40px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            font-size: 12px;
        }

        .catatan strong {
            display: block;
            margin-bottom: 8px;
            color: #667eea;
        }

        /* QR Code Placeholder */
        .qr-code {
            position: absolute;
            bottom: 20mm;
            left: 20mm;
            width: 80px;
            height: 80px;
            border: 2px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
            text-align: center;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            z-index: 1000;
        }

        .print-button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #6c757d;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
            z-index: 1000;
            display: inline-block;
        }

        .back-button:hover {
            background: #5a6268;
        }

        @media print {
            .page {
                box-shadow: none;
                margin: 0;
                padding: 25mm 20mm;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Print & Back Button -->
    <button onclick="window.print()" class="print-button no-print">
        <i class="fas fa-print"></i> Cetak Bukti
    </button>
    <a href="cek_status.php" class="back-button no-print">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="page">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="logo">
                <i class="fas fa-graduation-cap logo-icon"></i>
            </div>
            <h1><?php echo strtoupper($setting['nama_sekolah']); ?></h1>
            <h2>PANITIA PENERIMAAN PESERTA DIDIK BARU (PPDB)</h2>
            <p><?php echo $setting['alamat_sekolah']; ?></p>
            <p>Telp: <?php echo $setting['telp_sekolah']; ?> | Email: <?php echo $setting['email_sekolah']; ?></p>
        </div>

        <!-- Judul Surat -->
        <div class="judul-surat">
            <h3>BUKTI PENDAFTARAN PESERTA DIDIK BARU</h3>
            <p class="nomor-surat">Nomor: <?php echo $p['no_pendaftaran']; ?>/PPDB/<?php echo date('Y'); ?></p>
        </div>

        <!-- Pembuka -->
        <div class="content">
            <div class="pembuka">
                <p>Yang bertanda tangan di bawah ini, Panitia Penerimaan Peserta Didik Baru (PPDB) 
                <?php echo $setting['nama_sekolah']; ?> Tahun Ajaran <?php echo $setting['tahun_ajaran']; ?>, 
                dengan ini menerangkan bahwa:</p>
            </div>

            <!-- Data Pendaftar -->
            <div class="data-pendaftar">
                <table>
                    <tr>
                        <td class="label">Nomor Pendaftaran</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['no_pendaftaran']; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo strtoupper($p['nama_lengkap']); ?></td>
                    </tr>
                    <tr>
                        <td class="label">NISN</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['nisn'] ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tempat, Tanggal Lahir</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['tempat_lahir']; ?>, <?php echo formatTanggalIndo(date('Y-m-d', strtotime($p['tanggal_lahir']))); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Agama</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['agama']; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['alamat']; ?>, RT/RW <?php echo $p['rt'].'/'.$p['rw']; ?>, 
                        <?php echo $p['kelurahan']; ?>, <?php echo $p['kecamatan']; ?>, <?php echo $p['kota']; ?></td>
                    </tr>
                    <tr>
                        <td class="label">No. Telepon/HP</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['no_hp']; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Asal Sekolah</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['asal_sekolah']; ?> (Lulus Tahun <?php echo $p['tahun_lulus']; ?>)</td>
                    </tr>
                    <tr>
                        <td class="label">Jurusan yang Dipilih</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo strtoupper($p['nama_jurusan']); ?> (<?php echo $p['kode_jurusan']; ?>)</td>
                    </tr>
                    <?php if ($p['rata_rata_nilai']): ?>
                    <tr>
                        <td class="label">Rata-rata Nilai</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo $p['rata_rata_nilai']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="label">Tanggal Pendaftaran</td>
                        <td class="separator">:</td>
                        <td class="value"><?php echo formatTanggalIndo(date('Y-m-d', strtotime($p['tanggal_daftar']))); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Status Pendaftaran</td>
                        <td class="separator">:</td>
                        <td class="value">
                            <?php
                            $status_class = 'status-' . $p['status_pendaftaran'];
                            $status_text = '';
                            switch($p['status_pendaftaran']) {
                                case 'pending': $status_text = 'MENUNGGU VERIFIKASI'; break;
                                case 'verifikasi': $status_text = 'SEDANG DIVERIFIKASI'; break;
                                case 'diterima': $status_text = 'DITERIMA'; break;
                                case 'ditolak': $status_text = 'DITOLAK'; break;
                            }
                            ?>
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo $status_text; ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Catatan Admin (jika ada) -->
            <?php if (!empty($p['catatan_admin'])): ?>
            <div class="catatan">
                <strong><i class="fas fa-info-circle"></i> CATATAN PANITIA:</strong>
                <?php echo nl2br($p['catatan_admin']); ?>
            </div>
            <?php endif; ?>

            <!-- Penutup -->
            <div class="penutup">
                <p>Demikian surat bukti pendaftaran ini dibuat dengan sebenarnya untuk dapat dipergunakan 
                sebagaimana mestinya.</p>
            </div>

            <!-- TTD Section -->
            <div class="ttd-section">
                <div class="ttd-box ttd-left">
                    <div class="tanggal">Calon Peserta Didik,</div>
                    <div class="jabatan">&nbsp;</div>
                    <div class="nama-ttd"><?php echo $p['nama_lengkap']; ?></div>
                </div>

                <div class="ttd-box ttd-right">
                    <div class="tanggal"><?php echo ucwords(formatTanggalIndo(date('Y-m-d'))); ?></div>
                    <div class="jabatan">Ketua Panitia PPDB,</div>
                    <div class="nama-ttd">Drs. H. Ahmad Fauzi, M.Pd</div>
                    <div class="nip">NIP. 196512301990031005</div>
                </div>
            </div>

            <!-- Stempel -->
            <div class="stempel">
                <div class="stempel-text">
                    <div class="besar">SMK</div>
                    <div class="kecil">ROHMATUL</div>
                    <div class="kecil">UMMAH</div>
                    <div class="besar">PPDB</div>
                    <div class="kecil"><?php echo date('Y'); ?></div>
                </div>
            </div>

            <!-- QR Code Placeholder -->
            <div class="qr-code no-print">
                <div>
                    <i class="fas fa-qrcode" style="font-size: 50px; color: #ccc;"></i><br>
                    Scan QR
                </div>
            </div>
        </div>

        <!-- Footer Catatan -->
        <div style="position: absolute; bottom: 15mm; left: 20mm; right: 20mm; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 10px;">
            <p><strong>Catatan Penting:</strong></p>
            <ul style="margin-left: 20px; line-height: 1.6;">
                <li>Simpan bukti pendaftaran ini dengan baik</li>
                <li>Bukti ini merupakan tanda bahwa pendaftaran Anda telah tercatat dalam sistem PPDB</li>
                <li>Untuk informasi lebih lanjut hubungi: <?php echo $setting['telp_sekolah']; ?></li>
                <li>Cek status pendaftaran secara berkala melalui website: <?php echo BASE_URL; ?></li>
            </ul>
        </div>
    </div>

    <script>
        // Auto print dialog on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
<?php $conn->close(); ?>
