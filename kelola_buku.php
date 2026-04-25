<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in and is either a manager or employee
if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'manajer' && $_SESSION['role'] !== 'karyawan')) {
    if (isset($_SESSION['logged_in'])) {
        header("Location: profil.php");
    } else {
        header("Location: harus_login.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .top-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .back-btn {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
        }
        .page-title {
            flex: 1;
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin-right: 24px; /* balance flex */
        }
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn-add {
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .list-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding-bottom: 80px;
        }
        .book-list-item {
            display: flex;
            background: white;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 12px;
            gap: 15px;
        }
        .book-list-img {
            width: 70px;
            height: 100px;
            border-radius: 6px;
            object-fit: cover;
        }
        .book-list-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .book-list-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .book-list-author {
            font-size: 11px;
            color: #888;
            margin-bottom: 8px;
        }
        .book-list-stats {
            display: flex;
            gap: 10px;
            font-size: 11px;
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: auto;
        }
        .book-list-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-act {
            flex: 1;
            text-align: center;
            padding: 6px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }
        .btn-edit {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        .btn-delete {
            background-color: #fee2e2;
            color: #ef4444;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>
    <div class="mobile-container">
        <div class="content top-spacing">
            <div class="top-header">
                <a href="profil.php" class="back-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <div class="page-title">Kelola Buku</div>
            </div>

            <div class="action-bar">
                <div style="font-size: 14px; font-weight:600; color:#666;">Total Buku: 
                    <?php 
                    $res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
                    $row = mysqli_fetch_assoc($res);
                    echo $row['total'];
                    ?>
                </div>
                <a href="tambah_buku.php" class="btn-add">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah
                </a>
            </div>

            <div class="list-container">
                <?php
                $query = "SELECT * FROM buku ORDER BY created_at DESC";
                $result = mysqli_query($koneksi, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($buku = mysqli_fetch_assoc($result)) {
                        $sampul = !empty($buku['sampul']) ? '../' . $buku['sampul'] : 'https://via.placeholder.com/70x100?text=No+Cover';
                        if (filter_var($buku['sampul'], FILTER_VALIDATE_URL)) {
                            $sampul = $buku['sampul'];
                        }
                ?>
                <div class="book-list-item">
                    <img src="<?= htmlspecialchars($sampul) ?>" alt="<?= htmlspecialchars($buku['judul']) ?>" class="book-list-img">
                    <div class="book-list-info">
                        <div class="book-list-title"><?= htmlspecialchars($buku['judul']) ?></div>
                        <div class="book-list-author"><?= htmlspecialchars($buku['penulis']) ?> | <?= htmlspecialchars($buku['penerbit']) ?></div>
                        <div class="book-list-stats">
                            <span>Stok: <?= $buku['jumlah'] ?></span>
                            <span>Rak: <?= htmlspecialchars($buku['lokasi_rak']) ?></span>
                        </div>
                        <div class="book-list-actions">
                            <a href="edit_buku.php?id=<?= $buku['id_buku'] ?>" class="btn-act btn-edit">Edit</a>
                            <button onclick="confirmDelete(<?= $buku['id_buku'] ?>)" class="btn-act btn-delete">Hapus</button>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p style='text-align:center; color:#888; padding:20px;'>Tidak ada data buku.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Buku?',
                text: "Data buku dan fotonya akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../proses/hapus_buku_handler.php?id=' + id;
                }
            })
        }

        // Show SweetAlert based on URL parameters
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('status') === 'deleted') {
                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: 'Buku berhasil dihapus.',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if(urlParams.get('status') === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: urlParams.get('msg') ? urlParams.get('msg') : 'Terjadi kesalahan.',
                    confirmButtonColor: '#074776'
                });
            }
        }
    </script>
</body>
</html>
