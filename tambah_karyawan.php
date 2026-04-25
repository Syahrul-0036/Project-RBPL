<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'manajer') {
    header("Location: harus_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
            font-size: 14px;
        }
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
            background-color: #f9fafb;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:20px;">
                <a href="manajemen_karyawan.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Buat Akun Karyawan</h1>
            </div>

            <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                <form action="../proses/tambah_karyawan_handler.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-input" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-input" placeholder="Buat username tanpa spasi" required>
                        <small style="color:#666; font-size:11px; margin-top:4px; display:block;">Username ini akan digunakan karyawan untuk Login.</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password Sementara</label>
                        <input type="text" name="password" class="form-input" placeholder="Minimal 6 karakter" required minlength="6">
                        <small style="color:#666; font-size:11px; margin-top:4px; display:block;">Berikan password ini ke karyawan. Mereka bisa menggantinya nanti.</small>
                    </div>

                    <button type="submit" class="btn-submit">Simpan & Buat Akun</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
