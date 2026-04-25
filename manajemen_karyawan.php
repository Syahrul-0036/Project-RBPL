<?php
session_start();
require '../koneksi/koneksi.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'manajer') {
    header("Location: harus_login.php");
    exit;
}

// Ambil daftar karyawan
$query = "SELECT * FROM users WHERE role = 'karyawan' ORDER BY id_user DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Karyawan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .btn-tambah {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--primary-color);
            color: white;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .karyawan-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .k-avatar {
            width: 50px;
            height: 50px;
            background: #e0f2fe;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
        }
        .k-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .k-info {
            flex: 1;
        }
        .k-name {
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 2px;
            font-size: 15px;
        }
        .k-user {
            font-size: 12px;
            color: #666;
        }
        .btn-hapus {
            background: #fee2e2;
            color: #ef4444;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:20px; ">
                <a href="profil.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Manajemen Karyawan</h1>
            </div>

            <a href="tambah_karyawan.php" class="btn-tambah">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Tambah Karyawan Baru
            </a>

            <div class="list-container" style="display:flex; flex-direction:column; gap:12px;">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $nama = !empty($row['nama']) ? $row['nama'] : $row['username'];
                        $inisial = strtoupper(substr($nama, 0, 1));
                    ?>
                    <div class="karyawan-card">
                        <div class="k-avatar">
                            <?php if(!empty($row['foto_profil']) && file_exists("../".$row['foto_profil'])): ?>
                                <img src="../<?= $row['foto_profil'] ?>" alt="<?= htmlspecialchars($nama) ?>">
                            <?php else: ?>
                                <?= $inisial ?>
                            <?php endif; ?>
                        </div>
                        <div class="k-info">
                            <div class="k-name"><?= htmlspecialchars($nama) ?></div>
                            <div class="k-user">@<?= htmlspecialchars($row['username']) ?></div>
                        </div>
                        <a href="#" onclick="konfirmasiHapus(<?= $row['id_user'] ?>, '<?= htmlspecialchars($nama) ?>')" class="btn-hapus" title="Hapus Karyawan">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:30px 20px; color:#888; font-size:12px;">Belum ada karyawan terdaftar.</div>
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

    <script>
    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Hapus Akun Karyawan?',
            text: "Karyawan '" + nama + "' tidak akan bisa login lagi ke sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            width: 320,
            customClass: {
                popup: 'swal2-custom-border'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../proses/hapus_karyawan_handler.php?id=' + id;
            }
        });
    }

    <?php if(isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= $_SESSION['success'] ?>',
            showConfirmButton: false,
            timer: 2000,
            width: 300
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= $_SESSION['error'] ?>',
            showConfirmButton: true,
            width: 300
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    </script>
</body>
</html>
