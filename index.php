<?php
session_start();
require 'koneksi/koneksi.php';

// Cek apakah splash screen sudah ditampilkan di sesi ini
if (!isset($_SESSION['splash_shown'])) {
    $_SESSION['splash_shown'] = true;
    header("Location: pages/splash.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="mobile-container pb-70">
<div class="header-action">
            <form action="index.php" method="GET" class="search-box" style="flex:1;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" placeholder="Cari Judul Buku, Penulis" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            </form>
            <div class="action-icons">
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'pelanggan'): ?>
                <a href="pages/favorit.php" style="color:inherit;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                <?php endif; ?>
                <a href="pages/notifikasi.php" style="color:inherit; position:relative;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <?php
                    if(isset($_SESSION['user_id'])) {
                        $u_id = $_SESSION['user_id'];
                        $unread_query = mysqli_query($koneksi, "SELECT id_notif FROM notifikasi WHERE id_user = $u_id AND is_read = 0 LIMIT 1");
                        if(mysqli_num_rows($unread_query) > 0) {
                            echo '<span style="position:absolute; top:2px; right:2px; width:8px; height:8px; background:#ef4444; border-radius:50%; border:2px solid white;"></span>';
                        }
                    }
                    ?>
                </a>
            </div>
        </div>

        <div class="content no-padding-top">
            <div class="banner">
                <div class="banner-text">PERPUSTAKAAN:<br>BAHAGIA EA</div>
            </div>

            <div class="book-grid">
                <?php
                // Fetch books from database
                
                $search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
                $query = "SELECT * FROM buku";
                if (!empty($search)) {
                    $query .= " WHERE judul LIKE '%$search%' OR penulis LIKE '%$search%'";
                }
                $query .= " ORDER BY id_buku DESC";
                $result = mysqli_query($koneksi, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Determine status based on jumlah
                        $status_text = "Tersedia";
                        $status_class = "";
                        if ($row['jumlah'] <= 0) {
                            $status_text = "Dipinjam";
                            $status_class = "status-used";
                        }
                        
                        // Default image if empty
                        $sampul = !empty($row['sampul']) ? $row['sampul'] : 'https://via.placeholder.com/150x200?text=No+Cover';
                        // Validate if it's an external URL or local path
                        if (!filter_var($sampul, FILTER_VALIDATE_URL) && !file_exists(__DIR__ . '/' . $sampul)) {
                            // If local file doesn't exist but is not empty URL, fallback
                            // Actually it's fine to just output it, browser will show broken image or fallback
                        }
                ?>
                <div class="book-card" style="position:relative;">
                    <a href="pages/detail_buku.php?id=<?= $row['id_buku'] ?>" style="text-decoration:none; color:inherit; display:block;">
                        <img src="<?= htmlspecialchars($sampul) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" class="book-img">
                        <div class="book-status <?= $status_class ?>"><?= $status_text ?></div>
                        <div class="book-author"><?= htmlspecialchars($row['penulis']) ?></div>
                        <div class="book-title"><?= htmlspecialchars($row['judul']) ?></div>
                    </a>
                    <a href="pages/detail_buku.php?id=<?= $row['id_buku'] ?>" class="btn btn-outline btn-small" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center; gap:5px; margin-top:auto;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        <?= (isset($_SESSION['role']) && $_SESSION['role'] === 'pelanggan') ? 'Pinjam' : 'Detail' ?>
                    </a>
                </div>
                <?php
                    }
                } else {
                    echo "<p style='grid-column: 1 / -1; text-align: center; color: #888;'>Belum ada buku yang tersedia.</p>";
                }
                ?>
            </div>
        </div>

        <div class="bottom-nav">
            <a href="index.php" class="nav-item active">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>Beranda</span>
            </a>
            <a href="pages/profil.php" class="nav-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>Profil</span>
            </a>
        </div>
    </div>
</body>
</html>
