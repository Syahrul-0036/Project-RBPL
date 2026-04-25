<?php
session_start();
require '../koneksi/koneksi.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Secara default, pendaftaran publik hanya untuk role pelanggan
    $role = 'pelanggan'; 

    $check = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = '<div style="color:red; margin-bottom:15px; font-size:13px; text-align:center;">Username atau Email sudah terdaftar!</div>';
    } else {
        $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
        if (mysqli_query($koneksi, $query)) {
            $message = '<div style="color:green; margin-bottom:15px; font-size:13px; text-align:center;">Pendaftaran berhasil! Silakan <a href="login_role.php">Masuk</a>.</div>';
        } else {
            $message = '<div style="color:red; margin-bottom:15px; font-size:13px; text-align:center;">Pendaftaran gagal!</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="mobile-container">
        
        <div class="content">
            <!-- Simplified Registration Illustration -->
            <svg class="illustration mb-4" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Desk -->
                <path d="M20 180 L280 180" stroke="#074776" stroke-width="2"/>
                <!-- Registration Form / Paper -->
                <rect x="100" y="40" width="100" height="130" rx="2" stroke="#074776" stroke-width="2" fill="white"/>
                <line x1="115" y1="65" x2="185" y2="65" stroke="#074776" stroke-width="2"/>
                <line x1="115" y1="85" x2="185" y2="85" stroke="#074776" stroke-width="1"/>
                <line x1="115" y1="105" x2="185" y2="105" stroke="#074776" stroke-width="1"/>
                <line x1="115" y1="125" x2="155" y2="125" stroke="#074776" stroke-width="1"/>
                <!-- Pen -->
                <path d="M210 50 L230 30 L240 40 L220 60 Z" fill="#074776"/>
                <path d="M205 55 L210 50 L220 60 L215 65 Z" fill="#074776"/>
                <path d="M205 55 L200 65 L215 65 Z" fill="#074776"/>
                <!-- Checks -->
                <circle cx="90" cy="100" r="15" stroke="#074776" stroke-width="2" fill="white"/>
                <path d="M85 100 L90 105 L100 95" stroke="#074776" stroke-width="2"/>
            </svg>

            <h1 class="title mb-4">Daftar</h1>
            
            <?php if($message != '') echo $message; ?>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Masukan Username" required>
                </div>
                
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-control" name="email" placeholder="Masukan E-mail" required>
                </div>
                
                <div class="form-group mb-2">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Masukan Password" required>
                </div>
                

                
                <label class="checkbox-group">
                    <input type="checkbox" name="terms" required>
                    Terima syarat & ketentuan
                </label>
                
                <button type="submit" class="btn btn-primary">DAFTAR</button>
            </form>
            
            <div class="separator mt-auto">ATAU</div>
            
            <div class="social-buttons">
                <a href="../proses/auth_facebook.php" class="btn-social">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook">
                    Facebook
                </a>
                <a href="../proses/auth_google.php" class="btn-social">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google">
                    Google
                </a>
            </div>
            
            <div class="footer-link" style="margin-top: 0;">
                Sudah Memiliki Akun? <a href="login_role.php">Masuk</a>
            </div>
        </div>
    </div>
</body>
</html>
