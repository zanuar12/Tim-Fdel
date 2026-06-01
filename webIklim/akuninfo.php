<?php
if (!isset($pdo) || !isset($_SESSION['user'])) { die("Akses langsung ditolak."); }
$userid = $_SESSION['user']['id'];
$alert_html = '';

$stmt_user = $pdo->prepare("SELECT * FROM userr WHERE userid = ?");
$stmt_user->execute([$userid]);
$user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profil'])) {
        $email_baru = filter_var($_POST['email_baru'], FILTER_SANITIZE_EMAIL);
        $username_baru = filter_var($_POST['username_baru'], FILTER_UNSAFE_RAW);
        $password_konfirmasi = $_POST['password_konfirmasi'];

        if (password_verify($password_konfirmasi, $user_data['userpass'])) {
            try {
                $upd = $pdo->prepare("UPDATE userr SET useremail = ?, username = ? WHERE userid = ?");
                if ($upd->execute([$email_baru, $username_baru, $userid])) {
                    $_SESSION['user']['email'] = $email_baru;
                    $_SESSION['user']['name'] = $username_baru;
                    $user_data['useremail'] = $email_baru;
                    $user_data['username'] = $username_baru;
                    $alert_html = '<div class="bg-green-50 text-green-700 p-4 rounded-lg mb-4 text-sm font-semibold border border-green-200">Profil berhasil diperbarui!</div>';
                }
            } catch (PDOException $e) { $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Email sudah terdaftar di akun lain.</div>'; }
        } else { $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Kata sandi konfirmasi salah!</div>'; }
    } 
    elseif (isset($_POST['update_sandi'])) {
        $sandi_lama = $_POST['sandi_lama'];
        $sandi_baru = $_POST['sandi_baru'];
        $sandi_conf = $_POST['sandi_conf'];

        if (strlen($sandi_baru) < 6) { $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Kata sandi baru minimal 6 karakter.</div>'; }
        elseif ($sandi_baru !== $sandi_conf) { $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Konfirmasi sandi baru tidak cocok.</div>'; }
        else {
            if (password_verify($sandi_lama, $user_data['userpass'])) {
                $hashed_new = password_hash($sandi_baru, PASSWORD_BCRYPT);
                $upd = $pdo->prepare("UPDATE userr SET userpass = ? WHERE userid = ?");
                if ($upd->execute([$hashed_new, $userid])) { $alert_html = '<div class="bg-green-50 text-green-700 p-4 rounded-lg mb-4 text-sm font-semibold border border-green-200">Kata sandi berhasil diubah!</div>'; }
            } else { $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Kata sandi lama salah!</div>'; }
        }
    }
    elseif (isset($_POST['hapus_akun'])) {
        $password_konfirmasi_hapus = $_POST['password_hapus'];
        if (password_verify($password_konfirmasi_hapus, $user_data['userpass'])) {
            $del = $pdo->prepare("DELETE FROM userr WHERE userid = ?");
            if ($del->execute([$userid])) {
                session_destroy();
                echo "<script>window.location.href='index.php?page=login&status=success&msg=Akun admin telah dihapus permanen.';</script>"; exit();
            }
        } else { $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Kata sandi salah! Penghapusan digagalkan.</div>'; }
    }
}
$action = isset($_GET['action']) ? $_GET['action'] : '';
?>

<div class="mb-8">
    <h2 class="text-2xl font-bold text-pine">Manajemen Akun Admin</h2>
    <p class="text-sm text-gray-500">Kelola informasi kredensial login dan keamanan panel kontrol Anda.</p>
</div>

<div class="flex flex-col md:flex-row gap-6 items-start">
    <aside class="w-full md:w-64 bg-white border border-border-gray rounded-xl p-3 shadow-sm space-y-1">
        <a href="index.php?page=akun" class="block p-3 text-sm rounded-lg transition-colors <?php echo ($page == 'akun' && $action != 'danger') ? 'bg-off-white text-pine font-bold' : 'text-gray-600 hover:bg-gray-50'; ?>">Info Profil</a>
        <a href="index.php?page=ubah_email" class="block p-3 text-sm rounded-lg transition-colors <?php echo ($page == 'ubah_email') ? 'bg-off-white text-pine font-bold' : 'text-gray-600 hover:bg-gray-50'; ?>">Perbarui Profil</a>
        <a href="index.php?page=ubah_sandi" class="block p-3 text-sm rounded-lg transition-colors <?php echo ($page == 'ubah_sandi') ? 'bg-off-white text-pine font-bold' : 'text-gray-600 hover:bg-gray-50'; ?>">Keamanan Sandi</a>
        <div class="border-t border-gray-100 my-2 pt-2">
            <a href="index.php?page=akun&action=danger" class="block p-3 text-sm text-terracotta font-semibold rounded-lg hover:bg-red-50 transition-colors">Zona Bahaya</a>
        </div>
    </aside>

    <section class="flex-1 w-full bg-white border border-border-gray p-6 md:p-8 rounded-xl shadow-sm">
        <?php echo $alert_html; ?>

        <?php if ($action == 'danger'): ?>
            <h3 class="text-xl font-bold text-terracotta mb-4">Hapus Akun Permanen</h3>
            <div class="bg-red-50 border border-red-200 p-4 rounded-lg mb-6 text-sm text-red-800 leading-relaxed">
                <strong>Peringatan Kritis:</strong> Tindakan ini menghapus data admin Anda secara permanen dari server. Hak kontrol panel admin ini akan langsung hangus setelah proses selesai.
            </div>
            <form action="index.php?page=akun&action=danger" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-dark mb-2">Konfirmasi Kata Sandi Anda</label>
                    <input type="password" name="password_hapus" required class="w-full max-w-md p-3 border border-border-gray rounded-lg text-sm bg-off-white focus:outline-none">
                </div>
                <button type="submit" name="hapus_akun" class="bg-terracotta hover:bg-red-700 text-white text-sm font-bold px-5 py-2.5 rounded-lg transition-colors shadow-sm" onclick="return confirm('Apakah Anda benar-benar yakin?');">Saya Yakin, Hapus Akun Ini</button>
            </form>

        <?php elseif ($page == 'akun'): ?>
            <h3 class="text-xl font-bold text-slate-dark mb-5">Detail Akun</h3>
            <div class="space-y-4 max-w-xl">
                <div><label class="block text-xs font-bold text-gray-400 mb-1">ID Pengguna (Sistem)</label><input type="text" value="<?php echo $user_data['userid']; ?>" disabled class="w-full p-2.5 border border-border-gray rounded-lg text-sm bg-gray-50 text-gray-500"></div>
                <div><label class="block text-xs font-bold text-gray-400 mb-1">Username Admin</label><input type="text" value="<?php echo htmlspecialchars($user_data['username']); ?>" disabled class="w-full p-2.5 border border-border-gray rounded-lg text-sm bg-gray-50 text-gray-500"></div>
                <div><label class="block text-xs font-bold text-gray-400 mb-1">Email Utama</label><input type="text" value="<?php echo htmlspecialchars($user_data['useremail']); ?>" disabled class="w-full p-2.5 border border-border-gray rounded-lg text-sm bg-gray-50 text-gray-500"></div>
            </div>

        <?php elseif ($page == 'ubah_email'): ?>
            <h3 class="text-xl font-bold text-slate-dark mb-5">Perbarui Informasi Profil</h3>
            <form action="index.php?page=ubah_email" method="POST" class="space-y-4 max-w-xl">
                <div>
                    <label class="block text-sm font-bold text-slate-dark mb-1">Ubah Username</label>
                    <input type="text" name="username_baru" value="<?php echo htmlspecialchars($user_data['username']); ?>" required class="w-full p-3 border border-border-gray rounded-lg text-sm bg-off-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-dark mb-1">Ubah Alamat Email</label>
                    <input type="email" name="email_baru" value="<?php echo htmlspecialchars($user_data['useremail']); ?>" required class="w-full p-3 border border-border-gray rounded-lg text-sm bg-off-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-dark mb-1">Kata Sandi (Otorisasi Akses)</label>
                    <input type="password" name="password_konfirmasi" placeholder="Masukkan kata sandi Anda..." required class="w-full p-3 border border-border-gray rounded-lg text-sm bg-off-white">
                </div>
                <button type="submit" name="update_profil" class="bg-pine hover:bg-pine-hover text-white text-sm font-bold px-5 py-2.5 rounded-lg transition-colors shadow-sm">Simpan Pembaruan Profil</button>
            </form>

        <?php elseif ($page == 'ubah_sandi'): ?>
            <h3 class="text-xl font-bold text-slate-dark mb-5">Perbarui Kata Sandi</h3>
            <form action="index.php?page=ubah_sandi" method="POST" class="space-y-4 max-w-xl">
                <div>
                    <label class="block text-sm font-bold text-slate-dark mb-1">Kata Sandi Lama</label>
                    <input type="password" name="sandi_lama" required class="w-full p-3 border border-border-gray rounded-lg text-sm bg-off-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-dark mb-1">Kata Sandi Baru (Min. 6 Karakter)</label>
                    <input type="password" name="sandi_baru" required minlength="6" class="w-full p-3 border border-border-gray rounded-lg text-sm bg-off-white">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-dark mb-1">Ulangi Kata Sandi Baru</label>
                    <input type="password" name="sandi_conf" required minlength="6" class="w-full p-3 border border-border-gray rounded-lg text-sm bg-off-white">
                </div>
                <button type="submit" name="update_sandi" class="bg-pine hover:bg-pine-hover text-white text-sm font-bold px-5 py-2.5 rounded-lg transition-colors shadow-sm">Ganti Sandi Keamanan</button>
            </form>
        <?php endif; ?>
    </section>
</div>