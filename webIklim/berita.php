<?php
if (!isset($pdo)) { die("Akses langsung ditolak."); }
try {
    $stmt = $pdo->query("SELECT b.*, u.username FROM berita b JOIN userr u ON b.penulis_id = u.userid ORDER BY b.tanggal_publikasi DESC");
    $daftar_berita = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $daftar_berita = []; }
?>
<div class="mb-8">
    <h2 class="text-3xl font-bold text-pine mb-2">Kabar Iklim Terkini</h2>
    <p class="text-gray-600">Informasi terbaru seputar lingkungan, kebijakan, dan krisis ekologi global.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($daftar_berita)): ?>
        <div class="col-span-full bg-white border border-border-gray p-10 rounded-lg text-center shadow-sm">
            <i class="fa-solid fa-folder-open text-gray-300 text-4xl mb-3"></i>
            <p class="text-gray-500">Belum ada berita yang diterbitkan di database.</p>
        </div>
    <?php else: ?>
        <?php foreach ($daftar_berita as $berita): ?>
            <article class="bg-white border border-border-gray rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 flex flex-col group">
                <div class="h-48 w-full bg-gray-200 overflow-hidden relative flex items-center justify-center">
                    <?php if (!empty($berita['gambar']) && file_exists('uploads/' . $berita['gambar'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($berita['gambar']); ?>" alt="Sampul Berita" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <?php else: ?>
                        <i class="fa-solid fa-newspaper text-gray-400 text-4xl"></i>
                    <?php endif; ?>
                </div>
                
                <div class="p-5 flex-1 flex flex-col">
                    <span class="inline-block bg-terracotta text-white text-xs px-2.5 py-1 rounded-md font-semibold mb-3 w-max">Berita</span>
                    <h3 class="text-lg font-bold text-slate-dark mb-2 line-clamp-2 group-hover:text-pine transition-colors"><?php echo htmlspecialchars($berita['judul']); ?></h3>
                    
                    <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                        <span><i class="fa-solid fa-user-edit text-gray-400"></i> <?php echo htmlspecialchars($berita['username']); ?></span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-calendar-day text-gray-400"></i> <?php echo date('d M Y', strtotime($berita['tanggal_publikasi'])); ?></span>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-5 line-clamp-3"><?php echo htmlspecialchars(substr($berita['konten'], 0, 120)) . '...'; ?></p>
                    
                    <a href="index.php?page=baca_berita&id=<?php echo $berita['id_berita']; ?>" class="mt-auto pt-3 border-t border-gray-100 text-pine font-bold text-sm flex items-center gap-1 hover:gap-2 transition-all">
                        Baca selengkapnya <span class="text-base">&rarr;</span>
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>