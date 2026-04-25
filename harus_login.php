<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harus Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .illustration-login {
            max-width: 250px;
            margin: 20px auto;
            display: block;
        }
        .text-center {
            text-align: center;
            font-size: 13px;
            color: #666;
            margin-bottom: 30px;
            padding: 0 10px;
            line-height: 1.5;
        }
        .back-btn {
            color: black; 
            text-decoration: none; 
            margin-bottom: 0px; 
            display: inline-block;
            margin-top: -10px;
        }
    </style>
</head>
<body>
    <div class="mobile-container">
        <div class="content">
            <a href="../index.php" class="back-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            </a>
            
            <svg class="illustration illustration-login" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Laptop Background -->
                <rect x="80" y="40" width="180" height="110" rx="8" fill="#E5F0FA" stroke="#074776" stroke-width="3"/>
                <polygon points="60,150 280,150 260,165 80,165" fill="#074776"/>
                <rect x="120" y="80" width="100" height="8" rx="4" fill="#a7c5df"/>
                <rect x="120" y="100" width="60" height="8" rx="4" fill="#a7c5df"/>
                <circle cx="170" cy="60" r="12" fill="#074776"/>
                <!-- Person in red suit -->
                <path d="M90 160 C 90 90, 40 90, 40 160 Z" fill="#dc2626"/>
                <!-- Person head -->
                <circle cx="65" cy="70" r="22" fill="#fca5a5"/>
                <!-- Tie/details -->
                <path d="M60 92 L70 92 L65 130 Z" fill="#991b1b"/>
                <path d="M65 92 L105 130 L95 140 Z" fill="#ef4444"/>
            </svg>
            
            <div class="text-center">
                Untuk Meminjam buku, kamu diharuskan untuk<br>masuk atau daftar akun terlebih dahulu
            </div>
            
            <a href="register.php" class="btn btn-primary" style="margin-bottom: 10px;">DAFTAR</a>
            
            <div class="separator" style="margin: 10px 0;">ATAU</div>
            
            <a href="login_role.php" class="btn btn-primary">MASUK</a>
        </div>
    </div>
</body>
</html>
