<?php
if (!isset($pdo) || !isset($_SESSION['user'])) { die("Akses ditolak."); }
$alert_html = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_berita'])) {
    $judul  = filter_var($_POST['judul'], FILTER_SANITIZE_STRING);
    $konten = $_POST['konten']; 
    $penulis_id = $_SESSION['user']['id'];
    $nama_file_baru = null;

    if (isset($_FILES['foto_berita']) && $_FILES['foto_berita']['error'] == 0) {
        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $nama_file = $_FILES['foto_berita']['name'];
        $ukuran_file = $_FILES['foto_berita']['size'];
        $tmp_file = $_FILES['foto_berita']['tmp_name'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (!in_array($ekstensi, $ekstensi_diizinkan)) {
            $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Format file ditolak! Hanya JPG, PNG, WEBP, GIF.</div>';
        } elseif ($ukuran_file > 2097152) {
            $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Ukuran file terlalu besar! Maksimal 2MB.</div>';
        } else {
            $nama_file_baru = uniqid('img_', true) . '.' . $ekstensi;
            if (!move_uploaded_file($tmp_file, 'uploads/' . $nama_file_baru)) {
                $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Gagal mengunggah gambar. Pastikan folder "uploads" tersedia.</div>';
                $nama_file_baru = null;
            }
        }
    }

    if (empty($alert_html)) {
        try {
            $ins = $pdo->prepare("INSERT INTO berita (judul, konten, penulis_id, gambar) VALUES (?, ?, ?, ?)");
            if ($ins->execute([$judul, $konten, $penulis_id, $nama_file_baru])) {
                echo "<script>window.location.href='index.php?page=kelola_berita&status=success&msg=Berita berhasil diterbitkan!';</script>";
                exit();
            }
        } catch (PDOException $e) {
            $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Gagal menyimpan ke database.</div>';
        }
    }
}
?>
<div class="mb-8">
    <a href="index.php?page=kelola_berita" class="text-gray-500 hover:text-pine text-sm font-semibold mb-2 inline-block">← Kembali ke Manajemen</a>
    <h2 class="text-2xl font-bold text-pine">Tulis Berita Baru</h2>
</div>

<div class="bg-white border border-border-gray p-6 md:p-8 rounded-xl shadow-sm max-w-4xl">
    <?php echo $alert_html; ?>
    <form action="index.php?page=tambah_berita" method="POST" enctype="multipart/form-data" class="space-y-5">
        <div>
            <label class="block text-sm font-bold text-slate-dark mb-2">Judul Berita</label>
            <input type="text" name="judul" required placeholder="Tuliskan judul berita utama..." class="w-full p-3 border border-border-gray rounded-lg text-sm focus:outline-none focus:border-pine bg-off-white">
        </div>
        
        <div>
            <label class="block text-sm font-bold text-slate-dark mb-2">Foto / Sampul Berita (Maks. 2MB)</label>
            <input type="file" name="foto_berita" accept="image/*" class="w-full p-3 border border-dashed border-gray-300 rounded-lg text-sm bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-pine file:text-white hover:file:bg-pine-hover">
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-dark mb-2">Isi Konten Berita</label>
            <textarea name="konten" required rows="12" placeholder="Tulis rincian artikel berita secara lengkap di sini..." class="w-full p-3 border border-border-gray rounded-lg text-sm focus:outline-none focus:border-pine bg-off-white resize-y"></textarea>
        </div>
        
        <div class="pt-2">
            <button type="submit" name="submit_berita" class="bg-pine hover:bg-pine-hover text-white text-sm font-bold px-6 py-2.5 rounded-lg transition-colors shadow-sm"><i class="fa-solid fa-paper-plane mr-1"></i> Terbitkan Berita</button>
        </div>
    </form>
</div>