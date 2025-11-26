<?php
require_once '../config.php';
startSession();

if (!isAdminLoggedIn()) {
    exit('Unauthorized');
}

$conn = getConnection();
$id = intval($_GET['id']);

$query = "SELECT p.*, j.nama_jurusan, j.kode_jurusan 
          FROM pendaftar p 
          JOIN jurusan j ON p.jurusan_id = j.id 
          WHERE p.id = $id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    echo '<p style="text-align: center; padding: 50px; color: #666;">Data tidak ditemukan</p>';
    exit();
}

$p = $result->fetch_assoc();
?>

<style>
.detail-section {
    margin-bottom: 30px;
}
.detail-section h4 {
    background: #f8f9fa;
    padding: 12px 15px;
    margin: 0 -30px 15px -30px;
    color: #667eea;
    font-size: 1.1rem;
}
.detail-row {
    display: grid;
    grid-template-columns: 200px 1fr;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}
.detail-label {
    font-weight: 600;
    color: #333;
}
.detail-value {
    color: #666;
}
</style>

<div class="detail-section">
    <h4><i class="fas fa-id-card"></i> Informasi Pendaftaran</h4>
    <div class="detail-row">
        <div class="detail-label">No. Pendaftaran</div>
        <div class="detail-value"><strong style="color: #667eea;"><?php echo $p['no_pendaftaran']; ?></strong></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Status</div>
        <div class="detail-value">
            <span class="badge badge-<?php echo $p['status_pendaftaran']; ?>">
                <?php echo ucfirst($p['status_pendaftaran']); ?>
            </span>
        </div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Jurusan Pilihan</div>
        <div class="detail-value"><strong><?php echo $p['nama_jurusan']; ?></strong> (<?php echo $p['kode_jurusan']; ?>)</div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Tanggal Daftar</div>
        <div class="detail-value"><?php echo date('d F Y, H:i', strtotime($p['tanggal_daftar'])); ?> WIB</div>
    </div>
</div>

<div class="detail-section">
    <h4><i class="fas fa-user"></i> Data Pribadi</h4>
    <div class="detail-row">
        <div class="detail-label">Nama Lengkap</div>
        <div class="detail-value"><strong><?php echo $p['nama_lengkap']; ?></strong></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">NISN</div>
        <div class="detail-value"><?php echo $p['nisn'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">NIK</div>
        <div class="detail-value"><?php echo $p['nik'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Tempat, Tanggal Lahir</div>
        <div class="detail-value"><?php echo $p['tempat_lahir']; ?>, <?php echo date('d F Y', strtotime($p['tanggal_lahir'])); ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Jenis Kelamin</div>
        <div class="detail-value"><?php echo $p['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Agama</div>
        <div class="detail-value"><?php echo $p['agama']; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Anak ke- / Jumlah Saudara</div>
        <div class="detail-value"><?php echo $p['anak_ke'] ?: '-'; ?> / <?php echo $p['jumlah_saudara'] ?: '-'; ?></div>
    </div>
</div>

<div class="detail-section">
    <h4><i class="fas fa-map-marker-alt"></i> Alamat</h4>
    <div class="detail-row">
        <div class="detail-label">Alamat Lengkap</div>
        <div class="detail-value"><?php echo nl2br($p['alamat']); ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">RT / RW</div>
        <div class="detail-value"><?php echo $p['rt'] ?: '-'; ?> / <?php echo $p['rw'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Kelurahan / Kecamatan</div>
        <div class="detail-value"><?php echo $p['kelurahan'] ?: '-'; ?> / <?php echo $p['kecamatan'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Kota / Provinsi</div>
        <div class="detail-value"><?php echo $p['kota'] ?: '-'; ?> / <?php echo $p['provinsi'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Kode Pos</div>
        <div class="detail-value"><?php echo $p['kode_pos'] ?: '-'; ?></div>
    </div>
</div>

<div class="detail-section">
    <h4><i class="fas fa-phone"></i> Kontak</h4>
    <div class="detail-row">
        <div class="detail-label">No. HP / WhatsApp</div>
        <div class="detail-value">
            <strong><?php echo $p['no_hp']; ?></strong>
            <a href="https://wa.me/62<?php echo substr($p['no_hp'], 1); ?>" target="_blank" style="margin-left: 10px;" class="btn btn-success" style="padding: 3px 10px; font-size: 0.85rem;">
                <i class="fab fa-whatsapp"></i> Chat
            </a>
        </div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Email</div>
        <div class="detail-value"><?php echo $p['email'] ?: '-'; ?></div>
    </div>
</div>

<div class="detail-section">
    <h4><i class="fas fa-school"></i> Asal Sekolah</h4>
    <div class="detail-row">
        <div class="detail-label">Nama Sekolah</div>
        <div class="detail-value"><?php echo $p['asal_sekolah']; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Tahun Lulus</div>
        <div class="detail-value"><?php echo $p['tahun_lulus']; ?></div>
    </div>
</div>

<div class="detail-section">
    <h4><i class="fas fa-chart-bar"></i> Nilai Rata-rata</h4>
    <div class="detail-row">
        <div class="detail-label">Matematika</div>
        <div class="detail-value"><?php echo $p['nilai_matematika'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Bahasa Indonesia</div>
        <div class="detail-value"><?php echo $p['nilai_bahasa_indonesia'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Bahasa Inggris</div>
        <div class="detail-value"><?php echo $p['nilai_bahasa_inggris'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">IPA</div>
        <div class="detail-value"><?php echo $p['nilai_ipa'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label"><strong>Rata-rata</strong></div>
        <div class="detail-value"><strong style="color: #667eea; font-size: 1.2rem;"><?php echo $p['rata_rata_nilai'] ?: '-'; ?></strong></div>
    </div>
</div>

<div class="detail-section">
    <h4><i class="fas fa-users"></i> Data Orang Tua</h4>
    <div class="detail-row">
        <div class="detail-label">Nama Ayah</div>
        <div class="detail-value"><?php echo $p['nama_ayah'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Pekerjaan Ayah</div>
        <div class="detail-value"><?php echo $p['pekerjaan_ayah'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Penghasilan Ayah</div>
        <div class="detail-value"><?php echo $p['penghasilan_ayah'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Nama Ibu</div>
        <div class="detail-value"><?php echo $p['nama_ibu'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Pekerjaan Ibu</div>
        <div class="detail-value"><?php echo $p['pekerjaan_ibu'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">Penghasilan Ibu</div>
        <div class="detail-value"><?php echo $p['penghasilan_ibu'] ?: '-'; ?></div>
    </div>
    <div class="detail-row">
        <div class="detail-label">No. HP Orang Tua</div>
        <div class="detail-value"><?php echo $p['no_hp_ortu'] ?: '-'; ?></div>
    </div>
</div>

<!-- Update Status -->
<div class="detail-section">
    <h4><i class="fas fa-clipboard-check"></i> Ubah Status Pendaftaran</h4>
    <div style="text-align: center; padding: 20px;">
        <a href="?action=pending&id=<?php echo $p['id']; ?>" class="btn" style="background: #ffeaa7; color: #856404; margin: 5px;"
           onclick="return confirm('Ubah status menjadi Pending?')">
            <i class="fas fa-clock"></i> Pending
        </a>
        <a href="?action=verifikasi&id=<?php echo $p['id']; ?>" class="btn" style="background: #74b9ff; color: #0652dd; margin: 5px;"
           onclick="return confirm('Ubah status menjadi Verifikasi?')">
            <i class="fas fa-sync"></i> Verifikasi
        </a>
        <a href="?action=diterima&id=<?php echo $p['id']; ?>" class="btn btn-success" style="margin: 5px;"
           onclick="return confirm('Terima pendaftar ini?')">
            <i class="fas fa-check"></i> Terima
        </a>
        <a href="?action=ditolak&id=<?php echo $p['id']; ?>" class="btn" style="background: #ff7675; color: white; margin: 5px;"
           onclick="return confirm('Tolak pendaftar ini?')">
            <i class="fas fa-times"></i> Tolak
        </a>
    </div>
</div>

<!-- Catatan Admin -->
<div class="detail-section">
    <h4><i class="fas fa-comment"></i> Catatan Admin</h4>
    <form method="POST" action="pendaftar.php">
        <input type="hidden" name="pendaftar_id" value="<?php echo $p['id']; ?>">
        <div class="form-group">
            <textarea name="catatan_admin" rows="4" placeholder="Tambahkan catatan untuk pendaftar..."><?php echo $p['catatan_admin']; ?></textarea>
        </div>
        <button type="submit" name="update_catatan" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Catatan
        </button>
    </form>
</div>

<!-- Actions -->
<div style="text-align: center; padding: 20px; border-top: 2px solid #eee; margin: 20px -30px 0 -30px;">
    <a href="../cetak_bukti.php?no=<?php echo $p['no_pendaftaran']; ?>" target="_blank" class="btn btn-success">
        <i class="fas fa-file-pdf"></i> Cetak Bukti Resmi
    </a>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fas fa-print"></i> Cetak Data
    </button>
    <a href="?action=delete&id=<?php echo $p['id']; ?>" class="btn" style="background: #d63031; color: white;"
       onclick="return confirm('PERHATIAN: Data akan dihapus permanen! Lanjutkan?')">
        <i class="fas fa-trash"></i> Hapus Data
    </a>
    <button onclick="closeModal()" class="btn">
        <i class="fas fa-times"></i> Tutup
    </button>
</div>

<?php $conn->close(); ?>
