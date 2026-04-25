<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: harus_login.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$status_msg = '';
$status_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    
    // Ambil password saat ini
    $query = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user = $id_user");
    $user = mysqli_fetch_assoc($query);
    
    // Verifikasi password lama
    if (password_verify($old_pass, $user['password'])) {
        if ($new_pass === $confirm_pass) {
            // Hash password baru
            $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = mysqli_query($koneksi, "UPDATE users SET password = '$new_hash' WHERE id_user = $id_user");
            
            if ($update) {
                $status_msg = 'Kata sandi berhasil diubah! Silakan login kembali.';
                $status_type = 'success';
            } else {
                $status_msg = 'Terjadi kesalahan sistem.';
                $status_type = 'error';
            }
        } else {
            $status_msg = 'Konfirmasi kata sandi baru tidak cocok!';
            $status_type = 'error';
        }
    } else {
        $status_msg = 'Kata sandi lama salah!';
        $status_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:30px;">
                <a href="profil.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Ubah Kata Password</h1>
            </div>

            <form action="" method="POST">
                <div class="form-group mb-4">
                    <label>Kata Password Lama</label>
                    <input type="password" class="form-control" name="old_password" placeholder="Masukan Kata Password Lama" required>
                </div>
                
                <div class="form-group">
                    <label>Kata Password Baru</label>
                    <input type="password" class="form-control" name="new_password" placeholder="Masukan Kata Password Baru" required minlength="6">
                </div>
                
                <div class="form-group mb-4">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Konfirmasi Kata Sandi Baru" required minlength="6">
                </div>
                
                <button type="submit" class="btn btn-primary" style="margin-top:20px;">Ubah Kata Password</button>
            </form>
        </div>
    </div>

    <script>
        <?php if($status_msg != ''): ?>
        Swal.fire({
            icon: '<?= $status_type ?>',
            title: '<?= $status_type == 'success' ? 'Berhasil' : 'Gagal' ?>',
            text: '<?= $status_msg ?>',
            confirmButtonColor: '#074776'
        }).then(() => {
            <?php if($status_type == 'success'): ?>
            // Arahkan ke logout untuk login ulang demi keamanan
            window.location.href = 'logout.php';
            <?php endif; ?>
        });
        <?php endif; ?>
    </script>
</body>
</html>
