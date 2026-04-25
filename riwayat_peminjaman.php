<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: harus_login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil daftar buku yang sudah dikembalikan
$query = "SELECT p.id_pinjam, p.tanggal_pinjam, p.tanggal_kembali, b.judul, b.penulis, b.sampul 
          FROM peminjaman p 
          JOIN buku b ON p.id_buku = b.id_buku 
          WHERE p.id_user = $id_user AND p.status = 'dikembalikan' 
          ORDER BY p.tanggal_kembali DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:20px;">
                <a href="profil.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Riwayat Pinjam</h1>
            </div>

            <div class="list-container" style="display:flex; flex-direction:column; gap:15px;">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $sampul = !empty($row['sampul']) ? '../' . $row['sampul'] : 'https://via.placeholder.com/80x120?text=No+Cover';
                        if (filter_var($row['sampul'], FILTER_VALIDATE_URL)) {
                            $sampul = $row['sampul'];
                        }
                    ?>
                    <div class="book-list-item" style="display:flex; background:white; border:1px solid #eee; border-radius:12px; padding:15px; gap:15px; box-shadow:0 2px 5px rgba(0,0,0,0.02); opacity:0.8;">
                        <img src="<?= htmlspecialchars($sampul) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" style="width:70px; height:100px; object-fit:cover; border-radius:6px; filter:grayscale(30%);">
                        <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                            <div style="font-weight:700; font-size:15px; color:var(--text-color); margin-bottom:4px;"><?= htmlspecialchars($row['judul']) ?></div>
                            <div style="font-size:12px; color:#666; margin-bottom:12px;"><?= htmlspecialchars($row['penulis']) ?></div>
                            <div style="font-size:10px; color:#888; margin-bottom:2px;">Pinjam: <?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></div>
                            <div style="font-size:10px; color:var(--primary-color); font-weight:600;">Kembali: <?= date('d M Y', strtotime($row['tanggal_kembali'])) ?></div>
                        </div>
                        <div style="display:flex; align-items:center; color:#4ade80;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:40px 20px; color:#888;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:15px; opacity:0.5;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <p>Anda belum memiliki riwayat peminjaman buku.</p>
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
