<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in and is either a manager or employee
if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'manajer' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: ../pages/harus_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_buku = (int)$_GET['id'];
    
    // Fetch image path first to delete the file
    $check_query = "SELECT sampul FROM buku WHERE id_buku = $id_buku";
    $result = mysqli_query($koneksi, $check_query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $sampul = $row['sampul'];
        
        // Delete the database record
        $delete_query = "DELETE FROM buku WHERE id_buku = $id_buku";
        if (mysqli_query($koneksi, $delete_query)) {
            
            // If delete successful, also delete the local file if it exists
            if (!empty($sampul) && strpos($sampul, 'assets/uploads/') !== false) {
                $file_path = '../' . $sampul;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            
            header("Location: ../pages/kelola_buku.php?status=deleted");
            exit;
        } else {
            header("Location: ../pages/kelola_buku.php?status=error&msg=" . urlencode(mysqli_error($koneksi)));
            exit;
        }
    }
}

header("Location: ../pages/kelola_buku.php");
?>
