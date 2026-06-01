<div class="wireframe-box auth-box">
    <div class="back-header">
        <a href="index.php?page=login"><i class="fa-solid fa-arrow-left"></i></a>
        <span>Daftar</span>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div style="color: red; font-size: 12px; margin-bottom: 10px; text-align: center;">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?page=proses_daftar" method="POST">
        <input type="email" name="email_daftar" placeholder="Masukkan alamat email" required>
        <input type="password" name="pass_daftar" placeholder="Masukkan kata sandi" required>
        <input type="password" name="pass_confirm" placeholder="Masukkan ulang kata sandi" required>
        <button type="submit" class="btn-block">Daftar</button>
    </form>
</div>