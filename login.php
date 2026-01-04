<?php 
session_start();
require_once 'koneksi.php';

// Jika sudah login, langsung lempar ke admin (Biar gak login ulang)
if(isset($_SESSION['status']) && $_SESSION['status'] == "login"){
    header("location:admin/index.php");
    exit;
}

// LOGIKA LOGIN
if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // Enkripsi MD5 sesuai database

    // Cek database
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");

    if(mysqli_num_rows($cek) > 0){
        $data = mysqli_fetch_assoc($cek);
        
        // BIKIN SESSION (KUNCI UTAMA)
        $_SESSION['username'] = $username;
        $_SESSION['status'] = "login";
        $_SESSION['id'] = $data['id'];

        // Lempar ke Admin
        header("location:admin/index.php");
    } else {
        header("location:login.php?pesan=gagal");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - DolanKuy</title>
    
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="login-split">
        <div class="login-image"></div>
        
        <div class="login-form-container">
            <div class="login-card">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-primary mb-2">Welcome Back!</h2>
                    <p class="text-muted">Silakan login untuk mengelola destinasi.</p>
                </div>

                <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
                    <div class="alert alert-danger text-center border-0 shadow-sm mb-4" role="alert">
                        <small><b>Login Gagal!</b> Username atau Password salah.</small>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'belum_login'): ?>
                    <div class="alert alert-warning text-center border-0 shadow-sm mb-4" role="alert">
                        <small>Silakan login dulu bro.</small>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Username</label>
                        <input type="text" name="username" class="form-control form-control-modern" placeholder="Masukkan username" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Password</label>
                        <input type="password" name="password" class="form-control form-control-modern" placeholder="Masukkan password" required>
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                        Masuk Dashboard
                    </button>

                    <div class="text-center mt-4">
                        <a href="index.php" class="text-decoration-none text-muted small">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Website
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>