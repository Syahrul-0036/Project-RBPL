<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: harus_login.php");
    exit;
}

// Get user data
$id_user = $_SESSION['user_id'];
require '../koneksi/koneksi.php';
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_user");
$user_data = mysqli_fetch_assoc($query);

$username = $user_data['username'];
$role = $user_data['role'];

// Generate avatar string (First Letter)
$avatar_initial = strtoupper(substr($username, 0, 1));
$has_foto = !empty($user_data['foto_profil']);
$foto_url = $has_foto ? '../' . $user_data['foto_profil'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="mobile-container pb-70" style="background-color: #f8f9fa;">
<div class="content top-spacing">
            <h1 class="title mb-4" style="text-align: left;">Profil Saya</h1>
            
            <div class="profile-header">
                <?php if($has_foto): ?>
                    <img src="<?= htmlspecialchars($foto_url) ?>" alt="Profile" class="profile-avatar" style="object-fit:cover; border:none; padding:0; display:block;">
                <?php else: ?>
                    <div class="profile-avatar"><?= $avatar_initial ?></div>
                <?php endif; ?>
                <div class="profile-name"><?= htmlspecialchars($username) ?></div>
                <div class="profile-role"><?= htmlspecialchars($role) ?></div>
            </div>

            <?php if($role === 'pelanggan'): ?>
            <!-- Menu Pelanggan -->
            <div class="menu-section">
                <div class="section-title">Aktivitas Buku</div>
                <div class="menu-list">
                    <a href="buku_dipinjam.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        </div>
                        Buku Sedang Dipinjam
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                    <a href="riwayat_peminjaman.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        Riwayat Peminjaman
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                </div>
            </div>

            <?php elseif($role === 'karyawan'): ?>
            <!-- Menu Karyawan -->
            <div class="menu-section">
                <div class="section-title">Menu Operasional</div>
                <div class="menu-list">
                    <a href="pantauan_peminjaman.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        Pantauan Peminjaman
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                    <a href="daftar_pengembalian.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        </div>
                        Daftar Pengembalian
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                    <a href="kelola_buku.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        </div>
                        Kelola Buku & Stok
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                </div>
            </div>

            <?php elseif($role === 'manajer'): ?>
            <!-- Menu Manajer -->
            <div class="menu-section">
                <div class="section-title">Menu Manajerial</div>
                <div class="menu-list">
                    <a href="kelola_buku.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        </div>
                        Kelola Buku & Stok
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                    <a href="laporan_peminjaman.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        </div>
                        Laporan Peminjaman
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                    <a href="manajemen_karyawan.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        Manajemen Karyawan
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                    <a href="statistik.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        </div>
                        Statistik & Pengaturan
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Global Settings Menu -->
            <div class="menu-section">
                <div class="section-title">Akun & Preferensi</div>
                <div class="menu-list">
                    <a href="ubah_profil.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        Ubah Profil
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                    <a href="ubah_password.php" class="menu-item">
                        <div class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        Ubah Kata Sandi
                        <div class="menu-arrow"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg></div>
                    </a>
                </div>
            </div>
            
            <a href="logout.php" class="btn btn-logout" style="width: 100%; text-align: center; font-weight: bold; padding: 15px; border-radius: 12px; display: block; text-decoration: none;">Keluar (Logout)</a>
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
