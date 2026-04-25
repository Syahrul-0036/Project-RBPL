<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: harus_login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// Ambil daftar buku yang sedang dipinjam
$query = "SELECT p.id_pinjam, p.tanggal_pinjam, b.judul, b.penulis, b.sampul 
          FROM peminjaman p 
          JOIN buku b ON p.id_buku = b.id_buku 
          WHERE p.id_user = $id_user AND p.status = 'dipinjam' 
          ORDER BY p.tanggal_pinjam DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Sedang Dipinjam</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:20px;">
                <a href="profil.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Sedang Dipinjam</h1>
            </div>

            <div class="list-container" style="display:flex; flex-direction:column; gap:15px;">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $sampul = !empty($row['sampul']) ? '../' . $row['sampul'] : 'https://via.placeholder.com/80x120?text=No+Cover';
                        if (filter_var($row['sampul'], FILTER_VALIDATE_URL)) {
                            $sampul = $row['sampul'];
                        }
                    ?>
                    <div class="book-list-item" style="display:flex; background:white; border:1px solid #eee; border-radius:12px; padding:15px; gap:15px; box-shadow:0 2px 5px rgba(0,0,0,0.02);">
                        <img src="<?= htmlspecialchars($sampul) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" style="width:70px; height:100px; object-fit:cover; border-radius:6px;">
                        <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                            <div style="font-weight:700; font-size:15px; color:var(--text-color); margin-bottom:4px;"><?= htmlspecialchars($row['judul']) ?></div>
                            <div style="font-size:12px; color:#666; margin-bottom:8px;"><?= htmlspecialchars($row['penulis']) ?></div>
                            <div style="font-size:11px; color:#999; margin-bottom:10px;">Dipinjam: <?= date('d M Y, H:i', strtotime($row['tanggal_pinjam'])) ?></div>
                            <button type="button" onclick="konfirmasiKembali(<?= $row['id_pinjam'] ?>, '<?= addslashes($row['judul']) ?>')" style="background:var(--primary-color); color:white; border:none; padding:8px 15px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; width:100%;">Kembalikan Buku</button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:40px 20px; color:#888;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:15px; opacity:0.5;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        <p>Anda belum meminjam buku apapun saat ini.</p>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function konfirmasiKembali(id, judul) {
        Swal.fire({
            title: 'Kembalikan Buku?',
            text: "Apakah Anda yakin ingin mengembalikan buku '" + judul + "'?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#074776',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Kembalikan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../proses/kembali_handler.php?id=' + id;
            }
        });
    }

    <?php if(isset($_SESSION['success_kembali'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Buku telah berhasil dikembalikan ke perpustakaan.',
            confirmButtonColor: '#074776'
        });
        <?php unset($_SESSION['success_kembali']); ?>
    <?php endif; ?>
    </script>
</body>
</html>
