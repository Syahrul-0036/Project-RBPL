<?php
session_start();
require '../koneksi/koneksi.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: harus_login.php");
    exit;
}

$id_user = $_SESSION['user_id'];
$status_msg = '';
$status_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    
    $update_query = "UPDATE users SET nama = '$nama', username = '$username'";
    
    // Handle photo upload
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['foto_profil']['tmp_name'];
        $file_name = $_FILES['foto_profil']['name'];
        $file_size = $_FILES['foto_profil']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_exts = array('jpg', 'jpeg', 'png', 'webp');
        if (in_array($file_ext, $allowed_exts) && $file_size <= 10000000) {
            $new_file_name = "prof_" . $id_user . "_" . time() . "." . $file_ext;
            $upload_dir = '../assets/uploads/profile/';
            $upload_path = $upload_dir . $new_file_name;
            $db_path = 'assets/uploads/profile/' . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Delete old photo
                $old_q = mysqli_query($koneksi, "SELECT foto_profil FROM users WHERE id_user = $id_user");
                if ($old_q) {
                    $old_data = mysqli_fetch_assoc($old_q);
                    if (!empty($old_data['foto_profil']) && file_exists('../' . $old_data['foto_profil'])) {
                        unlink('../' . $old_data['foto_profil']);
                    }
                }
                $update_query .= ", foto_profil = '$db_path'";
            }
        } else {
            $status_msg = 'Format foto tidak valid atau ukuran > 10MB.';
            $status_type = 'error';
        }
    }
    
    $update_query .= " WHERE id_user = $id_user";
    
    if (empty($status_msg)) {
        if (mysqli_query($koneksi, $update_query)) {
            $_SESSION['username'] = $username;
            $status_msg = 'Profil berhasil diperbarui!';
            $status_type = 'success';
        } else {
            $status_msg = 'Gagal memperbarui profil.';
            $status_type = 'error';
        }
    }
}

$user_query = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_user");
$user_data = mysqli_fetch_assoc($user_query);

$avatar_initial = strtoupper(substr($user_data['username'], 0, 1));
$has_foto = !empty($user_data['foto_profil']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Profil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .profile-photo-edit {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 700;
            margin: 0 auto 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .profile-photo-edit img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .edit-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 30px;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="mobile-container pb-70">
        <div class="content top-spacing">
            <div class="top-header" style="display:flex; align-items:center; margin-bottom:30px;">
                <a href="profil.php" class="back-btn" style="margin-right:15px; color:#333;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
                <h1 class="title" style="margin:0; font-size:20px;">Ubah Profil</h1>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="profile-photo-edit" onclick="document.getElementById('foto_profil').click()">
                    <?php if($has_foto): ?>
                        <img id="previewImage" src="../<?= htmlspecialchars($user_data['foto_profil']) ?>" alt="Profile">
                    <?php else: ?>
                        <span id="initialText"><?= $avatar_initial ?></span>
                        <img id="previewImage" src="#" style="display:none;" alt="Profile">
                    <?php endif; ?>
                    <div class="edit-overlay">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                    </div>
                </div>
                <input type="file" id="foto_profil" name="foto_profil" style="display:none;" accept="image/jpeg, image/png, image/webp" onchange="previewFile()">
                <div style="text-align:center; font-size:11px; color:#888; margin-bottom:30px;">Klik foto untuk mengubah (Maks 2MB)</div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($user_data['nama']) ?>" placeholder="Masukan Nama Lengkap">
                </div>
                
                <div class="form-group mb-4">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user_data['username']) ?>" placeholder="Masukan Username" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="margin-top:20px;">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        function previewFile() {
            const file = document.getElementById('foto_profil').files[0];
            const preview = document.getElementById('previewImage');
            const initial = document.getElementById('initialText');
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
                preview.style.display = "block";
                if(initial) initial.style.display = "none";
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        <?php if($status_msg != ''): ?>
        Swal.fire({
            icon: '<?= $status_type ?>',
            title: '<?= $status_type == 'success' ? 'Berhasil' : 'Gagal' ?>',
            text: '<?= $status_msg ?>',
            confirmButtonColor: '#074776'
        }).then(() => {
            <?php if($status_type == 'success'): ?>
            window.location.href = 'profil.php';
            <?php endif; ?>
        });
        <?php endif; ?>
    </script>
</body>
</html>
