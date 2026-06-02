<?php
if (!isset($pdo)) { die("Akses langsung ditolak."); }
$id_berita = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : 0;

if (!$id_berita) {
    echo "<div class='bg-white border border-border-gray p-6 rounded-lg max-w-2xl mx-auto text-center shadow-sm'>
            <h3 class='text-xl font-bold text-terracotta mb-2'>Error: URL Tidak Valid</h3>
            <p class='text-gray-600 mb-4'>ID Berita tidak ditemukan atau formatnya salah.</p>
            <a href='index.php?page=berita' class='inline-block bg-pine hover:bg-pine-hover text-white text-sm font-semibold px-4 py-2 rounded-md transition-colors'>← Kembali ke Berita</a>
          </div>";
    return;
}

try {
    $stmt = $pdo->prepare("SELECT b.*, u.username FROM berita b JOIN userr u ON b.penulis_id = u.userid WHERE b.id_berita = ? LIMIT 1");
    $stmt->execute([$id_berita]);
    $berita = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $berita = false; }

if (!$berita) {
    echo "<div class='bg-white border border-border-gray p-6 rounded-lg max-w-2xl mx-auto text-center shadow-sm'>
            <h3 class='text-xl font-bold text-terracotta mb-2'>Berita Tidak Ditemukan (404)</h3>
            <p class='text-gray-600 mb-4'>Artikel ini telah dihapus atau tidak pernah ada.</p>
            <a href='index.php?page=berita' class='inline-block bg-pine hover:bg-pine-hover text-white text-sm font-semibold px-4 py-2 rounded-md transition-colors'>← Kembali ke Berita</a>
          </div>";
    return;
}
?>

<div class="bg-white border border-border-gray p-6 md:p-10 rounded-xl max-w-4xl mx-auto shadow-sm">
    <a href="index.php?page=berita" class="text-pine text-sm font-bold flex items-center gap-2 mb-6 hover:underline">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Berita
    </a>
    
    <h2 class="text-2xl md:text-3xl font-bold text-slate-dark leading-tight mb-4"><?php echo htmlspecialchars($berita['judul']); ?></h2>
    
    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 pb-4 mb-6 border-b border-border-gray">
        <span><i class="fa-solid fa-user-pen"></i> Ditulis oleh: <strong class="text-slate-dark"><?php echo htmlspecialchars($berita['username']); ?></strong></span>
        <span class="hidden sm:inline text-gray-300">|</span>
        <span><i class="fa-solid fa-clock"></i> Dipublikasikan: <?php echo date('d F Y, H:i', strtotime($berita['tanggal_publikasi'])); ?></span>
    </div>
    
    <?php if (!empty($berita['gambar']) && file_exists('uploads/' . $berita['gambar'])): ?>
        <div class="mb-6 rounded-xl overflow-hidden border border-border-gray shadow-sm">
            <img src="uploads/<?php echo htmlspecialchars($berita['gambar']); ?>" alt="Foto Berita" class="w-full max-h-[450px] object-cover">
        </div>
    <?php endif; ?>

    <div class="text-base text-slate-dark leading-relaxed text-justify space-y-4">
        <?php echo nl2br(htmlspecialchars($berita['konten'])); ?>
    </div>
</div>