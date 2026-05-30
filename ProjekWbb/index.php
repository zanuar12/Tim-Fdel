<?php
// Memulai session untuk mensimulasikan perpindahan halaman secara dinamis
session_start();

// Mengatur halaman default yang aktif berdasarkan parameter URL (?page=...)
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Simulasi data user dummy di memori session (jika belum ada)
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => '11001100',
        'email' => 'wDWD@gmail.com'
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Projek Mangkrak</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php if (in_array($page, ['login', 'daftar', 'lupasandi', 'lupasandierror'])): ?>
        <div class="auth-container">
            <?php 
                if ($page == 'login') include 'pages/login.php';
                if ($page == 'daftar') include 'pages/daftar.php';
                if ($page == 'lupasandi') include 'pages/lupasandi.php';
                if ($page == 'lupasandierror') include 'pages/lupasandi.php'; // memuat file sama dengan flag error
            ?>
        </div>

    <?php else: ?>
        <div class="app-container">
            
            <?php include 'sidebar.php'; ?>

            <main class="main-content">
                
                <?php 
                    switch($page) {
                        case 'beranda':
                        case 'isi1':
                        case 'isi2':
                        case 'isi3':
                            // Placeholder halaman kosong silang (X) pada wireframe baris kedua
                            echo "<div class='wireframe-placeholder-box'>
                                    <h2>Halaman: ".ucfirst($page)."</h2>
                                    <div class='cross-lines'></div>
                                  </div>";
                            break;
                            
                        case 'berita':
                            include 'pages/berita.php';
                            break;
                            
                        case 'akun':
                        case 'ubah_email':
                        case 'ubah_sandi':
                            // Wrapper khusus untuk modul Manajemen Akun (Box Biru & Baris Bawah)
                            include 'pages/akuninfo.php'; 
                            break;
                            
                        default:
                            include 'pages/berita.php';
                    }
                ?>
            </main>
        </div>
    <?php endif; ?>

</body>
</html>
