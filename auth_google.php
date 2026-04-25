<?php
session_start();
require '../koneksi/koneksi.php';
require 'config_social.php';

// Jika tidak ada kode dari Google, arahkan ke Google Login
if (!isset($_GET['code'])) {
    $params = [
        'client_id' => GOOGLE_CLIENT_ID,
        'redirect_uri' => GOOGLE_REDIRECT_URL,
        'response_type' => 'code',
        'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
        'access_type' => 'offline',
        'prompt' => 'select_account'
    ];
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit;
}

// Jika ada kode, tukarkan dengan Access Token
$code = $_GET['code'];
$token_url = 'https://oauth2.googleapis.com/token';
$post_data = [
    'code' => $code,
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URL,
    'grant_type' => 'authorization_code'
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
$response = curl_exec($ch);
$data = json_decode($response, true);

if (isset($data['access_token'])) {
    $access_token = $data['access_token'];
    
    // Ambil data profil user
    $user_info_url = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $access_token;
    $user_info_response = file_get_contents($user_info_url);
    $user_info = json_decode($user_info_response, true);
    
    if (isset($user_info['id'])) {
        $google_id = $user_info['id'];
        $email = $user_info['email'];
        $name = $user_info['name'];
        
        // Cek apakah user sudah ada di database
        $check_user = mysqli_query($koneksi, "SELECT * FROM users WHERE social_id = '$google_id' AND social_provider = 'google'");
        
        if (mysqli_num_rows($check_user) > 0) {
            $user = mysqli_fetch_assoc($check_user);
        } else {
            // Jika belum ada, buat user baru (sebagai pelanggan)
            $username = strtolower(explode(' ', $name)[0]) . rand(100, 999);
            // Password random karena login via google
            $dummy_pass = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
            
            mysqli_query($koneksi, "INSERT INTO users (nama, username, email, password, role, social_id, social_provider) 
                                   VALUES ('$name', '$username', '$email', '$dummy_pass', 'pelanggan', '$google_id', 'google')");
            
            $id_new = mysqli_insert_id($koneksi);
            $user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_new"));
        }
        
        // Set Session
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        header("Location: ../index.php");
        exit;
    }
}

echo "Login Google Gagal. Pastikan Client ID dan Client Secret sudah benar.";
?>
