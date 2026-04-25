<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in']) || !isset($_GET['id']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../index.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$id_buku = (int)$_GET['id'];

// Cek apakah sudah difavoritkan
$check_query = mysqli_query($koneksi, "SELECT * FROM favorit WHERE id_user = $id_user AND id_buku = $id_buku");

if (mysqli_num_rows($check_query) > 0) {
    // Jika sudah, hapus dari favorit
    mysqli_query($koneksi, "DELETE FROM favorit WHERE id_user = $id_user AND id_buku = $id_buku");
} else {
    // Jika belum, tambahkan ke favorit
    mysqli_query($koneksi, "INSERT INTO favorit (id_user, id_buku) VALUES ($id_user, $id_buku)");
}

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "detail_buku.php?id=$id_buku";
header("Location: $referer");
exit;
?>
