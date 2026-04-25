<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header("Location: harus_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id_buku = (int)$_GET['id'];
$query = "SELECT * FROM buku WHERE id_buku = $id_buku";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 0) {
    echo "<script>alert('Buku tidak ditemukan.'); window.location.href='../index.php';</script>";
    exit;
}

$buku = mysqli_fetch_assoc($result);
$sampul_url = !empty($buku['sampul']) ? '../' . $buku['sampul'] : 'https://via.placeholder.com/300x400?text=No+Cover';
if (filter_var($buku['sampul'], FILTER_VALIDATE_URL)) {
    $sampul_url = $buku['sampul'];
}

// Fallbacks for missing info
$detail_lainnya = !empty($buku['detail_lainnya']) ? $buku['detail_lainnya'] : 'Tidak ada deskripsi tersedia untuk buku ini.';
// Truncate logic
// Truncate logic
$deskripsi_pendek = strlen($detail_lainnya) > 100 ? substr($detail_lainnya, 0, 100) . '...' : $detail_lainnya;

$is_favorit = false;
if (isset($_SESSION['logged_in'])) {
    $id_user = $_SESSION['user_id'];
    $fav_check = mysqli_query($koneksi, "SELECT 1 FROM favorit WHERE id_user = $id_user AND id_buku = $id_buku");
    if (mysqli_num_rows($fav_check) > 0) {
        $is_favorit = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku - <?= htmlspecialchars($buku['judul']) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .mobile-container {
            /* override to allow scrolling smoothly in detail page without bottom nav */
            padding-bottom: 0;
            background-color: white;
        }
        .cover-section {
            width: 100%;
            height: 380px;
            position: relative;
            background-color: #f8f9fa;
        }
        .cover-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 60px 40px 40px 40px;
        }
        .top-nav-absolute {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
        }
        .back-btn-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.8);
            border-radius: 50%;
            color: #333;
            text-decoration: none;
            backdrop-filter: blur(4px);
        }
        .detail-content {
            padding: 30px 20px 20px 20px;
            background: white;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            margin-top: -20px;
            position: relative;
            z-index: 5;
            min-height: calc(100vh - 360px);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .title-row {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
        }
        .title-left {
            flex: 1;
            padding-right: 0;
            margin-bottom: 10px;
        }
        .book-title-large {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 5px;
            line-height: 1.3;
        }
        .btn-pinjam-small {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 16px;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            background: transparent;
        }
        .title-right-icons {
            display: flex;
            gap: 20px;
            color: #666;
            margin-top: 15px;
            justify-content: center;
        }
        .section-title-small {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 10px;
            width: 100%;
        }
        .desc-text {
            font-size: 12px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 5px;
            width: 100%;
        }
        .read-more {
            font-size: 11px;
            color: #888;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            cursor: pointer;
            margin-bottom: 25px;
            width: 100%;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            row-gap: 20px;
            column-gap: 15px;
            margin-bottom: 30px;
            width: 100%;
        }
        .info-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .info-label {
            font-size: 11px;
            color: #888;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-color);
        }
        
        .status-bar-transparent {
            position: absolute;
            top: 0;
            width: 100%;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 600;
            color: #000;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="mobile-container">
        <!-- Transparent status bar overlapping image -->


        <div style="flex:1; overflow-y:auto; -ms-overflow-style:none; scrollbar-width:none;">
            <div class="cover-section">
                <div class="top-nav-absolute" style="top: 50px;">
                    <a href="../index.php" class="back-btn-circle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </a>
                </div>
                <img src="<?= htmlspecialchars($sampul_url) ?>" alt="<?= htmlspecialchars($buku['judul']) ?>" class="cover-image">
            </div>

            <div class="detail-content">
                <div class="title-row">
                    <div class="title-left">
                        <div class="book-title-large"><?= htmlspecialchars($buku['judul']) ?></div>
                    </div>
                    <?php if($_SESSION['role'] !== 'pelanggan'): ?>
                        <button class="btn-pinjam-small" style="border-color:#ccc; color:#ccc; cursor:not-allowed;" title="Hanya pelanggan yang dapat meminjam" disabled>
                            Hanya Pelanggan
                        </button>
                    <?php elseif($buku['jumlah'] > 0): ?>
                        <button class="btn-pinjam-small" onclick="handlePinjam(<?= $buku['id_buku'] ?>)">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            Pinjam
                        </button>
                    <?php else: ?>
                        <button class="btn-pinjam-small" style="border-color:#ccc; color:#ccc; cursor:not-allowed;" disabled>
                            Habis
                        </button>
                    <?php endif; ?>
                    <div class="title-right-icons" style="margin-top: 15px; justify-content: center;">
                        <?php if($_SESSION['role'] === 'pelanggan'): ?>
                            <a href="../proses/favorit_handler.php?id=<?= $buku['id_buku'] ?>" style="color: <?= $is_favorit ? '#ef4444' : '#666' ?>; display: flex; align-items: center;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= $is_favorit ? '#ef4444' : 'none' ?>" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                            </a>
                        <?php else: ?>
                            <span style="color: #ccc; display: flex; align-items: center; cursor: not-allowed;" title="Hanya pelanggan">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                            </span>
                        <?php endif; ?>
                        <svg onclick="shareBuku()" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="cursor:pointer;"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                    </div>
                </div>

                <div class="section-title-small">Deskripsi</div>
                <div class="desc-text" id="descText">
                    <?= htmlspecialchars($deskripsi_pendek) ?>
                </div>
                <?php if(strlen($detail_lainnya) > 100): ?>
                <div class="read-more" id="readMoreBtn" onclick="toggleDesc()">
                    Baca Selengkapnya
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <?php endif; ?>

                <div class="section-title-small" style="margin-top:20px;">Informasi Buku</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Kategori</div>
                        <div class="info-value">Fiksi</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Rilis</div>
                        <div class="info-value">--</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Penerbit</div>
                        <div class="info-value"><?= htmlspecialchars($buku['penerbit']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Penulis</div>
                        <div class="info-value"><?= htmlspecialchars($buku['penulis']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Stok</div>
                        <div class="info-value"><?= $buku['jumlah'] ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Lokasi</div>
                        <div class="info-value"><?= htmlspecialchars($buku['lokasi_rak']) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const fullDesc = <?= json_encode(htmlspecialchars($detail_lainnya)) ?>;
        const shortDesc = <?= json_encode(htmlspecialchars($deskripsi_pendek)) ?>;
        let isExpanded = false;

        function toggleDesc() {
            const descEl = document.getElementById('descText');
            const btnEl = document.getElementById('readMoreBtn');
            if (isExpanded) {
                descEl.innerHTML = shortDesc;
                btnEl.innerHTML = 'Baca Selengkapnya <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            } else {
                descEl.innerHTML = fullDesc;
                btnEl.innerHTML = 'Sembunyikan <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"></polyline></svg>';
            }
            isExpanded = !isExpanded;
        }

        function shareBuku() {
            if (navigator.share) {
                navigator.share({
                    title: '<?= addslashes(htmlspecialchars($buku['judul'])) ?>',
                    text: 'Lihat buku "<?= addslashes(htmlspecialchars($buku['judul'])) ?>" di Perpustakaan kami!',
                    url: window.location.href,
                }).catch((error) => console.log('Error sharing', error));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Tautan buku berhasil disalin ke clipboard!');
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    alert('Gagal menyalin tautan.');
                });
            }
        }

        function handlePinjam(id) {
            Swal.fire({
                title: 'Detail Peminjaman Akan dikirim ke E-mail Anda',
                confirmButtonText: 'OK',
                confirmButtonColor: '#074776',
                customClass: {
                    popup: 'swal-wide'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Peminjaman Berhasil',
                        text: 'Silahkan Mengambil Buku Sesuai Lokasi',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#074776'
                    }).then(() => {
                        window.location.href = '../proses/pinjam_handler.php?id=' + id;
                    });
                }
            });
        }
    </script>
</body>
</html>
