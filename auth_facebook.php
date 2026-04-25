<?php
session_start();
require '../koneksi/koneksi.php';
require 'config_social.php';

// Jika tidak ada kode dari Facebook, arahkan ke Facebook Login
if (!isset($_GET['code'])) {
    $params = [
        'client_id' => FB_APP_ID,
        'redirect_uri' => FB_REDIRECT_URL,
        'scope' => 'email,public_profile',
    ];
    header('Location: https://www.facebook.com/v12.0/dialog/oauth?' . http_build_query($params));
    exit;
}

// Jika ada kode, tukarkan dengan Access Token
$code = $_GET['code'];
$token_url = "https://graph.facebook.com/v12.0/oauth/access_token";
$params = [
    'client_id' => FB_APP_ID,
    'client_secret' => FB_APP_SECRET,
    'redirect_uri' => FB_REDIRECT_URL,
    'code' => $code
];

$ch = curl_init($token_url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$data = json_decode($response, true);

if (isset($data['access_token'])) {
    $access_token = $data['access_token'];
    
    // Ambil data profil user
    $user_info_url = "https://graph.facebook.com/me?fields=id,name,email&access_token=" . $access_token;
    $user_info_response = file_get_contents($user_info_url);
    $user_info = json_decode($user_info_response, true);
    
    if (isset($user_info['id'])) {
        $fb_id = $user_info['id'];
        $email = isset($user_info['email']) ? $user_info['email'] : $fb_id . "@facebook.com";
        $name = $user_info['name'];
        
        // Cek apakah user sudah ada di database
        $check_user = mysqli_query($koneksi, "SELECT * FROM users WHERE social_id = '$fb_id' AND social_provider = 'facebook'");
        
        if (mysqli_num_rows($check_user) > 0) {
            $user = mysqli_fetch_assoc($check_user);
        } else {
            // Jika belum ada, buat user baru
            $username = strtolower(str_replace(' ', '', $name)) . rand(100, 999);
            $dummy_pass = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
            
            mysqli_query($koneksi, "INSERT INTO users (nama, username, email, password, role, social_id, social_provider) 
                                   VALUES ('$name', '$username', '$email', '$dummy_pass', 'pelanggan', '$fb_id', 'facebook')");
            
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

echo "Login Facebook Gagal. Pastikan App ID dan App Secret sudah benar.";
?>
