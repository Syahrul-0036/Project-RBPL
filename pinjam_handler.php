<?php
session_start();

// Cek apakah user sudah login dan perannya pelanggan
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'pelanggan') {
    // Jika belum login, arahkan ke halaman peringatan "harus_login"
    header("Location: ../pages/harus_login.php");
    exit;
}

// Jika sudah login, di sini kita bisa menangani logika peminjaman
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($book_id > 0) {
    // Kurangi stok buku sebanyak 1
    require '../koneksi/koneksi.php';
    
    // Check if stock is available
    $check = mysqli_query($koneksi, "SELECT jumlah FROM buku WHERE id_buku = $book_id");
    if ($check && mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        if ($row['jumlah'] > 0) {
            // Decrease stock
            mysqli_query($koneksi, "UPDATE buku SET jumlah = jumlah - 1 WHERE id_buku = $book_id");
            
            // Insert to peminjaman table
            $id_user = $_SESSION['user_id'];
            mysqli_query($koneksi, "INSERT INTO peminjaman (id_user, id_buku, status) VALUES ($id_user, $book_id, 'dipinjam')");

            // Tambahkan Notifikasi untuk Pelanggan
            $judul_notif = "Peminjaman Berhasil!";
            $pesan_notif = "Anda baru saja meminjam buku. Jangan lupa kembalikan tepat waktu ya!";
            mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan) VALUES ($id_user, '$judul_notif', '$pesan_notif')");

            // Tambahkan Notifikasi untuk SEMUA KARYAWAN
            $judul_karyawan = "Ada Peminjaman Baru";
            $pesan_karyawan = "Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.";
            $res_karyawan = mysqli_query($koneksi, "SELECT id_user FROM users WHERE role = 'karyawan'");
            while($k = mysqli_fetch_assoc($res_karyawan)) {
                $id_k = $k['id_user'];
                mysqli_query($koneksi, "INSERT INTO notifikasi (id_user, judul, pesan) VALUES ($id_k, '$judul_karyawan', '$pesan_karyawan')");
            }
        }
    }
}

// Mengarahkan kembali ke index (bisa ditambahkan parameter sukses jika ingin alert di index)
header("Location: ../index.php");
exit;
?>
