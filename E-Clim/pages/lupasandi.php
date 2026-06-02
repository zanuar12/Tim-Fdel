<div class="wireframe-box auth-box">
    <div class="back-header">
        <a href="index.php?page=login"><i class="fa-solid fa-arrow-left"></i></a>
        <span>Lupa Kata Sandi</span>
    </div>
    
    <form action="index.php?page=proseslupasandi" method="POST">
        <input type="email" name="email" placeholder="Masukkan alamat email" required>
        <input type="password" name="pass_baru" placeholder="Masukkan kata sandi baru" required>
        <input type="password" name="pass_confirm" placeholder="Masukkan ulang kata sandi baru" required>
        <button type="submit" class="btn-block">Oke</button>
    </form>
</div>