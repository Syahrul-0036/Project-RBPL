<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'manajer') {
    header("Location: ../pages/harus_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Validasi username
    $check = mysqli_query($koneksi, "SELECT id_user FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION['error'] = 'Username sudah digunakan, silakan pilih yang lain.';
        header("Location: ../pages/manajemen_karyawan.php");
        exit;
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$hashed_password', 'karyawan')";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = 'Akun karyawan berhasil dibuat!';
        
        // Tambahkan Notifikasi untuk Manajer
        $id_manager = $_SESSION['user_id'];
        $judul_notif = "Staf Baru Terdaftar";
        $pesan_notif = "Akun karyawan atas nama '$nama' telah berhasil dibuat di sistem.";
        mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan) VALUES ($id_manager, '$judul_notif', '$pesan_notif')");
    } else {
        $_SESSION['error'] = 'Terjadi kesalahan sistem.';
    }
    
    header("Location: ../pages/manajemen_karyawan.php");
    exit;
}
?>
