<?php
require_once '../config.php';
startSession();

if (!isAdminLoggedIn()) {
    redirect('login.php');
}

$conn = getConnection();

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action == 'delete') {
        $conn->query("DELETE FROM pendaftar WHERE id = $id");
        redirect('pendaftar.php?msg=deleted');
    } elseif (in_array($action, ['pending', 'verifikasi', 'diterima', 'ditolak'])) {
        $conn->query("UPDATE pendaftar SET status_pendaftaran = '$action' WHERE id = $id");
        redirect('pendaftar.php?msg=updated');
    }
}

// Update catatan admin
if (isset($_POST['update_catatan'])) {
    $id = intval($_POST['pendaftar_id']);
    $catatan = sanitize($_POST['catatan_admin']);
    $conn->query("UPDATE pendaftar SET catatan_admin = '$catatan' WHERE id = $id");
    redirect('pendaftar.php?msg=updated');
}

// Filter
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$filter_jurusan = isset($_GET['jurusan']) ? $_GET['jurusan'] : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Query
$where = [];
if ($filter_status) $where[] = "p.status_pendaftaran = '$filter_status'";
if ($filter_jurusan) $where[] = "p.jurusan_id = '$filter_jurusan'";
if ($search) $where[] = "(p.nama_lengkap LIKE '%$search%' OR p.no_pendaftaran LIKE '%$search%')";

$where_clause = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

$query = "SELECT p.*, j.nama_jurusan, j.kode_jurusan 
          FROM pendaftar p 
          JOIN jurusan j ON p.jurusan_id = j.id 
          $where_clause
          ORDER BY p.tanggal_daftar DESC";
$result = $conn->query($query);

// Jurusan untuk filter
$query_jurusan = "SELECT * FROM jurusan WHERE status = 'aktif' ORDER BY nama_jurusan";
$result_jurusan = $conn->query($query_jurusan);

$pageTitle = 'Data Pendaftar';
include 'header.php';
?>

<!-- Messages -->
<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php 
        if ($_GET['msg'] == 'updated') echo 'Data berhasil diupdate!';
        if ($_GET['msg'] == 'deleted') echo 'Data berhasil dihapus!';
        ?>
    </div>
<?php endif; ?>

<!-- Filters -->
<div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <form method="GET" action="">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label>Status</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" <?php echo $filter_status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="verifikasi" <?php echo $filter_status == 'verifikasi' ? 'selected' : ''; ?>>Verifikasi</option>
                    <option value="diterima" <?php echo $filter_status == 'diterima' ? 'selected' : ''; ?>>Diterima</option>
                    <option value="ditolak" <?php echo $filter_status == 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label>Jurusan</label>
                <select name="jurusan" onchange="this.form.submit()">
                    <option value="">Semua Jurusan</option>
                    <?php 
                    $result_jurusan->data_seek(0);
                    while ($jur = $result_jurusan->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $jur['id']; ?>" <?php echo $filter_jurusan == $jur['id'] ? 'selected' : ''; ?>>
                            <?php echo $jur['nama_jurusan']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label>Cari</label>
                <input type="text" name="search" placeholder="Nama atau No. Pendaftaran" value="<?php echo $search; ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filter
            </button>
            
            <?php if ($filter_status || $filter_jurusan || $search): ?>
            <a href="pendaftar.php" class="btn">
                <i class="fas fa-times"></i> Reset
            </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Actions -->
<div style="margin-bottom: 20px;">
    <button onclick="exportTableToExcel('tablePendaftar', 'data_pendaftar')" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export ke Excel
    </button>
    <span style="margin-left: 20px; color: #666;">
        <i class="fas fa-users"></i> Total: <strong><?php echo $result->num_rows; ?></strong> pendaftar
    </span>
</div>

<!-- Table -->
<div class="table-container">
    <table id="tablePendaftar">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pendaftaran</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Kontak</th>
                <th>Tanggal Daftar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo $row['no_pendaftaran']; ?></strong></td>
                        <td>
                            <strong><?php echo $row['nama_lengkap']; ?></strong><br>
                            <small><?php echo $row['tempat_lahir']; ?>, <?php echo date('d-m-Y', strtotime($row['tanggal_lahir'])); ?></small>
                        </td>
                        <td><?php echo $row['nama_jurusan']; ?></td>
                        <td>
                            <i class="fas fa-phone"></i> <?php echo $row['no_hp']; ?><br>
                            <?php if ($row['email']): ?>
                                <small><i class="fas fa-envelope"></i> <?php echo $row['email']; ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal_daftar'])); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $row['status_pendaftaran']; ?>">
                                <?php echo ucfirst($row['status_pendaftaran']); ?>
                            </span>
                        </td>
                        <td>
                            <button onclick="showDetail(<?php echo $row['id']; ?>)" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.85rem;">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                        Tidak ada data pendaftar
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Detail (akan muncul dengan JavaScript) -->
<div id="modalDetail" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; overflow-y: auto;">
    <div style="max-width: 900px; margin: 50px auto; background: white; border-radius: 10px; padding: 0;">
        <div style="background: #667eea; color: white; padding: 20px; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;"><i class="fas fa-user"></i> Detail Pendaftar</h3>
            <button onclick="closeModal()" style="background: transparent; border: none; color: white; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <div id="modalContent" style="padding: 30px; max-height: 70vh; overflow-y: auto;">
            <!-- Content akan diisi via AJAX -->
        </div>
    </div>
</div>

<script>
function showDetail(id) {
    document.getElementById('modalDetail').style.display = 'block';
    document.getElementById('modalContent').innerHTML = '<div style="text-align: center; padding: 50px;"><i class="fas fa-spinner fa-spin" style="font-size: 3rem; color: #667eea;"></i><br><br>Memuat data...</div>';
    
    // Load detail via AJAX
    fetch('detail_pendaftar.php?id=' + id)
        .then(response => response.text())
        .then(data => {
            document.getElementById('modalContent').innerHTML = data;
        });
}

function closeModal() {
    document.getElementById('modalDetail').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('modalDetail');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php
$conn->close();
include 'footer.php';
?>
