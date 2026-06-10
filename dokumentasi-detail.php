<?php
// dokumentasi-detail.php - Halaman Detail Dokumentasi Penanaman
require_once 'config/koneksi.php';

$conn = getDB();

// Ambil ID dari URL parameter
$planting_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($planting_id <= 0) {
    header('Location: dokumentasi.php');
    exit;
}

// ─── AMBIL DATA DETAIL PENANAMAN ───────────────────────────────────────────

$query = "
    SELECT 
        p.id,
        p.planting_date as date,
        p.campaign_id,
        c.title as campaign,
        p.location,
        p.trees_planted,
        p.volunteers,
        p.coordinator,
        p.image,
        p.description,
        p.status,
        p.created_at
    FROM plantings p
    JOIN campaigns c ON c.id = p.campaign_id
    WHERE p.id = ?
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param('i', $planting_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: dokumentasi.php');
    exit;
}

$planting = $result->fetch_assoc();
$stmt->close();

// Format data
$planting_detail = [
    'id' => $planting['id'],
    'date' => $planting['date'],
    'campaign' => $planting['campaign'],
    'campaign_id' => $planting['campaign_id'],
    'location' => $planting['location'],
    'trees_planted' => (int)$planting['trees_planted'],
    'volunteers' => (int)$planting['volunteers'],
    'coordinator' => $planting['coordinator'],
    'image' => $planting['image'],
    'description' => $planting['description'],
    'status' => $planting['status'],
    'created_at' => $planting['created_at']
];

// ─── AMBIL GALERI GAMBAR ──────────────────────────────────────────────────

$gallery_query = "
    SELECT id, image_url, caption 
    FROM planting_gallery 
    WHERE planting_id = ? 
    ORDER BY created_at ASC
";

$stmt = $conn->prepare($gallery_query);
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param('i', $planting_id);
$stmt->execute();
$gallery_result = $stmt->get_result();

$gallery_images = [];
while ($row = $gallery_result->fetch_assoc()) {
    $gallery_images[] = [
        'id' => $row['id'],
        'url' => $row['image_url'],
        'caption' => $row['caption']
    ];
}
$stmt->close();

// Tentukan URL gambar cover
$coverImgSrc = '';
if (!empty($planting_detail['image'])) {
    if (strpos($planting_detail['image'], 'http') === 0) {
        $coverImgSrc = $planting_detail['image'];
    } else {
        $coverImgSrc = BASE_URL . '/' . ltrim($planting_detail['image'], '/');
    }
} else if (!empty($gallery_images)) {
    $coverImgSrc = BASE_URL . '/' . ltrim($gallery_images[0]['url'], '/');
} else {
    $coverImgSrc = 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80';
}

// Label status
$statusMap = [
    'completed' => ['label' => 'Selesai', 'cls' => 'bg-green-100 text-green-700', 'icon' => 'fa-check-circle'],
    'scheduled' => ['label' => 'Terjadwal', 'cls' => 'bg-blue-100 text-blue-700', 'icon' => 'fa-calendar'],
    'cancelled' => ['label' => 'Dibatalkan', 'cls' => 'bg-red-100 text-red-700', 'icon' => 'fa-times-circle']
];
$statusInfo = $statusMap[$planting_detail['status']] ?? ['label' => $planting_detail['status'], 'cls' => 'bg-gray-100 text-gray-600', 'icon' => 'fa-question-circle'];

// Get current URL for sharing
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$currentUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$shareText = urlencode($planting_detail['campaign']);
$shareUrl = urlencode($currentUrl);
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section dengan Breadcrumb -->
<section class="pt-20 pb-8 bg-gradient-to-b from-primary-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm mb-8">
            <a href="index.php" class="text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-home mr-1"></i>Beranda
            </a>
            <span class="text-gray-400 mx-3">/</span>
            <a href="dokumentasi.php" class="text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-images mr-1"></i>Dokumentasi
            </a>
            <span class="text-gray-400 mx-3">/</span>
            <span class="text-gray-600 font-medium"><?php echo htmlspecialchars($planting_detail['campaign']); ?></span>
        </nav>

        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <!-- Cover Image -->
            <div class="md:col-span-2">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                    <img src="<?php echo htmlspecialchars($coverImgSrc); ?>"
                        alt="<?php echo htmlspecialchars($planting_detail['campaign']); ?>"
                        class="w-full h-96 object-cover"
                        onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
            </div>

            <!-- Info Ringkas -->
            <div>
                <div class="bg-white rounded-2xl shadow-card p-6 h-96 flex flex-col">
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas <?php echo $statusInfo['icon']; ?> text-lg"></i>
                            <span class="text-sm font-semibold px-3 py-1 rounded-full <?php echo $statusInfo['cls']; ?>">
                                <?php echo $statusInfo['label']; ?>
                            </span>
                        </div>
                        <h1 class="text-2xl font-extrabold text-gray-900 line-clamp-3">
                            <?php echo htmlspecialchars($planting_detail['campaign']); ?>
                        </h1>
                    </div>

                    <div class="space-y-4 flex-grow">
                        <!-- Tanggal -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0 text-primary-600">
                                <i class="fas fa-calendar-alt text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Tanggal Penanaman</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    <?php echo date('d F Y', strtotime($planting_detail['date'])); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600">
                                <i class="fas fa-map-marker-alt text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Lokasi</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($planting_detail['location']); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Pohon & Relawan -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-green-50 rounded-xl p-3">
                                <p class="text-xs text-gray-500 font-medium">Pohon Ditanam</p>
                                <p class="text-lg font-extrabold text-green-600">
                                    <?php echo number_format($planting_detail['trees_planted']); ?>
                                </p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-3">
                                <p class="text-xs text-gray-500 font-medium">Relawan</p>
                                <p class="text-lg font-extrabold text-blue-600">
                                    <?php echo $planting_detail['volunteers']; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <a href="dokumentasi.php" class="mt-auto w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-lg transition text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Detail Konten -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Konten Utama -->
            <div class="md:col-span-2">
                <!-- Deskripsi -->
                <?php if (!empty($planting_detail['description'])): ?>
                    <div class="bg-white rounded-2xl shadow-card p-8 mb-8">
                        <h2 class="text-2xl font-extrabold text-gray-900 mb-4">
                            <i class="fas fa-file-alt text-primary-600 mr-2"></i>Deskripsi Kegiatan
                        </h2>
                        <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($planting_detail['description'])); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Galeri Foto -->
                <div class="bg-white rounded-2xl shadow-card p-8">
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-6">
                        <i class="fas fa-images text-primary-600 mr-2"></i>Galeri Foto Kegiatan
                    </h2>

                    <?php if (empty($gallery_images)): ?>
                        <div class="text-center py-12 bg-gray-50 rounded-xl">
                            <i class="fas fa-image text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-400 font-medium">Tidak ada foto dalam galeri.</p>
                        </div>
                    <?php else: ?>
                        <!-- Grid Galeri -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                            <?php foreach ($gallery_images as $index => $img): ?>
                                <div class="group relative rounded-xl overflow-hidden cursor-pointer gallery-thumbnail"
                                    data-index="<?php echo $index; ?>">
                                    <div class="relative h-48 overflow-hidden bg-gray-100">
                                        <img src="<?php echo BASE_URL . '/' . ltrim($img['url'], '/'); ?>"
                                            alt="Galeri foto <?php echo $index + 1; ?>"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                                            onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80'">
                                    </div>
                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center">
                                        <i class="fas fa-magnifying-glass-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition"></i>
                                    </div>
                                    <!-- Caption -->
                                    <?php if (!empty($img['caption'])): ?>
                                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3">
                                            <p class="text-white text-sm font-medium line-clamp-2">
                                                <?php echo htmlspecialchars($img['caption']); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Lightbox Modal -->
                        <div id="lightbox" class="fixed inset-0 bg-black/90 hidden flex items-center justify-center z-50">
                            <div class="relative w-full h-full flex items-center justify-center">
                                <!-- Close Button -->
                                <button id="lightbox-close" class="absolute top-4 right-4 text-white hover:text-gray-300 transition z-10">
                                    <i class="fas fa-times text-3xl"></i>
                                </button>

                                <!-- Image Container -->
                                <div class="relative w-11/12 h-5/6 flex items-center justify-center">
                                    <img id="lightbox-image" src="" alt="Galeri foto" class="max-w-full max-h-full object-contain">
                                </div>

                                <!-- Navigation Buttons -->
                                <button id="lightbox-prev" class="absolute left-4 text-white hover:text-gray-300 transition">
                                    <i class="fas fa-chevron-left text-3xl"></i>
                                </button>
                                <button id="lightbox-next" class="absolute right-4 text-white hover:text-gray-300 transition">
                                    <i class="fas fa-chevron-right text-3xl"></i>
                                </button>

                                <!-- Caption -->
                                <div id="lightbox-caption" class="absolute bottom-4 left-0 right-0 text-center text-white text-sm font-medium px-4"></div>

                                <!-- Counter -->
                                <div class="absolute top-4 left-4 text-white font-semibold bg-black/50 px-4 py-2 rounded-lg">
                                    <span id="image-counter">1</span> / <span><?php echo count($gallery_images); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="text-center text-sm text-gray-500">
                            Klik gambar untuk melihat versi lebih besar
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div>
                <!-- Detail Info Card -->
                <div class="bg-gradient-to-b from-primary-50 to-blue-50 rounded-2xl p-6 mb-6 border border-primary-100">
                    <h3 class="text-lg font-extrabold text-gray-900 mb-6">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i>Informasi Detail
                    </h3>

                    <div class="space-y-5">
                        <!-- Campaign -->
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                <i class="fas fa-leaf text-primary-600 mr-1"></i>Program Kampanye
                            </p>
                            <p class="text-sm font-semibold text-gray-900">
                                <a href="campaign-detail.php?id=<?php echo $planting_detail['campaign_id']; ?>"
                                    class="text-primary-600 hover:text-primary-700 transition">
                                    <?php echo htmlspecialchars($planting_detail['campaign']); ?>
                                </a>
                            </p>
                        </div>

                        <!-- Coordinator -->
                        <?php if (!empty($planting_detail['coordinator'])): ?>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    <i class="fas fa-user-tie text-blue-600 mr-1"></i>Koordinator
                                </p>
                                <p class="text-sm font-semibold text-gray-900">
                                    <?php echo htmlspecialchars($planting_detail['coordinator']); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Total Info -->
                        <div class="pt-4 border-t border-primary-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                <i class="fas fa-chart-bar text-green-600 mr-1"></i>Ringkasan
                            </p>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white rounded-lg p-3">
                                    <p class="text-xs text-gray-500 font-medium mb-1">Pohon Ditanam</p>
                                    <p class="text-lg font-extrabold text-green-600">
                                        <?php echo number_format($planting_detail['trees_planted']); ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-3">
                                    <p class="text-xs text-gray-500 font-medium mb-1">Relawan</p>
                                    <p class="text-lg font-extrabold text-blue-600">
                                        <?php echo $planting_detail['volunteers']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="pt-4 border-t border-primary-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                <i class="fas <?php echo $statusInfo['icon']; ?> text-lg mr-1"></i>Status
                            </p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold <?php echo $statusInfo['cls']; ?>">
                                <?php echo $statusInfo['label']; ?>
                            </span>
                        </div>

                        <!-- Tanggal -->
                        <div class="pt-4 border-t border-primary-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                <i class="fas fa-clock text-orange-600 mr-1"></i>Tanggal Pencatatan
                            </p>
                            <p class="text-sm font-semibold text-gray-900">
                                <?php echo date('d F Y H:i', strtotime($planting_detail['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Share Card -->
                <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-200">
                    <h3 class="text-lg font-extrabold text-gray-900 mb-4">
                        <i class="fas fa-share-alt text-primary-600 mr-2"></i>Bagikan
                    </h3>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $shareUrl; ?>"
                            target="_blank"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition text-center">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo $shareUrl; ?>&text=<?php echo $shareText; ?>"
                            target="_blank"
                            class="flex-1 bg-blue-400 hover:bg-blue-500 text-white font-semibold py-2 rounded-lg transition text-center">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode($planting_detail['campaign'] . ' ' . $currentUrl); ?>"
                            target="_blank"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition text-center">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

<script>
    // Make functions globally accessible
    window.currentImageIndex = 0;
    window.galleryImages = <?php echo json_encode($gallery_images); ?>;
    window.baseUrl = '<?php echo BASE_URL; ?>';

    // Event listener untuk gallery thumbnails
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.gallery-thumbnail');
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                window.openLightbox(index);
            });
        });

        // Event listeners untuk lightbox buttons
        const closeBtn = document.getElementById('lightbox-close');
        const prevBtn = document.getElementById('lightbox-prev');
        const nextBtn = document.getElementById('lightbox-next');

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                window.closeLightbox();
            });
        }
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                window.prevImage();
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                window.nextImage();
            });
        }
    });

    window.openLightbox = function(index) {
        window.currentImageIndex = index;
        window.updateLightbox();
        const lightbox = document.getElementById('lightbox');
        if (lightbox) {
            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeLightbox = function() {
        const lightbox = document.getElementById('lightbox');
        if (lightbox) {
            lightbox.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };

    window.nextImage = function() {
        if (window.galleryImages.length === 0) return;
        window.currentImageIndex = (window.currentImageIndex + 1) % window.galleryImages.length;
        window.updateLightbox();
    };

    window.prevImage = function() {
        if (window.galleryImages.length === 0) return;
        window.currentImageIndex = (window.currentImageIndex - 1 + window.galleryImages.length) % window.galleryImages.length;
        window.updateLightbox();
    };

    window.updateLightbox = function() {
        if (!window.galleryImages || window.galleryImages.length === 0) return;
        
        const img = window.galleryImages[window.currentImageIndex];
        const imgElement = document.getElementById('lightbox-image');
        const captionElement = document.getElementById('lightbox-caption');
        const counterElement = document.getElementById('image-counter');

        if (imgElement) {
            // Clean up the URL - remove leading slash if present
            let imgUrl = img.url.replace(/^\//, '');
            imgElement.src = window.baseUrl + '/' + imgUrl;
        }
        
        if (captionElement) {
            captionElement.textContent = img.caption || 'Foto dokumentasi kegiatan';
        }
        
        if (counterElement) {
            counterElement.textContent = window.currentImageIndex + 1;
        }
    };

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox || lightbox.classList.contains('hidden')) return;
        
        if (e.key === 'ArrowRight') window.nextImage();
        if (e.key === 'ArrowLeft') window.prevImage();
        if (e.key === 'Escape') window.closeLightbox();
    });

    // Close lightbox when clicking outside image
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.addEventListener('click', function(e) {
            if (e.target.id === 'lightbox') {
                window.closeLightbox();
            }
        });
    }
</script>

</html>