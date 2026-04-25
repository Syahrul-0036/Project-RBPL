<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'manajer' && $_SESSION['role'] !== 'karyawan')) {
    header("Location: harus_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: kelola_buku.php");
    exit;
}

$id_buku = (int)$_GET['id'];
$query = "SELECT * FROM buku WHERE id_buku = $id_buku";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 0) {
    header("Location: kelola_buku.php?status=error&msg=Buku tidak ditemukan.");
    exit;
}

$buku = mysqli_fetch_assoc($result);
$sampul_url = !empty($buku['sampul']) ? '../' . $buku['sampul'] : '';
if (filter_var($buku['sampul'], FILTER_VALIDATE_URL)) {
    $sampul_url = $buku['sampul'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-container {
            background-color: var(--primary-color);
            border-radius: 20px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }
        .form-container .form-group label {
            color: white;
            font-weight: 500;
        }
        .form-container .form-control {
            background-color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .form-container .form-control::placeholder {
            color: #999;
        }
        .cover-upload {
            background-color: white;
            border-radius: 8px;
            width: 100%;
            aspect-ratio: 3/4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            overflow: hidden;
            position: relative;
            margin-bottom: 15px;
        }
        .cover-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            /* show by default if there is a cover */
            display: <?= !empty($sampul_url) ? 'block' : 'none' ?>;
        }
        .cover-upload-icon {
            z-index: 2;
            color: var(--text-color);
            text-align: center;
            display: <?= !empty($sampul_url) ? 'none' : 'block' ?>;
        }
        .cover-upload-text {
            font-size: 10px;
            font-weight: 600;
            margin-top: 5px;
            background: white;
            padding: 2px 8px;
            border-radius: 10px;
        }
        .top-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .back-btn {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
        }
        .page-title {
            flex: 1;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: #666;
            margin-right: 24px;
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-col {
            flex: 1;
        }
        .form-col-left {
            flex: 0 0 35%;
        }
        .form-col-right {
            flex: 1;
        }
        .btn-submit {
            margin-top: auto;
        }
        .help-text {
            font-size: 9px;
            color: #ccc;
            text-align: center;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="mobile-container">
        <div class="content top-spacing">
            <div class="top-header">
                <a href="kelola_buku.php" class="back-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <div class="page-title">Edit Buku</div>
            </div>

            <form action="../proses/edit_buku_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_buku" value="<?= $buku['id_buku'] ?>">
                <input type="hidden" name="sampul_lama" value="<?= htmlspecialchars($buku['sampul']) ?>">

                <div class="form-container">
                    <div class="form-row">
                        <div class="form-col-left">
                            <div class="cover-upload" onclick="document.getElementById('sampul').click()">
                                <img id="coverPreview" src="<?= htmlspecialchars($sampul_url) ?>" alt="Preview">
                                <div class="cover-upload-icon" id="coverIcon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle><line x1="12" y1="11" x2="12" y2="15"></line><line x1="10" y1="13" x2="14" y2="13"></line></svg>
                                    <div class="cover-upload-text">Ubah Sampul</div>
                                </div>
                            </div>
                            <input type="file" id="sampul" name="sampul" accept="image/*" style="display: none;" onchange="previewImage(this)">
                            <div class="help-text">Kosongkan jika tidak ingin mengubah foto.</div>
                        </div>
                        <div class="form-col-right">
                            <div class="form-group">
                                <label for="judul">Judul Buku</label>
                                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="penulis">Penulis</label>
                                <input type="text" class="form-control" id="penulis" name="penulis" value="<?= htmlspecialchars($buku['penulis']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="penerbit">Penerbit</label>
                                <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= htmlspecialchars($buku['penerbit']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="jumlah">Jumlah Buku</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?= $buku['jumlah'] ?>" min="0" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="lokasi_rak">Lokasi Rak</label>
                                <input type="text" class="form-control" id="lokasi_rak" name="lokasi_rak" value="<?= htmlspecialchars($buku['lokasi_rak']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="detail_lainnya">Detail Lainnya (Opsional)</label>
                        <input type="text" class="form-control" id="detail_lainnya" name="detail_lainnya" value="<?= htmlspecialchars($buku['detail_lainnya']) ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">SIMPAN PERUBAHAN</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('coverPreview').src = e.target.result;
                    document.getElementById('coverPreview').style.display = 'block';
                    document.getElementById('coverIcon').style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('status') === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Perubahan Disimpan',
                    confirmButtonColor: '#074776'
                }).then(() => {
                    window.location.href = 'kelola_buku.php';
                });
            } else if(urlParams.get('status') === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: urlParams.get('msg') ? urlParams.get('msg') : 'Terjadi kesalahan.',
                    confirmButtonColor: '#074776'
                });
            }
        }
    </script>
</body>
</html>
