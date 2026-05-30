<?php
// Mengambil halaman aktif untuk memberikan class 'active' pada menu navbar
$current_page = isset($_GET['page']) ? $_GET['page'] : 'login';
?>
<aside class="sidebar">
    <div class="logo-area">
        <i class="fa-solid fa-square-terminal logo-icon"></i>
        <span class="logo-text">END FIELD</span>
    </div>

    <nav class="nav-menu">
        <ul>
            <li class="<?php echo ($current_page == 'beranda') ? 'active' : ''; ?>">
                <a href="index.php?page=beranda"><i class="fa-solid fa-house"></i> <span>Beranda</span></a>
            </li>
            <li class="<?php echo ($current_page == 'isi1') ? 'active' : ''; ?>">
                <a href="index.php?page=isi1"><i class="fa-solid fa-layer-group"></i> <span>Isi 1</span></a>
            </li>
            <li class="<?php echo ($current_page == 'isi2') ? 'active' : ''; ?>">
                <a href="index.php?page=isi2"><i class="fa-solid fa-layer-group"></i> <span>Isi 2</span></a>
            </li>
            <li class="<?php echo ($current_page == 'isi3') ? 'active' : ''; ?>">
                <a href="index.php?page=isi3"><i class="fa-solid fa-layer-group"></i> <span>Isi 3</span></a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <ul>
            <li class="<?php echo (in_array($current_page, ['akun', 'ubah_email', 'ubah_sandi'])) ? 'active' : ''; ?>">
                <a href="index.php?page=akun"><i class="fa-solid fa-user-gear"></i> <span>Akun</span></a>
            </li>
        </ul>
    </div>
</aside>