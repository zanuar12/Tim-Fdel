<?php
if (!isset($pdo)) { die("Akses langsung ditolak."); }
try {
    $stmt = $pdo->query("SELECT b.*, u.username FROM berita b JOIN userr u ON b.penulis_id = u.userid ORDER BY b.tanggal_publikasi DESC");
    $daftar_berita = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $daftar_berita = []; }
?>
<header class="page-header">
    <h2>Kabar Iklim Terkini</h2>
    <p>Informasi terbaru seputar lingkungan, kebijakan, dan krisis ekologi.</p>
</header>

<div class="news-grid">
    <?php if (empty($daftar_berita)): ?>
        <div class="card-box" style="grid-column: 1/-1; text-align: center;">
            <p style="color: var(--text-muted);">Belum ada berita yang diterbitkan di database.</p>
        </div>
    <?php else: ?>
        <?php foreach ($daftar_berita as $berita): ?>
            <article class="news-card">
                <div class="news-image-placeholder" style="overflow: hidden; padding: 0;">
                    <?php if (!empty($berita['gambar']) && file_exists('uploads/' . $berita['gambar'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($berita['gambar']); ?>" alt="Sampul Berita" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: #E5E7EB;">
                            <i class="fa-solid fa-newspaper fa-2x" style="color: var(--text-muted);"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="news-content">
                    <span class="badge badge-alert">Berita</span>
                    <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);"><?php echo htmlspecialchars($berita['judul']); ?></h3>
                    <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 10px;">
                        <i class="fa-solid fa-pen-nib"></i> <?php echo htmlspecialchars($berita['username']); ?> &bull; 
                        <?php echo date('d M Y', strtotime($berita['tanggal_publikasi'])); ?>
                    </p>
                    <p style="font-size: 13px; color: var(--text-muted);"><?php echo htmlspecialchars(substr($berita['konten'], 0, 100)) . '...'; ?></p>
                    
                    <a href="index.php?page=baca_berita&id=<?php echo $berita['id_berita']; ?>" class="read-more">Baca selengkapnya &rarr;</a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>