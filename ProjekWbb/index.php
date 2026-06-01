<?php
session_start();
require_once 'connectiondb.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$msg = ""; $msg_type = ""; // Mengatur notifikasi sistem

// ==========================================
// 1. LOGIKA BACKEND (PROSES DATABASE)
// ==========================================

// PROSES DAFTAR AKUN (CREATE)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $page == 'proses_daftar') {
    $email = $_POST['email_daftar'];
    $pass  = $_POST['pass_daftar'];
    $conf  = $_POST['pass_confirm'];
    $username = explode('@', $email)[0]; // Mengambil username otomatis dari email

    if ($pass !== $conf) {
        header("Location: index.php?page=daftar&status=error&msg=Konfirmasi kata sandi tidak cocok!");
        exit();
    }
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM userr WHERE useremail = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            header("Location: index.php?page=daftar&status=error&msg=Email sudah terdaftar!");
            exit();
        }
        // Insert user baru ke database
        $ins = $pdo->prepare("INSERT INTO userr (username, useremail, userpass) VALUES (?, ?, ?)");
        $ins->execute([$username, $email, $pass]);
        header("Location: index.php?page=login&status=success&msg=Pendaftaran berhasil! Silakan login.");
        exit();
    } catch (PDOException $e) {
        header("Location: index.php?page=daftar&status=error&msg=Error: " . $e->getMessage());
        exit();
    }
}

// PROSES LOGIN AKUN (READ)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $page == 'proses_login') {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM userr WHERE useremail = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $pass == $user['userpass']) {
            $_SESSION['user'] = [
                'id'    => $user['userid'],
                'name'  => $user['username'],
                'email' => $user['useremail']
            ];
            header("Location: index.php?page=berita");
            exit();
        } else {
            header("Location: index.php?page=login&status=error&msg=Email atau Password salah!");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: index.php?page=login&status=error&msg=Error: " . $e->getMessage());
        exit();
    }
}
// PROSES LUPA KATA SANDI (UPDATE)
// LOGIKA PROSES LUPA KATA SANDI (DITEMPATKAN DI BAGIAN ATAS INDEX.PHP)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $page == 'proses_lupa_sandi') {
    $email        = $_POST['email'];
    $pass_baru    = $_POST['pass_baru'];
    $pass_confirm = $_POST['pass_confirm'];

    // 1. Validasi: Apakah input password pertama dan kedua sama?
    if ($pass_baru !== $pass_confirm) {
        // Jika tidak sama, lempar ke halaman error
        header("Location: index.php?page=lupa_sandi_error");
        exit();
    }

    try {
        // 2. Cek apakah email ini benar-benar ada di database tabel userr
        $stmt_cek = $pdo->prepare("SELECT COUNT(*) FROM userr WHERE useremail = ?");
        $stmt_cek->execute([$email]);
        $email_ditemukan = $stmt_cek->fetchColumn();

        if ($email_ditemukan > 0) {
            // 3. JALANKAN UPDATE: Ubah kolom userpass berdasarkan useremail
            $stmt_update = $pdo->prepare("UPDATE userr SET userpass = ? WHERE useremail = ?");
            $stmt_update->execute([$pass_baru, $email]);

            // Jika sukses merubah database, kembali ke login dengan notifikasi hijau
            header("Location: index.php?page=login&status=success&msg=Kata sandi berhasil diperbarui!");
            exit();
        } else {
            // Jika email tidak ditemukan di database, tampilkan modal error wireframe
            header("Location: index.php?page=lupa_sandi_error");
            exit();
        }

    } catch (PDOException $e) {
        // Jika query gagal karena salah nama kolom/tabel, hentikan program dan tampilkan pesan aslinya
        die("Gagal memperbarui database! Pesan Error: " . $e->getMessage());
    }
}

// PROSES UBAH EMAIL (UPDATE)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $page == 'proses_ubah_email') {
    if (!isset($_SESSION['user'])) header("Location: index.php?page=login");
    
    $email_lama = $_POST['email_lama'];
    $email_baru = $_POST['email_baru'];
    $password   = $_POST['password'];
    $userid     = $_SESSION['user']['id'];

    try {
        // Validasi password saat ini sebelum merubah data
        $stmt = $pdo->prepare("SELECT userpass FROM userr WHERE userid = ?");
        $stmt->execute([$userid]);
        $current_pass = $stmt->fetchColumn();

        if ($password !== $current_pass || $email_lama !== $_SESSION['user']['email']) {
            header("Location: index.php?page=ubah_email&status=error&msg=Email lama atau password salah!");
            exit();
        }

        // Update email di database
        $upd = $pdo->prepare("UPDATE userr SET useremail = ?, username = ? WHERE userid = ?");
        $new_username = explode('@', $email_baru)[0];
        $upd->execute([$email_baru, $new_username, $userid]);

        // Perbarui data session agar tampilan ikut berubah
        $_SESSION['user']['email'] = $email_baru;
        $_SESSION['user']['name'] = $new_username;

        header("Location: index.php?page=akun&status=success&msg=Email berhasil diperbarui!");
        exit();
    } catch (PDOException $e) {
        header("Location: index.php?page=ubah_email&status=error&msg=Error: " . $e->getMessage());
        exit();
    }
}

// PROSES UBAH KATA SANDI DI AKUN INFO (UPDATE)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $page == 'proses_ubah_sandi') {
    if (!isset($_SESSION['user'])) header("Location: index.php?page=login");

    $sandi_lama = $_POST['sandi_lama'];
    $sandi_baru = $_POST['sandi_baru'];
    $sandi_conf = $_POST['sandi_confirm'];
    $userid     = $_SESSION['user']['id'];

    if ($sandi_baru !== $sandi_conf) {
        header("Location: index.php?page=ubah_sandi&status=error&msg=Konfirmasi sandi baru tidak cocok!");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT userpass FROM userr WHERE userid = ?");
        $stmt->execute([$userid]);
        if ($sandi_lama !== $stmt->fetchColumn()) {
            header("Location: index.php?page=ubah_sandi&status=error&msg=Kata sandi lama salah!");
            exit();
        }

        // Update password di database
        $upd = $pdo->prepare("UPDATE userr SET userpass = ? WHERE userid = ?");
        $upd->execute([$sandi_baru, $userid]);

        header("Location: index.php?page=akun&status=success&msg=Kata sandi berhasil diubah!");
        exit();
    } catch (PDOException $e) {
        header("Location: index.php?page=ubah_sandi&status=error&msg=Error: " . $e->getMessage());
        exit();
    }
}

// PROSES HAPUS AKUN (DELETE)
if ($page == 'proses_hapus_akun') {
    if (!isset($_SESSION['user'])) header("Location: index.php?page=login");
    
    try {
        $userid = $_SESSION['user']['id'];
        // Hapus permanen baris data user dari tabel userr
        $del = $pdo->prepare("DELETE FROM userr WHERE userid = ?");
        $del->execute([$userid]);

        // Hancurkan session login
        session_destroy();
        header("Location: index.php?page=login&status=success&msg=Akun Anda telah dihapus permanen.");
        exit();
    } catch (PDOException $e) {
        header("Location: index.php?page=akun&status=error&msg=Gagal menghapus akun: " . $e->getMessage());
        exit();
    }
}

// LOGOUT AKUN
if ($page == 'logout') {
    session_destroy();
    header("Location: index.php?page=login");
    exit();
}

// Menangkap feedback status url untuk dijadikan pesan alert
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']);
    $msg_type = ($_GET['status'] == 'success') ? 'green' : 'red';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projek Mangkrak</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php if(!empty($msg)): ?>
        <div class="global-alert" style="background-color: <?php echo $msg_type; ?>;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <?php if (in_array($page, ['login', 'daftar', 'lupasandi'])): ?>
        <div class="auth-container">
            
            <?php if ($page == 'login'): ?>
                <div class="wireframe-box auth-box">
                    <h3>Login</h3>
                    <form action="index.php?page=proses_login" method="POST">
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" class="btn-block">Login</button>
                    </form>
                    <div class="auth-links">
                        <a href="index.php?page=daftar">Daftar</a>
                        <a href="index.php?page=lupasandi">Lupa Password?</a>
                    </div>
                </div>

            <?php elseif ($page == 'daftar'): ?>
                <div class="wireframe-box auth-box">
                    <div class="back-header">
                        <a href="index.php?page=login"><i class="fa-solid fa-arrow-left"></i></a>
                        <span>Daftar</span>
                    </div>
                    <form action="index.php?page=proses_daftar" method="POST">
                        <input type="email" name="email_daftar" placeholder="Masukkan alamat email" required>
                        <input type="password" name="pass_daftar" placeholder="Masukkan kata sandi" required>
                        <input type="password" name="pass_confirm" placeholder="Masukkan ulang kata sandi" required>
                        <button type="submit" class="btn-block">Daftar</button>
                    </form>
                </div>

            <?php elseif ($page == 'lupasandi'): ?>
                <div class="wireframe-box auth-box">
        <div class="back-header">
            <a href="index.php?page=login"><i class="fa-solid fa-arrow-left"></i></a>
            <span>Lupa Kata Sandi</span>
        </div>
        
        <form action="index.php?page=proses_lupa_sandi" method="POST">
            <input type="email" name="email" placeholder="Masukkan alamat email" required>
            <input type="password" name="pass_baru" placeholder="Masukkan kata sandi baru" required>
            <input type="password" name="pass_confirm" placeholder="Masukkan ulang kata sandi baru" required>
            <button type="submit" class="btn-block">Oke</button>
        </form>

        <?php elseif ($page == 'lupa_sandi_error'): ?>
            <div class="error-overlay">
                <div class="error-modal">
                    <div class="error-title">Error!</div>
                    <div class="error-msg">Sandi yang anda masukkan salah! / Email tidak terdaftar</div>
                    <a href="index.php?page=lupasandi" class="btn-modal">Kembali</a>
                </div>
            </div>
            <?php endif; ?>

        </div>

    <?php else: ?>
        <?php if(!isset($_SESSION['user'])) header("Location: index.php?page=login"); ?>

        <div class="app-container">
            
            <aside class="sidebar">
                <div class="logo-area">
                    <i class="fa-solid fa-square-terminal logo-icon"></i>
                    <span class="logo-text">END FIELD</span>
                </div>
                <nav class="nav-menu">
                    <ul>
                        <li class="<?php echo ($page == 'beranda') ? 'active' : ''; ?>"><a href="index.php?page=beranda"><i class="fa-solid fa-house"></i> <span>Beranda</span></a></li>
                        <li class="<?php echo ($page == 'berita') ? 'active' : ''; ?>"><a href="index.php?page=berita"><i class="fa-solid fa-newspaper"></i> <span>Berita</span></a></li>
                        <li class="<?php echo ($page == 'isi1') ? 'active' : ''; ?>"><a href="index.php?page=isi1"><i class="fa-solid fa-layer-group"></i> <span>Isi 1</span></a></li>
                        <li class="<?php echo ($page == 'isi2') ? 'active' : ''; ?>"><a href="index.php?page=isi2"><i class="fa-solid fa-layer-group"></i> <span>Isi 2</span></a></li>
                        <li class="<?php echo ($page == 'isi3') ? 'active' : ''; ?>"><a href="index.php?page=isi3"><i class="fa-solid fa-layer-group"></i> <span>Isi 3</span></a></li>
                    </ul>
                </nav>
                <div class="sidebar-footer">
                    <ul>
                        <li class="<?php echo (in_array($page, ['akun', 'ubah_email', 'ubah_sandi'])) ? 'active' : ''; ?>"><a href="index.php?page=akun"><i class="fa-solid fa-user-gear"></i> <span>Akun</span></a></li>
                    </ul>
                </div>
            </aside>

            <main class="main-content">
                
                <?php if ($page == 'berita'): ?>
                    <div class="news-container">
                        <h2>BERITA</h2>
                        <div class="news-list">
                            <p>Berita 1</p>
                            <div class="news-grid">
                                <div class="cross-box"></div>
                                <div class="cross-box"></div>
                                <div class="cross-box"></div>
                            </div>
                            <div class="news-footer-line"></div>
                        </div>
                    </div>

                <?php elseif (in_array($page, ['akun', 'ubah_email', 'ubah_sandi'])): ?>
                    <div class="account-header">
                        <span><?php echo $_SESSION['user']['email']; ?></span>
                    </div>
                    
                    <div class="account-layout-box">
                        <aside class="account-nav-side">
                            <div class="nav-title">Info Akun</div>
                            <ul>
                                <li class="<?php echo ($page == 'akun') ? 'active' : ''; ?>"><a href="index.php?page=akun">Info Akun</a></li>
                                <li class="<?php echo ($page == 'ubah_email') ? 'active' : ''; ?>"><a href="index.php?page=ubah_email">Ubah Email</a></li>
                                <li class="<?php echo ($page == 'ubah_sandi') ? 'active' : ''; ?>"><a href="index.php?page=ubah_sandi">Ubah Kata Sandi</a></li>
                            </ul>
                        </aside>

                        <section class="account-content-side">
                            <?php if ($page == 'akun'): ?>
                                <div class="info-block">
                                    <h4>Info Akun</h4>
                                    <div class="info-group">
                                        <p>ID Akun: <?php echo $_SESSION['user']['id']; ?></p>
                                        <p>Email: <?php echo $_SESSION['user']['email']; ?></p>
                                        <p>Username: <?php echo $_SESSION['user']['name']; ?></p>
                                    </div>
                                    <div class="center-actions-mock">
                                        <p>Akun sandi ini</p>
                                        <div class="user-badge"><?php echo $_SESSION['user']['email']; ?></div>
                                        <button class="btn-sub">Pusat Akun</button>
                                    </div>
                                    
                                    <div class="account-danger-zone">
                                        <a href="index.php?page=logout" class="btn-wireframe-logout">Logout</a>
                                        <a href="index.php?page=proses_hapus_akun" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen dari database?')" class="btn-wireframe-delete">Hapus Akun</a>
                                    </div>
                                </div>

                            <?php elseif ($page == 'ubah_email'): ?>
                                <div class="info-block">
                                    <h4>Ubah Email</h4>
                                    <form class="form-wireframe" action="index.php?page=proses_ubah_email" method="POST">
                                        <div class="form-row"><label>Email lama:</label><input type="email" name="email_lama" required></div>
                                        <div class="form-row"><label>Email baru:</label><input type="email" name="email_baru" required></div>
                                        <div class="form-row"><label>Kata sandi:</label><input type="password" name="password" required></div>
                                        <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                                    </form>
                                </div>

                            <?php elseif ($page == 'ubah_sandi'): ?>
                                <div class="info-block">
                                    <h4>Ubah kata sandi</h4>
                                    <form class="form-wireframe" action="index.php?page=proses_ubah_sandi" method="POST">
                                        <div class="form-row"><label>Kata sandi lama:</label><input type="password" name="sandi_lama" required></div>
                                        <div class="form-row"><label>Kata sandi baru:</label><input type="password" name="sandi_baru" required></div>
                                        <div class="form-row"><label>Ulangi sandi:</label><input type="password" name="sandi_confirm" required></div>
                                        <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </section>
                    </div>

                <?php else: ?>
                    <div class='wireframe-placeholder-box'>
                        <h2>Halaman: <?php echo ucfirst($page); ?></h2>
                        <div class='cross-lines'></div>
                    </div>
                <?php endif; ?>

            </main>
        </div>
    <?php endif; ?>

</body>
</html>