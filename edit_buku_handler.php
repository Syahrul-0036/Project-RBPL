<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'manajer' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: ../pages/harus_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = (int)$_POST['id_buku'];
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi_rak = mysqli_real_escape_string($koneksi, $_POST['lokasi_rak']);
    $detail_lainnya = isset($_POST['detail_lainnya']) ? mysqli_real_escape_string($koneksi, $_POST['detail_lainnya']) : '';
    $sampul_lama = $_POST['sampul_lama'];

    $sampul_path = $sampul_lama; // default keep old cover

    // Handle File Upload if exists
    if (isset($_FILES['sampul']) && $_FILES['sampul']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['sampul']['tmp_name'];
        $file_name = $_FILES['sampul']['name'];
        $file_size = $_FILES['sampul']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = array('jpg', 'jpeg', 'png', 'webp');
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($file_ext, $allowed_ext)) {
            header("Location: ../pages/edit_buku.php?id=$id_buku&status=error&msg=Ekstensi file tidak diizinkan.");
            exit;
        }

        if ($file_size > $max_size) {
            header("Location: ../pages/edit_buku.php?id=$id_buku&status=error&msg=Ukuran file terlalu besar.");
            exit;
        }

        $new_file_name = uniqid('book_', true) . '.' . $file_ext;
        $upload_dir = '../assets/uploads/';
        $dest_path = $upload_dir . $new_file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $dest_path)) {
            $sampul_path = 'assets/uploads/' . $new_file_name;
            
            // Delete old file if it was a local upload
            if (!empty($sampul_lama) && strpos($sampul_lama, 'assets/uploads/') !== false) {
                $old_file_path = '../' . $sampul_lama;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        } else {
            header("Location: ../pages/edit_buku.php?id=$id_buku&status=error&msg=Gagal mengunggah gambar baru.");
            exit;
        }
    }

    $query = "UPDATE buku SET 
                judul = '$judul', 
                penulis = '$penulis', 
                penerbit = '$penerbit', 
                jumlah = $jumlah, 
                lokasi_rak = '$lokasi_rak', 
                detail_lainnya = '$detail_lainnya', 
                sampul = '$sampul_path'
              WHERE id_buku = $id_buku";
              
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../pages/edit_buku.php?id=$id_buku&status=success");
    } else {
        header("Location: ../pages/edit_buku.php?id=$id_buku&status=error&msg=Kesalahan Database: " . mysqli_error($koneksi));
    }
} else {
    header("Location: ../pages/kelola_buku.php");
}
?>
