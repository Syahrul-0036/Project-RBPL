<?php
session_start();

// Ensure user is logged in and is a manager or employee
if (!isset($_SESSION['logged_in']) || ($_SESSION['role'] !== 'manajer' && $_SESSION['role'] !== 'karyawan')) {
    // If logged in but not manager/employee, go to profil, else go to harus_login
    if (isset($_SESSION['logged_in'])) {
        header("Location: profil.php");
    } else {
        header("Location: harus_login.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku Baru</title>
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
            display: none;
        }
        .cover-upload-icon {
            z-index: 2;
            color: var(--text-color);
            text-align: center;
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
            margin-right: 24px; /* to balance the flex */
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
        .optional-add {
            background: white;
            color: var(--text-color);
            font-size: 10px;
            padding: 8px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .btn-submit {
            margin-top: auto;
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
                <div class="page-title">Tambah Buku Baru</div>
            </div>

            <form action="../proses/tambah_buku_handler.php" method="POST" enctype="multipart/form-data" id="formTambahBuku">
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-col-left">
                            <div class="cover-upload" onclick="document.getElementById('sampul').click()">
                                <img id="coverPreview" src="" alt="Preview">
                                <div class="cover-upload-icon" id="coverIcon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle><line x1="12" y1="11" x2="12" y2="15"></line><line x1="10" y1="13" x2="14" y2="13"></line></svg>
                                    <div class="cover-upload-text">Unggah Sampul</div>
                                </div>
                            </div>
                            <input type="file" id="sampul" name="sampul" accept="image/*" style="display: none;" onchange="previewImage(this)" required>
                        </div>
                        <div class="form-col-right">
                            <div class="form-group">
                                <label for="judul">Judul Buku</label>
                                <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul buku..." required>
                            </div>
                            <div class="form-group">
                                <label for="penulis">Penulis</label>
                                <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Masukkan nama penulis..." required>
                            </div>
                            <div class="form-group">
                                <label for="penerbit">Penerbit</label>
                                <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Masukkan nama penerbit..." required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="jumlah">Jumlah Buku</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="0" min="0" required>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="lokasi_rak">Lokasi Rak</label>
                                <input type="text" class="form-control" id="lokasi_rak" name="lokasi_rak" placeholder="Masukkan lokasi rak..." required>
                            </div>
                        </div>
                    </div>

                    <div class="optional-add">
                        <span>Detail Lainnya (Opsional)</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">SIMPAN BUKU</button>
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

        // Show SweetAlert based on URL parameters
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('status') === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Buku telah berhasil ditambahkan',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#074776'
                }).then((result) => {
                    // Redirect to kelola_buku after success
                    window.location.href = 'kelola_buku.php';
                });
            } else if(urlParams.get('status') === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menambah Buku',
                    text: urlParams.get('msg') ? urlParams.get('msg') : 'Terjadi kesalahan.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#074776'
                });
            }
        }
    </script>
</body>
</html>
