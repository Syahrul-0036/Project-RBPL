<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'manajer') {
    header("Location: ../pages/harus_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Pastikan yang dihapus benar-benar karyawan, bukan manajer atau pelanggan
    $check = mysqli_query($koneksi, "SELECT role FROM users WHERE id_user = $id");
    if ($row = mysqli_fetch_assoc($check)) {
        if ($row['role'] === 'karyawan') {
            if (mysqli_query($koneksi, "DELETE FROM users WHERE id_user = $id")) {
                $_SESSION['success'] = 'Akun karyawan berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Gagal menghapus karyawan.';
            }
        } else {
            $_SESSION['error'] = 'Tindakan tidak diizinkan.';
        }
    }
}

header("Location: ../pages/manajemen_karyawan.php");
exit;
?>
