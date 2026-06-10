<?php
// pengajuan-campaign.php - Halaman Pengajuan Campaign Baru
session_start();
require_once 'config/koneksi.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $submitter_name = $conn->real_escape_string($_POST['submitter_name'] ?? '');
    $submitter_email = $conn->real_escape_string($_POST['submitter_email'] ?? '');
    $submitter_phone = $conn->real_escape_string($_POST['submitter_phone'] ?? '');
    $organization_name = $conn->real_escape_string($_POST['organization_name'] ?? '');
    
    $title = $conn->real_escape_string($_POST['title'] ?? '');
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $location = $conn->real_escape_string($_POST['location'] ?? '');
    $tree_type = $conn->real_escape_string($_POST['tree_type'] ?? '');
    $target_trees = (int)($_POST['target_trees'] ?? 0);
    
    if (empty($submitter_name) || empty($submitter_email) || empty($submitter_phone) || empty($title) || empty($description) || empty($location) || empty($tree_type) || $target_trees <= 0) {
        $error = "Semua field yang bertanda * wajib diisi dan target pohon harus lebih dari 0.";
    } else {
        $image_paths = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $target_dir = 'uploads/campaign_submissions/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
            $file_count = count($_FILES['images']['name']);
            
            for ($i = 0; $i < $file_count; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $file_extension = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                    
                    if (in_array($file_extension, $allowed_types) && $_FILES['images']['size'][$i] <= 5000000) {
                        $file_name = uniqid('sub_') . '_' . $i . '.' . $file_extension;
                        $target_file = $target_dir . $file_name;
                        
                        if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_file)) {
                            $image_paths[] = $conn->real_escape_string($target_file);
                        }
                    } else {
                        $error = "Beberapa gambar memiliki format tidak didukung atau ukuran terlalu besar (Max 5MB).";
                    }
                }
            }
        }

        if (empty($error)) {
            $image_sql = !empty($image_paths) ? "'" . $conn->real_escape_string(json_encode($image_paths)) . "'" : "NULL";
            $sql = "INSERT INTO campaign_submissions 
                    (submitter_name, submitter_email, submitter_phone, organization_name, title, description, location, tree_type, target_trees, status, image, created_at) 
                    VALUES 
                    ('$submitter_name', '$submitter_email', '$submitter_phone', '$organization_name', '$title', '$description', '$location', '$tree_type', $target_trees, 'pending', $image_sql, NOW())";
                    
            if ($conn->query($sql)) {
                $success = true;
            } else {
                $error = "Terjadi kesalahan saat menyimpan pengajuan: " . $conn->error;
            }
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="pt-32 pb-12 bg-gradient-to-b from-primary-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center text-sm mb-8" data-aos="fade-up">
                <a href="campaign.php" class="text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-folder mr-1"></i>Campaign
                </a>
                <span class="text-gray-400 mx-3">/</span>
                <a href="deskripsi-pengajuan.php" class="text-primary-600 hover:text-primary-700 font-medium">
                    <i class="fas fa-info-circle mr-1"></i>Informasi Pengajuan
                </a>
                <span class="text-gray-400 mx-3">/</span>
                <span class="text-gray-600 font-medium">Form Pengajuan</span>
            </nav>

            <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                    Isi Form Pengajuan Campaign
                </h1>
                <p class="text-xl text-gray-600">
                    Lengkapi semua detail campaign Anda dengan informasi yang akurat dan jelas.
                </p>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-12" id="form-section">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-8 flex items-start" data-aos="fade-in">
                <i class="fas fa-check-circle mt-1 mr-3 text-green-500 text-xl"></i>
                <div>
                    <h3 class="font-bold">Pengajuan Berhasil Dikirim!</h3>
                    <p class="text-sm">Terima kasih telah mengajukan campaign. Tim kami akan meninjau pengajuan Anda dan segera menghubungi Anda melalui email atau nomor telepon yang tertera.</p>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="campaign.php" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-full font-semibold hover:bg-primary-700 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Campaign
                </a>
            </div>
            <?php else: ?>
            
            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-8 flex items-start" data-aos="fade-in">
                <i class="fas fa-exclamation-circle mt-1 mr-3 text-red-500 text-xl"></i>
                <p><?php echo $error; ?></p>
            </div>
            <?php endif; ?>
            
            <form action="" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8" data-aos="fade-up">
                
                <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">1. Data Pengaju</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="submitter_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Contoh: Budi Santoso" value="<?php echo isset($_POST['submitter_name']) ? htmlspecialchars($_POST['submitter_name']) : ''; ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="submitter_email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Contoh: budi@email.com" value="<?php echo isset($_POST['submitter_email']) ? htmlspecialchars($_POST['submitter_email']) : ''; ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon/WhatsApp <span class="text-red-500">*</span></label>
                        <input type="tel" name="submitter_phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Contoh: 081234567890" value="<?php echo isset($_POST['submitter_phone']) ? htmlspecialchars($_POST['submitter_phone']) : ''; ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Organisasi/Komunitas (Opsional)</label>
                        <input type="text" name="organization_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Contoh: Komunitas Hijau" value="<?php echo isset($_POST['organization_name']) ? htmlspecialchars($_POST['organization_name']) : ''; ?>">
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-4">2. Detail Campaign</h3>
                
                <div class="grid grid-cols-1 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Campaign <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Contoh: Penanaman Mangrove di Pesisir Jakarta" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Singkat Campaign <span class="text-red-500">*</span></label>
                        <textarea name="description" required rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Ceritakan tujuan campaign dan mengapa ini penting..."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Penanaman <span class="text-red-500">*</span></label>
                            <input type="text" name="location" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Contoh: Jakarta Utara" value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pohon <span class="text-red-500">*</span></label>
                            <input type="text" name="tree_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Contoh: Mangrove" value="<?php echo isset($_POST['tree_type']) ? htmlspecialchars($_POST['tree_type']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Jumlah Pohon <span class="text-red-500">*</span></label>
                        <input type="number" name="target_trees" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Contoh: 1000" value="<?php echo isset($_POST['target_trees']) ? htmlspecialchars($_POST['target_trees']) : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Lokasi / Ilustrasi (Bisa Lebih dari 1)</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG atau WEBP (MAX. 5MB per foto)</p>
                                </div>
                                <input type="file" name="images[]" id="imageInput" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/png, image/jpeg, image/jpg, image/webp" />
                            </label>
                        </div>
                        <div id="imagePreviewContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4 empty:hidden"></div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 transition duration-300">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
            <?php endif; ?>
            
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('bg-white/95', 'backdrop-blur-md', 'shadow-lg');
                nav.classList.remove('glass-effect');
            } else {
                nav.classList.remove('bg-white/95', 'backdrop-blur-md', 'shadow-lg');
                nav.classList.add('glass-effect');
            }
        });

        // Image Previews
        const imageInput = document.getElementById('imageInput');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                imagePreviewContainer.innerHTML = '';
                
                if (this.files && this.files.length > 0) {
                    Array.from(this.files).forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewHtml = `
                                <div class="relative rounded-lg overflow-hidden border border-gray-200 h-24 group">
                                    <img src="${e.target.result}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <p class="text-white text-xs text-center px-1 truncate w-full">${file.name}</p>
                                    </div>
                                </div>
                            `;
                            imagePreviewContainer.insertAdjacentHTML('beforeend', previewHtml);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });
        }
    </script>
</body>
</html>
