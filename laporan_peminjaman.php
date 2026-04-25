<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'manajer') {
    header("Location: harus_login.php");
    exit;
}

// Ambil statistik
$stat_query = mysqli_query($koneksi, "SELECT 
    COUNT(*) as total_transaksi,
    SUM(CASE WHEN status = 'dipinjam' THEN 1 ELSE 0 END) as total_dipinjam,
    SUM(CASE WHEN status = 'dikembalikan' THEN 1 ELSE 0 END) as total_dikembalikan
    FROM peminjaman");
$stat = mysqli_fetch_assoc($stat_query);

// Ambil semua daftar peminjaman
$query = "SELECT p.*, b.judul, b.sampul, u.nama, u.username 
          FROM peminjaman p 
          JOIN buku b ON p.id_buku = b.id_buku 
          JOIN users u ON p.id_user = u.id_user
          ORDER BY p.tanggal_pinjam DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #eee;
            text-align: center;
        }
        .stat-card.full-width {
            grid-column: 1 / -1;
            background: #eff6ff;
            border-color: #bfdbfe;
        }
        .stat-num {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
            font-weight: 600;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-warning { background: #fef08a; color: #854d0e; }
        .badge-success { background: #bbf7d0; color: #166534; }
    </style>
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:20px;">
                <a href="profil.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Laporan Peminjaman</h1>
            </div>

            <div class="stat-grid">
                <div class="stat-card full-width">
                    <div class="stat-num"><?= $stat['total_transaksi'] ?></div>
                    <div class="stat-label">TOTAL TRANSAKSI</div>
                </div>
                <div class="stat-card">
                    <div class="stat-num" style="color:#d97706;"><?= $stat['total_dipinjam'] ?></div>
                    <div class="stat-label">SEDANG DIPINJAM</div>
                </div>
                <div class="stat-card">
                    <div class="stat-num" style="color:#16a34a;"><?= $stat['total_dikembalikan'] ?></div>
                    <div class="stat-label">DIKEMBALIKAN</div>
                </div>
            </div>

            <div class="section-title-small" style="margin-bottom:15px;">Riwayat Transaksi</div>

            <div class="list-container" style="display:flex; flex-direction:column; gap:12px;">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $nama_peminjam = !empty($row['nama']) ? $row['nama'] : $row['username'];
                        $is_dipinjam = $row['status'] === 'dipinjam';
                    ?>
                    <div style="background:white; border:1px solid #eee; border-radius:10px; padding:12px; display:flex; gap:12px; align-items:center;">
                        <div style="width:40px; height:40px; background:#f3f4f6; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#666; flex-shrink:0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:13px; color:var(--text-color); margin-bottom:2px;"><?= htmlspecialchars($row['judul']) ?></div>
                            <div style="font-size:11px; color:#666; margin-bottom:4px;">Oleh: <?= htmlspecialchars($nama_peminjam) ?></div>
                            <div style="font-size:10px; color:#999;">
                                <?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?> 
                                <?= $row['tanggal_kembali'] ? ' - ' . date('d M Y', strtotime($row['tanggal_kembali'])) : '' ?>
                            </div>
                        </div>
                        <div>
                            <?php if($is_dipinjam): ?>
                                <span class="badge badge-warning">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:30px 20px; color:#888; font-size:12px;">Belum ada riwayat transaksi.</div>
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
