<?php
session_start();
require_once 'connectiondb.php';

$page = isset($_GET['page']) ? htmlspecialchars(strip_tags($_GET['page'])) : 'berita';
$msg = ""; $msg_type = "";

if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    $msg_type = (isset($_GET['status']) && $_GET['status'] == 'success') ? 'bg-pine' : 'bg-terracotta'; 
}

// ==========================================
// LOGIKA BACKEND AUTHENTICATION
// ==========================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $page == 'proses_daftar') {
    $email = filter_var($_POST['email_daftar'], FILTER_SANITIZE_EMAIL);
    $pass  = $_POST['pass_daftar'];
    $conf  = $_POST['pass_confirm'];
    $username = explode('@', $email)[0]; 

    if ($pass !== $conf) { header("Location: index.php?page=daftar&status=error&msg=Konfirmasi sandi tidak cocok!"); exit(); }
    $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM userr WHERE useremail = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) { header("Location: index.php?page=daftar&status=error&msg=Email sudah terdaftar!"); exit(); }
        
        $ins = $pdo->prepare("INSERT INTO userr (username, useremail, userpass) VALUES (?, ?, ?)");
        $ins->execute([$username, $email, $hashed_pass]);
        header("Location: index.php?page=login&status=success&msg=Pendaftaran berhasil! Silakan login."); exit();
    } catch (PDOException $e) { header("Location: index.php?page=daftar&status=error&msg=Error sistem."); exit(); }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $page == 'proses_login') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass  = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM userr WHERE useremail = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['userpass'])) {
            session_regenerate_id(true); 
            $_SESSION['user'] = ['id' => $user['userid'], 'name' => $user['username'], 'email' => $user['useremail']];
            header("Location: index.php?page=kelola_berita"); 
            exit();
        } else { header("Location: index.php?page=login&status=error&msg=Email atau Password salah!"); exit(); }
    } catch (PDOException $e) { header("Location: index.php?page=login&status=error&msg=Error sistem."); exit(); }
}

if ($page == 'logout') { session_destroy(); header("Location: index.php?page=berita"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clim</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path fill='%231F2937' d='M64 32C28.7 32 0 60.7 0 96v320c0 35.3 28.7 64 64 64h320c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm90.5 138.5c6.2-6.2 16.4-6.2 22.6 0l80 80c6.2 6.2 6.2 16.4 0 22.6l-80 80c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6L223 256l-68.5-68.5c-6.2-6.2-6.2-16.4 0-22.6zM320 320h-64c-8.8 0-16-7.2-16-16s7.2-16 16-16h64c8.8 0 16 7.2 16 16s-7.2 16-16 16z'/></svg>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pine: '#2F5233',
                        'pine-hover': '#1f3822',
                        terracotta: '#B95232',
                        'off-white': '#F7F9F6',
                        'slate-dark': '#1F2937',
                        'border-gray': '#E5E7EB'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-off-white text-slate-dark font-sans antialiased m-0 p-0 overflow-x-hidden">

    <?php if(!empty($msg)): ?>
        <div class="fixed top-5 right-5 text-white py-3 px-5 rounded shadow-lg z-[9999] <?php echo $msg_type; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <?php if (in_array($page, ['login', 'daftar', 'lupasandi'])): ?>
        <div class="flex justify-center items-center min-h-screen p-5 bg-gradient-to-br from-off-white to-gray-200">
            <?php if ($page == 'login'): ?>
                <div class="bg-white border border-border-gray p-8 rounded-xl shadow-md w-full max-w-[400px]">
                    <div class="text-2xl font-bold text-pine text-center mb-5"><i class="fa-solid fa-leaf"></i> E-Clim</div>
                    <h3 class="text-center font-semibold mb-1 text-xl">Akses Admin</h3>
                    <p class="text-center text-sm text-gray-500 mb-6"><a href="index.php" class="text-pine hover:underline">← Kembali ke Beranda</a></p>
                    <form action="index.php?page=proses_login" method="POST">
                        <input type="email" name="email" placeholder="Alamat Email" required class="w-full p-3 mb-4 border border-border-gray rounded-lg bg-off-white text-sm focus:outline-pine">
                        <input type="password" name="password" placeholder="Kata Sandi" required class="w-full p-3 mb-4 border border-border-gray rounded-lg bg-off-white text-sm focus:outline-pine">
                        <button type="submit" class="w-full bg-pine hover:bg-pine-hover text-white font-bold py-3 rounded-lg transition-colors mt-2">Login Admin</button>
                    </form>
                    <div class="flex justify-between mt-4 text-xs text-pine font-medium">
                        <a href="index.php?page=daftar" class="hover:underline">Buat Akun Baru</a>
                        <a href="index.php?page=lupasandi" class="hover:underline">Lupa Sandi?</a>
                    </div>
                </div>
            <?php elseif ($page == 'daftar'): ?>
                <div class="bg-white border border-border-gray p-8 rounded-xl shadow-md w-full max-w-[400px]">
                    </div>
                
            <?php elseif ($page == 'lupasandi'): ?>
                <div class="bg-white border border-border-gray p-8 rounded-xl shadow-md w-full max-w-[400px]">
                    <div class="mb-4"><a href="index.php?page=login" class="text-slate-dark text-sm hover:underline"><i class="fa-solid fa-arrow-left"></i> Kembali ke Login</a></div>
                    <h3 class="text-center font-bold text-xl mb-2">Pemulihan Sandi</h3>
                    <p class="text-center text-sm text-gray-500 mb-5">Masukkan email Anda untuk menerima tautan pemulihan akun.</p>
                    
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Sistem sedang dalam mode lokal (MVP). Fitur pengiriman email token SMTP dinonaktifkan demi keamanan. Hubungi SuperAdmin atau edit hash secara manual di phpMyAdmin.');">
                        <input type="email" name="email_reset" placeholder="Masukkan alamat email terdaftar" required class="w-full p-3 mb-4 border border-border-gray rounded-lg bg-off-white text-sm focus:outline-pine">
                        <button type="submit" class="w-full bg-terracotta hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-colors"><i class="fa-solid fa-envelope mr-2"></i> Kirim Tautan Reset</button>
                    </form>
                    
                    <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg text-xs text-orange-800 leading-relaxed text-justify">
                        <strong><i class="fa-solid fa-triangle-exclamation mr-1"></i> Mode Pengembang (Localhost)</strong><br>
                        Pengiriman email otomatis diblokir oleh sistem lokal. Jika Anda kehilangan akses admin saat pengujian, silakan daftarkan akun baru atau kosongkan tabel <code>userr</code> di *database*.
                    </div>
                </div>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <div class="flex min-h-screen relative">
            
            <aside class="fixed top-0 left-0 h-screen w-[70px] hover:w-[250px] transition-all duration-300 ease-in-out bg-white border-r border-border-gray overflow-hidden z-[1000] flex flex-col whitespace-nowrap group">
                <div class="p-5 flex items-center gap-4 border-b border-border-gray text-pine text-lg font-bold">
                    <i class="fa-solid fa-leaf text-2xl min-w-[30px] text-center"></i>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">E-Clim</span>
                </div>
                <nav class="flex-1 mt-5">
                    <ul class="list-none flex flex-col gap-1 px-2">
                        <li class="<?php echo ($page == 'berita' || $page == 'baca_berita') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                            <a href="index.php?page=berita" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-newspaper text-base min-w-[30px] text-center"></i><span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Berita Iklim</span></a>
                        </li>
                        <li class="<?php echo ($page == 'isi1') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                            <a href="index.php?page=isi1" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-smog text-base min-w-[30px] text-center"></i><span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Penyebab</span></a>
                        </li>
                        <li class="<?php echo ($page == 'isi2') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                            <a href="index.php?page=isi2" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-temperature-arrow-up text-base min-w-[30px] text-center"></i><span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Dampak Global</span></a>
                        </li>
                        <li class="<?php echo ($page == 'isi3') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                            <a href="index.php?page=isi3" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-hand-holding-hand text-base min-w-[30px] text-center"></i><span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Solusi Aksi</span></a>
                        </li>
                    </ul>
                </nav>
                <div class="p-2 border-t border-border-gray">
                    <ul class="list-none flex flex-col gap-1">
                        <?php if (isset($_SESSION['user'])): ?>
                            <li class="<?php echo ($page == 'kelola_berita' || $page == 'edit_berita' || $page == 'tambah_berita') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                                <a href="index.php?page=kelola_berita" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-table-list text-base min-w-[30px] text-center"></i><span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Kelola Berita</span></a>
                            </li>
                            <li class="<?php echo (in_array($page, ['akun', 'ubah_email', 'ubah_sandi'])) ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                                <a href="index.php?page=akun" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-user-gear text-base min-w-[30px] text-center"></i><span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Akun Admin</span></a>
                            </li>
                            <li class="text-terracotta hover:bg-red-50 rounded">
                                <a href="index.php?page=logout" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-right-from-bracket text-base min-w-[30px] text-center"></i><span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Keluar</span></a>
                            </li>
                        <?php else: ?>
                            <li class="text-pine hover:bg-green-50 rounded">
                                <a href="index.php?page=login" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-right-to-bracket text-base min-w-[30px] text-center"></i><span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">Login (Admin)</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </aside>

            <main class="flex-1 ml-[70px] p-6 md:p-10 transition-all duration-300 ease-in-out w-[calc(100%-70px)]">
                <?php 
                $public_pages = ['beranda' => 'beranda.php', 'berita' => 'berita.php', 'baca_berita' => 'baca_berita.php', 'isi1' => 'edukasi_penyebab.php', 'isi2' => 'edukasi_dampak.php', 'isi3' => 'edukasi_solusi.php'];
                $protected_pages = [
                    'tambah_berita' => 'tambah_berita.php',
                    'kelola_berita' => 'kelola_berita.php', 
                    'edit_berita'   => 'edit_berita.php',   
                    'akun'          => 'akuninfo.php',
                    'ubah_email'    => 'akuninfo.php',
                    'ubah_sandi'    => 'akuninfo.php'
                ];

                if (array_key_exists($page, $public_pages)) {
                    if (file_exists($public_pages[$page])) { include $public_pages[$page]; } else { echo "<div class='bg-white p-6 rounded-xl border border-border-gray'><h3>File {$public_pages[$page]} Belum Dibuat</h3></div>"; }
                } elseif (array_key_exists($page, $protected_pages)) {
                    if (!isset($_SESSION['user'])) {
                        echo "<script>window.location.href='index.php?page=login&status=error&msg=Silakan login terlebih dahulu.';</script>"; exit();
                    } else {
                        if (file_exists($protected_pages[$page])) { include $protected_pages[$page]; } else { echo "<div class='bg-white p-6 rounded-xl border border-border-gray'><h3>File {$protected_pages[$page]} Belum Dibuat</h3></div>"; }
                    }
                } else { echo "<div class='bg-white p-6 rounded-xl border border-border-gray'><h3>Halaman Tidak Ditemukan (404)</h3></div>"; }
                ?>
            </main>
        </div>
    <?php endif; ?>
</body>
</html>
