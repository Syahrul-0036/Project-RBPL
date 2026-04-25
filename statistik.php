<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'manajer') {
    header("Location: harus_login.php");
    exit;
}

// Total Buku
$res_buku = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
$total_buku = mysqli_fetch_assoc($res_buku)['total'];

// Total Pelanggan
$res_pelanggan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='pelanggan'");
$total_pelanggan = mysqli_fetch_assoc($res_pelanggan)['total'];

// Total Karyawan
$res_karyawan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role='karyawan'");
$total_karyawan = mysqli_fetch_assoc($res_karyawan)['total'];

// Total Peminjaman
$res_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman");
$total_pinjam = mysqli_fetch_assoc($res_pinjam)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Sistem</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .dash-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .dash-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        .icon-blue { background: #dbeafe; color: #2563eb; }
        .icon-green { background: #dcfce7; color: #16a34a; }
        .icon-purple { background: #f3e8ff; color: #9333ea; }
        .icon-orange { background: #ffedd5; color: #ea580c; }
        
        .dash-title {
            font-size: 13px;
            color: #666;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .dash-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-color);
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
                <h1 class="title" style="margin:0; font-size:20px;">Statistik & Pengaturan</h1>
            </div>

            <p style="font-size:13px; color:#666; margin-bottom:25px;">Tinjauan keseluruhan data sistem perpustakaan saat ini.</p>

            <div class="dashboard-grid">
                <!-- Card 1 -->
                <div class="dash-card">
                    <div class="dash-icon icon-blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <div>
                        <div class="dash-title">Total Buku</div>
                        <div class="dash-value"><?= $total_buku ?></div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="dash-card">
                    <div class="dash-icon icon-green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div>
                        <div class="dash-title">Pelanggan Aktif</div>
                        <div class="dash-value"><?= $total_pelanggan ?></div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="dash-card">
                    <div class="dash-icon icon-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <div>
                        <div class="dash-title">Total Transaksi</div>
                        <div class="dash-value"><?= $total_pinjam ?></div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="dash-card">
                    <div class="dash-icon icon-orange">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div>
                        <div class="dash-title">Total Karyawan</div>
                        <div class="dash-value"><?= $total_karyawan ?></div>
                    </div>
                </div>
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
