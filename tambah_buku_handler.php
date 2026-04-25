<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in and is a manager or employee
if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'manajer' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: ../pages/harus_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi_rak = mysqli_real_escape_string($koneksi, $_POST['lokasi_rak']);
    $detail_lainnya = isset($_POST['detail_lainnya']) ? mysqli_real_escape_string($koneksi, $_POST['detail_lainnya']) : '';

    // Handle File Upload
    $sampul_path = '';
    if (isset($_FILES['sampul']) && $_FILES['sampul']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['sampul']['tmp_name'];
        $file_name = $_FILES['sampul']['name'];
        $file_size = $_FILES['sampul']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = array('jpg', 'jpeg', 'png', 'webp');
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($file_ext, $allowed_ext)) {
            header("Location: ../pages/tambah_buku.php?status=error&msg=Ekstensi file tidak diizinkan. Hanya JPG, JPEG, PNG, WEBP.");
            exit;
        }

        if ($file_size > $max_size) {
            header("Location: ../pages/tambah_buku.php?status=error&msg=Ukuran file terlalu besar. Maksimal 2MB.");
            exit;
        }

        // Generate unique filename
        $new_file_name = uniqid('book_', true) . '.' . $file_ext;
        $upload_dir = '../assets/uploads/';
        $dest_path = $upload_dir . $new_file_name;

        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $dest_path)) {
            // Path relative to index.php for easier use
            $sampul_path = 'assets/uploads/' . $new_file_name;
        } else {
            header("Location: ../pages/tambah_buku.php?status=error&msg=Gagal mengunggah gambar.");
            exit;
        }
    } else {
        header("Location: ../pages/tambah_buku.php?status=error&msg=Gambar sampul wajib diunggah.");
        exit;
    }

    // Insert into database
    $query = "INSERT INTO buku (judul, penulis, penerbit, jumlah, lokasi_rak, detail_lainnya, sampul) 
              VALUES ('$judul', '$penulis', '$penerbit', $jumlah, '$lokasi_rak', '$detail_lainnya', '$sampul_path')";
              
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../pages/tambah_buku.php?status=success");
    } else {
        header("Location: ../pages/tambah_buku.php?status=error&msg=Kesalahan Database: " . mysqli_error($koneksi));
    }
} else {
    header("Location: ../pages/tambah_buku.php");
}
?>
