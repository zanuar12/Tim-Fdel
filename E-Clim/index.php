<?php
session_start();
require_once 'connectiondb.php';

$page = isset($_GET['page']) ? htmlspecialchars(strip_tags($_GET['page'])) : 'berita';
$msg = ""; $msg_type = "";

if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    $msg_type = (isset($_GET['status']) && $_GET['status'] == 'success') ? 'success' : 'error'; 
}

$halaman_publik_akses = ['beranda', 'berita', 'baca_berita', 'isi1', 'isi2', 'isi3', 'tentang_kami', 'login', 'daftar', 'lupasandi', 'proses_login', 'proses_daftar'];

if (!isset($_SESSION['user']) && !in_array($page, $halaman_publik_akses)) {
    header("Location: index.php?page=login&status=error&msg=Akses ditolak. Silakan login admin.");
    exit();
}

if (isset($_SESSION['user']) && in_array($page, ['login', 'daftar', 'lupasandi'])) {
    header("Location: index.php?page=kelola_berita");
    exit();
}

// =========================================================================
// 2. LOGIKA BACKEND AUTHENTICATION (CRUD USER)
// =========================================================================
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
    } catch (PDOException $e) { die("FATAL DB ERROR: " . $e->getMessage()); }
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
    } catch (PDOException $e) { die("FATAL DB ERROR: " . $e->getMessage()); }
}

if ($page == 'logout') { session_destroy(); header("Location: index.php?page=berita"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Clim | Edukasi Iklim</title>
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'><path fill='%232F5233' d='M393 414.7C283 524.6 94 475.5 61 310.5c0-12.2-30.4-7.4-28.9 3.3 24 173.4 246 256.9 381.6 121.3 6.9-7.8-12.6-28.4-20.7-20.4zM213.6 306.6c0 4 4.3 7.3 5.5 8.5 3 3 6.1 4.4 8.5 4.4 3.8 0 2.6.2 22.3-19.5 19.6 19.3 19.5 22.3 19.5 5.4 0 18.5-10.4 10.7-18.2L265.6 284l18.2-18.2c6.3-6.8-10.1-21.8-16.2-15.7L249.7 268c-18.6-18.8-18.4-19.5-21.5-19.5-5 0-18 11.7-12.4 17.3L234 284c-18.1 17.9-20.4 19.2-20.4 22.6z'/></svg>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pine: '#2F5233', 'pine-hover': '#1f3822',
                        terracotta: '#B95232', 'off-white': '#F7F9F6',
                        'slate-dark': '#1F2937', 'border-gray': '#E5E7EB'
                    },
                    fontFamily: { sans: ['Poppins', 'sans-serif'], }
                }
            }
        }
    </script>
</head>
<body class="bg-off-white text-slate-dark font-sans antialiased m-0 p-0 overflow-x-hidden flex flex-col md:block">

    <noscript>
        <?php if(!empty($msg)): ?>
            <div class="fixed top-5 right-5 text-white py-3 px-5 rounded shadow-lg z-[9999] <?php echo ($msg_type == 'success') ? 'bg-pine' : 'bg-terracotta'; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>
    </noscript>

    <?php if (in_array($page, ['login', 'daftar', 'lupasandi'])): ?>
        <div class="flex justify-center items-center min-h-screen p-5 bg-gradient-to-br from-off-white to-gray-200">
            
            <?php if ($page == 'login'): ?>
                <div class="bg-white border border-border-gray p-8 rounded-xl shadow-md w-full max-w-[400px]">
                    <div class="text-2xl font-bold text-pine text-center mb-5"><i class="fa-brands fa-envira"></i> E-Clim</div>
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
                    <div class="mb-4"><a href="index.php?page=login" class="text-slate-dark text-sm hover:underline"><i class="fa-solid fa-arrow-left"></i> Kembali</a></div>
                    <h3 class="text-center font-bold text-xl mb-5">Daftar Akun Admin</h3>
                    <form action="index.php?page=proses_daftar" method="POST">
                        <input type="email" name="email_daftar" placeholder="Masukkan alamat email" required class="w-full p-3 mb-4 border border-border-gray rounded-lg bg-off-white text-sm focus:outline-pine">
                        <input type="password" name="pass_daftar" placeholder="Masukkan kata sandi" required class="w-full p-3 mb-4 border border-border-gray rounded-lg bg-off-white text-sm focus:outline-pine">
                        <input type="password" name="pass_confirm" placeholder="Masukkan ulang kata sandi" required class="w-full p-3 mb-4 border border-border-gray rounded-lg bg-off-white text-sm focus:outline-pine">
                        <button type="submit" class="w-full bg-pine hover:bg-pine-hover text-white font-bold py-3 rounded-lg transition-colors">Daftar Sekarang</button>
                    </form>
                </div>
                
            <?php elseif ($page == 'lupasandi'): ?>
                <div class="bg-white border border-border-gray p-8 rounded-xl shadow-md w-full max-w-[400px]">
                    <div class="mb-4"><a href="index.php?page=login" class="text-slate-dark text-sm hover:underline"><i class="fa-solid fa-arrow-left"></i> Kembali ke Login</a></div>
                    <h3 class="text-center font-bold text-xl mb-2">Pemulihan Sandi</h3>
                    <p class="text-center text-sm text-gray-500 mb-5">Masukkan email Anda untuk menerima tautan pemulihan.</p>
                    
                    <form action="#" method="POST" onsubmit="event.preventDefault(); Swal.fire('Akses Ditolak', 'Sistem sedang dalam mode lokal (MVP). Fitur pengiriman email token SMTP dinonaktifkan demi keamanan. Hubungi SuperAdmin.', 'warning');">
                        <input type="email" name="email_reset" placeholder="Alamat email terdaftar" required class="w-full p-3 mb-4 border border-border-gray rounded-lg bg-off-white text-sm focus:outline-pine">
                        <button type="submit" class="w-full bg-terracotta hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-colors"><i class="fa-solid fa-envelope mr-2"></i> Kirim Tautan Reset</button>
                    </form>
                    
                    <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg text-xs text-orange-800 leading-relaxed text-justify">
                        <strong><i class="fa-solid fa-triangle-exclamation mr-1"></i> Mode Pengembang (Localhost)</strong><br>
                        Pengiriman email diblokir oleh sistem lokal. Jika Anda kehilangan akses, silakan daftarkan akun baru atau perbarui <i>database</i> secara manual.
                    </div>
                </div>
            <?php endif; ?>
        </div>

    <?php else: ?>
        
        <div class="md:hidden bg-white border-b border-border-gray p-4 flex justify-between items-center sticky top-0 z-[1001] shadow-sm">
            <div class="font-bold text-lg text-pine"><i class="fa-brands fa-envira"></i> E-Clim</div>
            <button id="mobile-menu-btn" class="text-2xl text-slate-dark focus:outline-none"><i class="fa-solid fa-bars"></i></button>
        </div>

        <div class="flex flex-1 min-h-[calc(100vh-60px)] md:min-h-screen relative overflow-hidden">
            
            <aside id="sidebar-menu" class="fixed md:fixed top-[60px] md:top-0 left-0 h-[calc(100vh-60px)] md:h-screen w-64 md:w-[70px] md:hover:w-[250px] -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out bg-white border-r border-border-gray overflow-y-auto md:overflow-hidden z-[1000] flex flex-col md:whitespace-nowrap group shadow-lg md:shadow-none">
                <div class="hidden md:flex p-5 items-center gap-4 border-b border-border-gray text-pine text-lg font-bold">
                    <i class="fa-brands fa-envira text-2xl min-w-[30px] text-center"></i>
                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">E-Clim</span>
                </div>
                
                <nav class="flex-1 mt-2 md:mt-5">
                    <ul class="list-none flex flex-col gap-1 px-2">
                        <li class="<?php echo ($page == 'berita' || $page == 'baca_berita') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                            <a href="index.php?page=berita" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-newspaper text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Berita Iklim</span></a>
                        </li>
                        <li class="<?php echo ($page == 'isi1') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                            <a href="index.php?page=isi1" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-smog text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Penyebab</span></a>
                        </li>
                        <li class="<?php echo ($page == 'isi2') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                            <a href="index.php?page=isi2" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-temperature-arrow-up text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Dampak Global</span></a>
                        </li>
                        <li class="<?php echo ($page == 'isi3') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                            <a href="index.php?page=isi3" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-hand-holding-hand text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Solusi Aksi</span></a>
                        </li>
                        <li class="<?php echo ($page == 'tentang_kami') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded mt-4 border-t border-gray-100 pt-2">
                            <a href="index.php?page=tentang_kami" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-circle-info text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Tentang Kami</span></a>
                        </li>
                    </ul>
                </nav>
                
                <div class="p-2 border-t border-border-gray">
                    <ul class="list-none flex flex-col gap-1">
                        <?php if (isset($_SESSION['user'])): ?>
                            <li class="<?php echo ($page == 'kelola_berita' || $page == 'edit_berita' || $page == 'tambah_berita') ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                                <a href="index.php?page=kelola_berita" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-table-list text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Kelola Berita</span></a>
                            </li>
                            <li class="<?php echo (in_array($page, ['akun', 'ubah_email', 'ubah_sandi'])) ? 'bg-off-white text-pine font-semibold rounded' : 'text-gray-500'; ?> hover:bg-off-white hover:text-pine rounded">
                                <a href="index.php?page=akun" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-user-gear text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Akun Admin</span></a>
                            </li>
                            <li class="text-terracotta hover:bg-red-50 rounded">
                                <a href="index.php?page=logout" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-right-from-bracket text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Keluar</span></a>
                            </li>
                        <?php else: ?>
                            <li class="text-pine hover:bg-green-50 rounded">
                                <a href="index.php?page=login" class="flex items-center gap-4 p-3 text-sm transition-colors"><i class="fa-solid fa-right-to-bracket text-base min-w-[30px] text-center"></i><span class="md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">Login Admin</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </aside>

            <main class="flex-1 w-full md:ml-[70px] p-6 md:p-10 transition-all duration-300 ease-in-out md:w-[calc(100%-70px)]">
                <?php 
                $public_pages = ['beranda' => 'beranda.php', 'berita' => 'berita.php', 'baca_berita' => 'baca_berita.php', 'isi1' => 'edukasi_penyebab.php', 'isi2' => 'edukasi_dampak.php', 'isi3' => 'edukasi_solusi.php', 'tentang_kami' => 'tentang_kami.php'];
                $protected_pages = ['tambah_berita' => 'tambah_berita.php', 'kelola_berita' => 'kelola_berita.php', 'edit_berita' => 'edit_berita.php', 'akun' => 'akuninfo.php', 'ubah_email' => 'akuninfo.php', 'ubah_sandi' => 'akuninfo.php'];

                if (array_key_exists($page, $public_pages)) {
                    if (file_exists($public_pages[$page])) { include $public_pages[$page]; } else { echo "<div class='bg-white p-6 rounded-xl border border-border-gray'><h3>File {$public_pages[$page]} Belum Dibuat</h3></div>"; }
                } elseif (array_key_exists($page, $protected_pages)) {
                    if (!isset($_SESSION['user'])) { echo "<script>window.location.href='index.php?page=login&status=error&msg=Akses Ilegal.';</script>"; exit(); } 
                    else { if (file_exists($protected_pages[$page])) { include $protected_pages[$page]; } else { echo "<div class='bg-white p-6 rounded-xl border border-border-gray'><h3>File {$protected_pages[$page]} Belum Dibuat</h3></div>"; } }
                } else { echo "<div class='bg-white p-6 rounded-xl border border-border-gray text-center'><h3 class='text-2xl font-bold text-terracotta mb-2'>404 Not Found</h3><p>Halaman tidak ditemukan.</p></div>"; }
                ?>
            </main>

            <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-[999] hidden md:hidden"></div>
        </div>
    <?php endif; ?>

    <script>
        <?php if(!empty($msg)): ?>
            Swal.fire({
                icon: '<?php echo $msg_type; ?>',
                title: '<?php echo ($msg_type == "success") ? "Berhasil!" : "Informasi"; ?>',
                text: '<?php echo $msg; ?>',
                confirmButtonColor: '<?php echo ($msg_type == "success") ? "#2F5233" : "#B95232"; ?>'
            });
        <?php endif; ?>

        const menuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar-menu');
        const overlay = document.getElementById('mobile-overlay');

        if(menuBtn && sidebar && overlay) {
            function toggleMenu() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
            menuBtn.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', toggleMenu);
        }
    </script>
</body>
</html>
