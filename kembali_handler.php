<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../pages/harus_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_pinjam = (int)$_GET['id'];
    $id_user = $_SESSION['user_id'];
    
    // Verifikasi kepemilikan dan status
    $check_query = "SELECT id_buku FROM peminjaman WHERE id_pinjam = $id_pinjam AND id_user = $id_user AND status = 'dipinjam'";
    $check_result = mysqli_query($koneksi, $check_query);
    
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $row = mysqli_fetch_assoc($check_result);
        $id_buku = $row['id_buku'];
        
        // Update status peminjaman
        mysqli_query($koneksi, "UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali = CURRENT_TIMESTAMP WHERE id_pinjam = $id_pinjam");
        
        // Tambah stok buku
        mysqli_query($koneksi, "UPDATE buku SET jumlah = jumlah + 1 WHERE id_buku = $id_buku");
        
        // Tambahkan Notifikasi untuk Pelanggan
        $judul_notif = "Pengembalian Selesai";
        $pesan_notif = "Terima kasih! Buku Anda telah berhasil dikembalikan ke sistem.";
        mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan) VALUES ($id_user, '$judul_notif', '$pesan_notif')");

        // Tambahkan Notifikasi untuk KARYAWAN
        $judul_karyawan = "Buku Telah Dikembalikan";
        $pesan_karyawan = "Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.";
        $res_karyawan = mysqli_query($koneksi, "SELECT id_user FROM users WHERE role = 'karyawan'");
        while($k = mysqli_fetch_assoc($res_karyawan)) {
            $id_k = $k['id_user'];
            mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan) VALUES ($id_k, '$judul_karyawan', '$pesan_karyawan')");
        }

        $_SESSION['success_kembali'] = true;
    }
}

header("Location: ../pages/buku_dipinjam.php");
exit;
?>
