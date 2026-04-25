<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: harus_login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil daftar buku favorit
$query = "SELECT f.id_favorit, b.id_buku, b.judul, b.penulis, b.sampul 
          FROM favorit f 
          JOIN buku b ON f.id_buku = b.id_buku 
          WHERE f.id_user = $id_user 
          ORDER BY f.created_at DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Favorit</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .btn-remove-fav {
            background-color: #fee2e2;
            color: #ef4444;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
    </style>
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:20px;">
                <a href="../index.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Favorit Saya</h1>
            </div>

            <p style="font-size:12px; color:#666; margin-bottom:20px;">Koleksi buku-buku yang Anda sukai dan simpan.</p>

            <div class="list-container" style="display:flex; flex-direction:column; gap:15px;">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $sampul = !empty($row['sampul']) ? '../' . $row['sampul'] : 'https://via.placeholder.com/80x120?text=No+Cover';
                        if (filter_var($row['sampul'], FILTER_VALIDATE_URL)) {
                            $sampul = $row['sampul'];
                        }
                    ?>
                    <div class="book-list-item" style="display:flex; background:white; border:1px solid #eee; border-radius:12px; padding:15px; gap:15px; box-shadow:0 2px 5px rgba(0,0,0,0.02);">
                        <a href="detail_buku.php?id=<?= $row['id_buku'] ?>">
                            <img src="<?= htmlspecialchars($sampul) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" style="width:70px; height:100px; object-fit:cover; border-radius:6px;">
                        </a>
                        <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                            <a href="detail_buku.php?id=<?= $row['id_buku'] ?>" style="text-decoration:none; color:inherit;">
                                <div style="font-weight:700; font-size:15px; color:var(--text-color); margin-bottom:4px;"><?= htmlspecialchars($row['judul']) ?></div>
                                <div style="font-size:12px; color:#666; margin-bottom:12px;"><?= htmlspecialchars($row['penulis']) ?></div>
                            </a>
                            <div style="margin-top:auto;">
                                <a href="favorit_handler.php?id=<?= $row['id_buku'] ?>" class="btn-remove-fav">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="#ef4444" stroke="#ef4444" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                    Hapus Favorit
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:40px 20px; color:#888;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:15px; opacity:0.5;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        <p>Anda belum menambahkan buku ke daftar favorit.</p>
                        <a href="../index.php" style="display:inline-block; margin-top:15px; color:var(--primary-color); font-weight:600; text-decoration:none;">Jelajahi Buku</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="bottom-nav">
            <a href="../index.php" class="nav-item active">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>Beranda</span>
            </a>
            <a href="profil.php" class="nav-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>Profil</span>
            </a>
        </div>
    </div>
</body>
</html>
