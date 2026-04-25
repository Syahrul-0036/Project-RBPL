<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="mobile-container">
        
        <div class="content center" style="cursor: pointer;" onclick="window.location.href='../index.php'">
            <svg class="illustration illustration-splash" viewBox="0 0 250 150" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path d="M20 70 L230 70 L210 120 L10 120 Z" fill="#60A5FA" stroke="#074776" stroke-width="2"/>
               <path d="M230 70 L210 120 L210 130 L230 80 Z" fill="#3B82F6" stroke="#074776" stroke-width="2"/>
               <path d="M10 120 L210 120 L210 130 L10 130 Z" fill="#2563EB" stroke="#074776" stroke-width="2"/>
               <path d="M30 40 L240 40 L220 90 L20 90 Z" fill="#93C5FD" stroke="#074776" stroke-width="2"/>
            </svg>
            <div style="margin-top: 20px; color: #074776; font-weight: 600; font-size: 14px;">Memuat Perpustakaan...</div>
        </div>
    </div>

    <script>
        // Otomatis pindah ke Beranda setelah 3 detik
        setTimeout(function() {
            window.location.href = '../index.php';
        }, 3000);
    </script>
</body>
</html>
