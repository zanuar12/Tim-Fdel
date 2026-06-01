<?php
// Memisahkan sub-halaman akun berdasarkan parameter ?page=
$sub_page = isset($_GET['page']) ? $_GET['page'] : 'akun';
?>
<div class="account-header">
    <span>user email</span>
</div>

<div class="account-layout-box">
    <aside class="account-nav-side">
        <div class="nav-title">Info Akun</div>
        <ul>
            <li class="<?php echo ($sub_page == 'akun') ? 'active' : ''; ?>">
                <a href="index.php?page=akun">Info Akun</a>
            </li>
            <li class="<?php echo ($sub_page == 'ubah_email') ? 'active' : ''; ?>">
                <a href="index.php?page=ubah_email">Ubah Email</a>
            </li>
            <li class="<?php echo ($sub_page == 'ubah_sandi') ? 'active' : ''; ?>">
                <a href="index.php?page=ubah_sandi">Ubah Kata Sandi</a>
            </li>
        </ul>
    </aside>

    <section class="account-content-side">
        
        <?php if ($sub_page == 'akun'): ?>
            <div class="info-block">
                <h4>Info Akun</h4>
                <div class="info-group">
                    <p>ID Akun: <?php echo $_SESSION['user']['id']; ?></p>
                    <p>Email: <?php echo $_SESSION['user']['email']; ?></p>
                </div>
                <div class="center-actions-mock">
                    <p>Akun Saat ini</p>
                    <div class="user-badge"><?php echo $_SESSION['user']['email']; ?></div>
                </div>
                <a href="index.php?page=login" class="btn-wireframe-logout">Logout</a>
            </div>

        <?php elseif ($sub_page == 'ubah_email'): ?>
            <div class="info-block">
                <h4>Ubah Email</h4>
                <form class="form-wireframe" action="index.php?page=akun" method="POST">
                    <div class="form-row">
                        <label>Email lama:</label>
                        <input type="text" name="email_lama">
                    </div>
                    <div class="form-row">
                        <label>Email baru:</label>
                        <input type="text" name="email_baru">
                    </div>
                    <div class="form-row">
                        <label>Kata sandi:</label>
                        <input type="password" name="konfirmasi_sandi">
                    </div>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </form>
            </div>

        <?php elseif ($sub_page == 'ubah_sandi'): ?>
            <div class="info-block">
                <h4>Ubah kata sandi</h4>
                <form class="form-wireframe" action="index.php?page=akun" method="POST">
                    <div class="form-row">
                        <label>Kata sandi lama:</label>
                        <input type="password">
                    </div>
                    <div class="form-row">
                        <label>Kata sandi baru:</label>
                        <input type="password">
                    </div>
                    <div class="form-row">
                        <label>Masukkan ulang kata sandi:</label>
                        <input type="password">
                    </div>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </form>
            </div>
        <?php endif; ?>

    </section>
</div>