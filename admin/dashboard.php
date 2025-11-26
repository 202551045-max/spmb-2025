<?php
require_once '../config.php';
startSession();

if (!isAdminLoggedIn()) {
    redirect('login.php');
}

$conn = getConnection();

// Statistik
$query_total = "SELECT COUNT(*) as total FROM pendaftar";
$total_pendaftar = $conn->query($query_total)->fetch_assoc()['total'];

$query_pending = "SELECT COUNT(*) as total FROM pendaftar WHERE status_pendaftaran = 'pending'";
$pending = $conn->query($query_pending)->fetch_assoc()['total'];

$query_diterima = "SELECT COUNT(*) as total FROM pendaftar WHERE status_pendaftaran = 'diterima'";
$diterima = $conn->query($query_diterima)->fetch_assoc()['total'];

$query_ditolak = "SELECT COUNT(*) as total FROM pendaftar WHERE status_pendaftaran = 'ditolak'";
$ditolak = $conn->query($query_ditolak)->fetch_assoc()['total'];

// Pendaftar terbaru
$query_latest = "SELECT p.*, j.nama_jurusan 
                 FROM pendaftar p 
                 JOIN jurusan j ON p.jurusan_id = j.id 
                 ORDER BY p.tanggal_daftar DESC 
                 LIMIT 5";
$result_latest = $conn->query($query_latest);

// Statistik per jurusan
$query_jurusan = "SELECT j.nama_jurusan, COUNT(p.id) as jumlah 
                  FROM jurusan j 
                  LEFT JOIN pendaftar p ON j.id = p.jurusan_id 
                  WHERE j.status = 'aktif'
                  GROUP BY j.id, j.nama_jurusan";
$result_jurusan = $conn->query($query_jurusan);

$pageTitle = 'Dashboard';
include 'header.php';
?>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $total_pendaftar; ?></h3>
            <p>Total Pendaftar</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $pending; ?></h3>
            <p>Menunggu Verifikasi</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $diterima; ?></h3>
            <p>Diterima</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $ditolak; ?></h3>
            <p>Ditolak</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="margin: 30px 0;">
    <a href="pendaftar.php?status=pending" class="btn btn-primary">
        <i class="fas fa-clipboard-check"></i> Verifikasi Pendaftar
    </a>
    <a href="pendaftar.php" class="btn">
        <i class="fas fa-users"></i> Lihat Semua Pendaftar
    </a>
    <a href="pengumuman.php" class="btn">
        <i class="fas fa-bullhorn"></i> Kelola Pengumuman
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 40px;">
    <!-- Pendaftar Terbaru -->
    <div class="table-container">
        <h3 style="padding: 20px; margin: 0; border-bottom: 1px solid #eee;">
            <i class="fas fa-user-plus"></i> Pendaftar Terbaru
        </h3>
        <?php if ($result_latest->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_latest->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?php echo $row['nama_lengkap']; ?></strong><br>
                                <small><?php echo $row['no_pendaftaran']; ?></small>
                            </td>
                            <td><?php echo $row['nama_jurusan']; ?></td>
                            <td>
                                <?php
                                $badge_class = 'badge-' . $row['status_pendaftaran'];
                                $status_text = ucfirst($row['status_pendaftaran']);
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="padding: 20px; text-align: center; color: #666;">Belum ada pendaftar</p>
        <?php endif; ?>
    </div>

    <!-- Statistik Jurusan -->
    <div class="table-container">
        <h3 style="padding: 20px; margin: 0; border-bottom: 1px solid #eee;">
            <i class="fas fa-chart-pie"></i> Statistik Per Jurusan
        </h3>
        <?php if ($result_jurusan->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Jurusan</th>
                        <th style="text-align: center;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_jurusan->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nama_jurusan']; ?></td>
                            <td style="text-align: center;">
                                <strong style="color: #667eea; font-size: 1.2rem;">
                                    <?php echo $row['jumlah']; ?>
                                </strong>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="padding: 20px; text-align: center; color: #666;">Belum ada data</p>
        <?php endif; ?>
    </div>
</div>

<?php
$conn->close();
include 'footer.php';
?>
