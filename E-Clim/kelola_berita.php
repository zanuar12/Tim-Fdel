<?php
if (!isset($pdo) || !isset($_SESSION['user'])) { die("Akses ditolak."); }

if (isset($_GET['hapus_id'])) {
    $hapus_id = filter_var($_GET['hapus_id'], FILTER_VALIDATE_INT);
    if ($hapus_id) {
        $stmt_img = $pdo->prepare("SELECT gambar FROM berita WHERE id_berita = ?");
        $stmt_img->execute([$hapus_id]);
        $berita_hapus = $stmt_img->fetch(PDO::FETCH_ASSOC);

        $del = $pdo->prepare("DELETE FROM berita WHERE id_berita = ?");
        if ($del->execute([$hapus_id])) {
            if ($berita_hapus && !empty($berita_hapus['gambar']) && file_exists('uploads/' . $berita_hapus['gambar'])) {
                unlink('uploads/' . $berita_hapus['gambar']);
            }
            echo "<script>window.location.href='index.php?page=kelola_berita&status=success&msg=Berita berhasil dihapus permanen.';</script>";
            exit();
        }
    }
}

try {
    $stmt = $pdo->query("SELECT b.id_berita, b.judul, b.tanggal_publikasi, u.username FROM berita b JOIN userr u ON b.penulis_id = u.userid ORDER BY b.tanggal_publikasi DESC");
    $daftar_berita = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $daftar_berita = []; }
?>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-pine">Manajemen Konten Berita</h2>
        <p class="text-sm text-gray-500">Kelola (Edit/Hapus) artikel berita yang telah dipublikasikan.</p>
    </div>
    <a href="index.php?page=tambah_berita" class="inline-flex items-center justify-center bg-pine hover:bg-pine-hover text-white text-sm font-bold px-5 py-2.5 rounded-lg transition-colors gap-2 shadow-sm"><i class="fa-solid fa-plus"></i> Tulis Berita Baru</a>
</div>

<div class="bg-white border border-border-gray rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-border-gray text-slate-dark text-sm font-semibold">
                <tr>
                    <th class="p-4">Judul Berita</th>
                    <th class="p-4">Penulis</th>
                    <th class="p-4">Tanggal Publikasi</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                <?php if (empty($daftar_berita)): ?>
                    <tr><td colspan="4" class="p-8 text-center text-gray-500">Belum ada data berita. Silakan tambahkan berita baru.</td></tr>
                <?php else: ?>
                    <?php foreach ($daftar_berita as $berita): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-semibold text-slate-dark max-w-xs md:max-w-md truncate"><?php echo htmlspecialchars($berita['judul']); ?></td>
                            <td class="p-4 text-gray-500"><?php echo htmlspecialchars($berita['username']); ?></td>
                            <td class="p-4 text-gray-500"><?php echo date('d M Y, H:i', strtotime($berita['tanggal_publikasi'])); ?></td>
                            <td class="p-4 text-center space-x-3">
                                <a href="index.php?page=edit_berita&id=<?php echo $berita['id_berita']; ?>" class="text-blue-600 hover:text-blue-800 text-base" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="index.php?page=kelola_berita&hapus_id=<?php echo $berita['id_berita']; ?>" class="text-terracotta hover:text-red-700 text-base" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini secara permanen?');"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>