<?php
require_once '../config.php';
startSession();

// Redirect jika sudah login
if (isAdminLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = md5($_POST['password']);
    
    $conn = getConnection();
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_nama'] = $admin['nama_lengkap'];
        
        redirect('dashboard.php');
    } else {
        $error = "Username atau password salah!";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="form-container" style="max-width: 450px; margin: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <i class="fas fa-user-shield" style="font-size: 4rem; color: #667eea;"></i>
            <h2 style="color: #333; margin-top: 20px;">Login Admin</h2>
            <p style="color: #666;">Sistem PPDB SMK Rohmatul Ummah</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" required placeholder="Masukkan username">
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" required placeholder="Masukkan password">
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                <a href="../index.php" class="btn" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>
        </form>

        <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-radius: 5px; font-size: 0.9rem;">
            <strong>Info Default:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>admin123</code>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>
