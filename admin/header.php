<?php
require_once '../config.php';
startSession();

if (!isAdminLoggedIn()) {
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin'; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: #2d3436;
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 25px 20px;
            background: #1e272e;
            border-bottom: 2px solid #667eea;
        }
        .sidebar-header h3 {
            margin: 0;
            font-size: 1.2rem;
        }
        .sidebar-header p {
            margin: 5px 0 0 0;
            font-size: 0.85rem;
            opacity: 0.8;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #667eea;
            padding-left: 30px;
        }
        .sidebar-menu a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            margin-left: 260px;
            flex: 1;
            background: #f4f7f9;
        }
        .topbar {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar h2 {
            margin: 0;
            color: #333;
        }
        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .admin-avatar {
            width: 40px;
            height: 40px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .content-wrapper {
            padding: 30px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-graduation-cap"></i> PPDB Admin</h3>
            <p>SMK Rohmatul Ummah</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a></li>
            <li><a href="pendaftar.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'pendaftar.php') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Data Pendaftar
            </a></li>
            <li><a href="jurusan.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'jurusan.php') ? 'active' : ''; ?>">
                <i class="fas fa-book"></i> Kelola Jurusan
            </a></li>
            <li><a href="pengumuman.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'pengumuman.php') ? 'active' : ''; ?>">
                <i class="fas fa-bullhorn"></i> Pengumuman
            </a></li>
            <li><a href="pengaturan.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'pengaturan.php') ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Pengaturan
            </a></li>
            <li><a href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> Lihat Website
            </a></li>
            <li><a href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h2><?php echo $pageTitle ?? 'Dashboard'; ?></h2>
            <div class="admin-info">
                <div>
                    <strong><?php echo $_SESSION['admin_nama']; ?></strong><br>
                    <small style="color: #666;">Administrator</small>
                </div>
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($_SESSION['admin_nama'], 0, 1)); ?>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-wrapper">
