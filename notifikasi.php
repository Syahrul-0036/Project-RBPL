<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: harus_login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil notifikasi user
$query = "SELECT * FROM notifikasi WHERE id_user = $user_id ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $query);

// Tandai semua sebagai terbaca saat halaman dibuka
mysqli_query($koneksi, "UPDATE notifikasi SET is_read = 1 WHERE id_user = $user_id");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .notif-item {
            background: white;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            gap: 15px;
            position: relative;
        }
        .notif-item.unread {
            border-left: 4px solid var(--primary-color);
            background: #f0f9ff;
        }
        .notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .icon-blue { background: #dbeafe; color: #2563eb; }
        .icon-green { background: #dcfce7; color: #16a34a; }
        .icon-orange { background: #ffedd5; color: #ea580c; }
        
        .notif-title { font-weight: 700; font-size: 14px; margin-bottom: 3px; color: #333; }
        .notif-msg { font-size: 12px; color: #666; line-height: 1.4; }
        .notif-time { font-size: 10px; color: #999; margin-top: 8px; }
        
        .notif-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 60vh;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:20px; ">
                <a href="../index.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Notifikasi</h1>
            </div>

            <div class="notif-list">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $icon_class = "icon-blue";
                        if (strpos($row['judul'], 'Berhasil') !== false || strpos($row['judul'], 'Selesai') !== false) $icon_class = "icon-green";
                        if (strpos($row['judul'], 'Baru') !== false) $icon_class = "icon-orange";
                    ?>
                    <div class="notif-item <?= $row['is_read'] == 0 ? 'unread' : '' ?>">
                        <div class="notif-icon <?= $icon_class ?>">
                            <?php if($icon_class == "icon-green"): ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <?php elseif($icon_class == "icon-orange"): ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <?php else: ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                            <?php endif; ?>
                        </div>
                        <div style="flex:1;">
                            <div class="notif-title"><?= htmlspecialchars($row['judul']) ?></div>
                            <div class="notif-msg"><?= htmlspecialchars($row['pesan']) ?></div>
                            <div class="notif-time"><?= date('d M, H:i', strtotime($row['created_at'])) ?></div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="notif-empty">
                        <div style="background: #f3f4f6; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; color: #d1d5db;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        </div>
                        <div style="font-weight:700; color:#333; margin-bottom:5px;">Belum ada notifikasi</div>
                        <div style="font-size:13px;">Kabar terbaru akan muncul di sini.</div>
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
