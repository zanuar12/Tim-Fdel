<?php
if (!isset($pdo) || !isset($_SESSION['user'])) { die("Akses ditolak."); }
$alert_html = '';
$id_berita = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : 0;

if (!$id_berita) {
    echo "<script>window.location.href='index.php?page=kelola_berita&status=error&msg=ID Berita salah.';</script>"; exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_berita'])) {
    $judul  = filter_var($_POST['judul'], FILTER_SANITIZE_STRING);
    $konten = $_POST['konten'];
    $gambar_lama = $_POST['gambar_lama']; 
    $nama_file_baru = $gambar_lama;

    if (isset($_FILES['foto_berita']) && $_FILES['foto_berita']['error'] == 0) {
        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
        $nama_file = $_FILES['foto_berita']['name'];
        $ukuran_file = $_FILES['foto_berita']['size'];
        $tmp_file = $_FILES['foto_berita']['tmp_name'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (!in_array($ekstensi, $ekstensi_diizinkan)) {
            $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Format ditolak!</div>';
        } elseif ($ukuran_file > 2097152) {
            $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Maksimal file 2MB!</div>';
        } else {
            $nama_file_baru = uniqid('img_', true) . '.' . $ekstensi;
            if (move_uploaded_file($tmp_file, 'uploads/' . $nama_file_baru)) {
                if (!empty($gambar_lama) && file_exists('uploads/' . $gambar_lama)) { unlink('uploads/' . $gambar_lama); }
            } else {
                $nama_file_baru = $gambar_lama;
            }
        }
    }

    if (empty($alert_html)) {
        try {
            $upd = $pdo->prepare("UPDATE berita SET judul = ?, konten = ?, gambar = ? WHERE id_berita = ?");
            if ($upd->execute([$judul, $konten, $nama_file_baru, $id_berita])) {
                echo "<script>window.location.href='index.php?page=kelola_berita&status=success&msg=Berita berhasil diperbarui!';</script>"; exit();
            }
        } catch (PDOException $e) { $alert_html = '<div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-semibold border border-red-200">Gagal update database.</div>'; }
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id_berita = ? LIMIT 1");
    $stmt->execute([$id_berita]);
    $berita_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$berita_edit) { echo "<script>window.location.href='index.php?page=kelola_berita&status=error&msg=Data hilang.';</script>"; exit(); }
} catch (PDOException $e) { die("Database error."); }
?>
<div class="mb-8">
    <a href="index.php?page=kelola_berita" class="text-gray-500 hover:text-pine text-sm font-semibold mb-2 inline-block">← Kembali ke Manajemen</a>
    <h2 class="text-2xl font-bold text-pine">Edit Artikel Berita</h2>
</div>

<div class="bg-white border border-border-gray p-6 md:p-8 rounded-xl shadow-sm max-w-4xl">
    <?php echo $alert_html; ?>
    <form action="index.php?page=edit_berita&id=<?php echo $id_berita; ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
        <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($berita_edit['gambar']); ?>">
        
        <div>
            <label class="block text-sm font-bold text-slate-dark mb-2">Judul Berita</label>
            <input type="text" name="judul" value="<?php echo htmlspecialchars($berita_edit['judul']); ?>" required class="w-full p-3 border border-border-gray rounded-lg text-sm focus:outline-none focus:border-pine bg-off-white">
        </div>
        
        <div>
            <label class="block text-sm font-bold text-slate-dark mb-2">Gambar Sampul Saat Ini</label>
            <?php if (!empty($berita_edit['gambar']) && file_exists('uploads/' . $berita_edit['gambar'])): ?>
                <div class="mb-3"><img src="uploads/<?php echo htmlspecialchars($berita_edit['gambar']); ?>" class="h-28 rounded-lg border border-border-gray shadow-sm"></div>
            <?php else: ?>
                <p class="text-xs text-gray-400 mb-3">(Tidak menggunakan foto sampul)</p>
            <?php endif; ?>
            <label class="block text-xs font-semibold text-gray-500 mb-2">Ganti File Gambar (Biarkan kosong jika tidak diubah)</label>
            <input type="file" name="foto_berita" accept="image/*" class="w-full p-2 border border-border-gray rounded-lg text-sm bg-gray-50 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-200 file:text-slate-dark">
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-dark mb-2">Isi Konten Berita</label>
            <textarea name="konten" required rows="14" class="w-full p-3 border border-border-gray rounded-lg text-sm focus:outline-none focus:border-pine bg-off-white resize-y"><?php echo htmlspecialchars($berita_edit['konten']); ?></textarea>
        </div>
        
        <div class="pt-2">
            <button type="submit" name="update_berita" class="bg-pine hover:bg-pine-hover text-white text-sm font-bold px-6 py-2.5 rounded-lg transition-colors shadow-sm"><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>