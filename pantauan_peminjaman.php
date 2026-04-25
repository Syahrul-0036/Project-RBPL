<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in and is a manager or employee
if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'manajer' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: harus_login.php");
    exit;
}

// Ambil semua daftar buku yang sedang dipinjam
$query = "SELECT p.id_pinjam, p.tanggal_pinjam, b.judul, b.penulis, b.sampul, u.nama, u.username 
          FROM peminjaman p 
          JOIN buku b ON p.id_buku = b.id_buku 
          JOIN users u ON p.id_user = u.id_user
          WHERE p.status = 'dipinjam' 
          ORDER BY p.tanggal_pinjam DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantauan Peminjaman</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .peminjam-badge {
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:20px;">
                <a href="profil.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Pantauan Peminjaman</h1>
            </div>

            <p style="font-size:12px; color:#666; margin-bottom:20px;">Daftar buku yang saat ini sedang berada di tangan pelanggan (belum dikembalikan).</p>

            <div class="list-container" style="display:flex; flex-direction:column; gap:15px;">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $sampul = !empty($row['sampul']) ? '../' . $row['sampul'] : 'https://via.placeholder.com/80x120?text=No+Cover';
                        if (filter_var($row['sampul'], FILTER_VALIDATE_URL)) {
                            $sampul = $row['sampul'];
                        }
                        $nama_peminjam = !empty($row['nama']) ? $row['nama'] : $row['username'];
                    ?>
                    <div class="book-list-item" style="display:flex; background:white; border:1px solid #eee; border-radius:12px; padding:15px; gap:15px; box-shadow:0 2px 5px rgba(0,0,0,0.02);">
                        <img src="<?= htmlspecialchars($sampul) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" style="width:70px; height:100px; object-fit:cover; border-radius:6px;">
                        <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                            <div class="peminjam-badge">👤 <?= htmlspecialchars($nama_peminjam) ?></div>
                            <div style="font-weight:700; font-size:15px; color:var(--text-color); margin-bottom:4px;"><?= htmlspecialchars($row['judul']) ?></div>
                            <div style="font-size:12px; color:#666; margin-bottom:8px;"><?= htmlspecialchars($row['penulis']) ?></div>
                            <div style="font-size:11px; color:#999; margin-top:auto;">Dipinjam: <?= date('d M Y, H:i', strtotime($row['tanggal_pinjam'])) ?></div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:40px 20px; color:#888;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:15px; opacity:0.5;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <p>Tidak ada peminjaman aktif saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="bottom-nav">
            <a href="../index.php" class="nav-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>Beranda</span>
            </a>
            <a href="profil.php" class="nav-item active">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>Profil</span>
            </a>
        </div>
    </div>
</body>
</html>
