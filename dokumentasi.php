<?php
// dokumentasi.php - Halaman Dokumentasi Penanaman
require_once 'config/koneksi.php';

$conn = getDB();

// ─── AMBIL DATA DOKUMENTASI PENANAMAN ─────────────────────────────────────────

$query = "
    SELECT 
        p.id,
        p.planting_date as date,
        c.title as campaign,
        p.location,
        p.trees_planted,
        p.volunteers,
        p.image,
        p.description,
        p.status
    FROM plantings p
    JOIN campaigns c ON c.id = p.campaign_id
    ORDER BY p.planting_date DESC
";
$planting_documentations_raw = db_query($query);

$planting_documentations = [];
foreach ($planting_documentations_raw as $row) {
    $planting_documentations[] = [
        'id' => $row['id'],
        'date' => $row['date'],
        'campaign' => $row['campaign'],
        'location' => $row['location'],
        'trees_planted' => (int) $row['trees_planted'],
        'volunteers' => (int) $row['volunteers'],
        'image' => $row['image'],
        'description' => $row['description'],
        'status' => $row['status'],
    ];
}
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="pt-32 pb-16 bg-gradient-to-b from-primary-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center bg-primary-100 rounded-full px-4 py-2 mb-6">
                <i class="fas fa-images text-primary-700 mr-2"></i>
                <span class="text-sm font-semibold text-primary-800">Galeri Aksi</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">
                Dokumentasi <span class="gradient-text">Penanaman</span>
            </h1>
            <p class="text-xl text-gray-600 leading-relaxed">
                Bukti nyata dari setiap donasi Anda. Berikut adalah rekam jejak aksi penanaman pohon yang telah kami lakukan bersama relawan dan masyarakat.
            </p>
        </div>
    </div>
</section>

<!-- List Dokumentasi -->
<section class="py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($planting_documentations)): ?>
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-seedling text-5xl mb-4 block"></i>
                <p>Belum ada data dokumentasi penanaman saat ini.</p>
            </div>
        <?php else: ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($planting_documentations as $doc):
                    // Tentukan URL gambar
                    $imgSrc = '';
                    if (!empty($doc['image'])) {
                        if (strpos($doc['image'], 'http') === 0) {
                            $imgSrc = $doc['image'];
                        } else {
                            $imgSrc = BASE_URL . '/' . ltrim($doc['image'], '/');
                        }
                    } else {
                        $imgSrc = 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80';
                    }
                    
                    // Label status
                    $statusMap = [
                        'completed' => ['label' => 'Selesai', 'cls' => 'bg-green-100 text-green-700'],
                        'scheduled' => ['label' => 'Terjadwal', 'cls' => 'bg-blue-100 text-blue-700'],
                        'cancelled' => ['label' => 'Dibatalkan', 'cls' => 'bg-red-100 text-red-700']
                    ];
                    $statusInfo = $statusMap[$doc['status']] ?? ['label' => $doc['status'], 'cls' => 'bg-gray-100 text-gray-600'];
                    ?>
                    <a href="dokumentasi-detail.php?id=<?php echo $doc['id']; ?>" class="documentation-card bg-white rounded-2xl shadow-card overflow-hidden group flex flex-col transition hover:shadow-lg no-underline block">
                        <div class="relative h-56 overflow-hidden">
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>"
                                alt="<?php echo htmlspecialchars($doc['campaign']); ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80'">
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm rounded-lg px-3 py-1">
                                <span class="text-xs font-bold text-primary-700"><?php echo date('d M Y', strtotime($doc['date'])); ?></span>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="text-xs font-semibold px-3 py-1 rounded-full <?php echo $statusInfo['cls']; ?>">
                                    <?php echo $statusInfo['label']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex-grow flex flex-col">
                            <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">
                                <?php echo htmlspecialchars($doc['campaign']); ?>
                            </h3>
                            <p class="text-sm text-gray-500 mb-3 flex items-start">
                                <i class="fas fa-map-marker-alt mt-1 mr-2 text-primary-600"></i>
                                <span><?php echo htmlspecialchars($doc['location']); ?></span>
                            </p>
                            <?php if (!empty($doc['description'])): ?>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-grow">
                                    <?php echo htmlspecialchars($doc['description']); ?>
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-gray-600 mb-4 flex-grow italic text-gray-400">
                                    Tidak ada deskripsi.
                                </p>
                            <?php endif; ?>
                            
                            <div class="pt-4 border-t border-gray-100 flex justify-between items-center mt-auto">
                                <div class="flex items-center text-sm font-medium text-gray-700">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center mr-2 text-primary-600">
                                        <i class="fas fa-tree"></i>
                                    </div>
                                    <?php echo number_format($doc['trees_planted']); ?> Pohon
                                </div>
                                <div class="flex items-center text-sm font-medium text-gray-700">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2 text-blue-600">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <?php echo $doc['volunteers']; ?> Relawan
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
