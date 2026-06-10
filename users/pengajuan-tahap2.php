<?php
// users/pengajuan-tahap2.php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once '../config/koneksi.php';
require_once '../models/CampaignSubmission.php';

$conn = getDB();
$submissionModel = new CampaignSubmission();

$user_email = $_SESSION['user_email'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0 || !$user_email) {
    header("Location: riwayat-pengajuan.php");
    exit;
}

// Cek apakah data valid dan milik user
$sub = $submissionModel->getById($id);

if (!$sub || $sub['submitter_email'] !== $user_email) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Data tidak ditemukan atau Anda tidak berhak mengaksesnya.'];
    header("Location: riwayat-pengajuan.php");
    exit;
}

if ($sub['stage'] != 1 || $sub['status'] !== 'approved') {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Pengajuan ini tidak valid untuk dilanjutkan ke Tahap 2.'];
    header("Location: riwayat-pengajuan.php");
    exit;
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $price_per_tree = isset($_POST['price_per_tree']) ? (float)$_POST['price_per_tree'] : 0;
    $deadline = $_POST['deadline'] ?? '';
    $category = $_POST['category'] ?? 'Umum';
    $partner = $_POST['partner'] ?? '';
    $map_url = $_POST['map_url'] ?? '';
    $long_description = $_POST['long_description'] ?? '';
    $benefits = isset($_POST['benefits']) ? $_POST['benefits'] : [];
    
    // Filter empty benefits
    $benefits = array_filter($benefits, function($value) {
        return !empty(trim($value));
    });
    
    if ($price_per_tree <= 0 || empty($deadline) || empty($long_description)) {
        $error = "Harap isi semua field wajib (bertanda *) dengan benar.";
    } else {
        $updateData = [
            'price_per_tree' => $price_per_tree,
            'deadline' => $deadline,
            'category' => $category,
            'partner' => $partner,
            'map_url' => $map_url,
            'long_description' => $long_description,
            'benefits_json' => json_encode(array_values($benefits))
        ];
        
        $result = $submissionModel->updateStage2Data($id, $updateData);
        if ($result) {
            $success = true;
        } else {
            $error = "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Campaign Tahap 2 - Sodakoh Pohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #f9fafb; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased">

    <?php include '../includes/header.php'; ?>

    <div class="max-w-4xl mx-auto px-4 py-12 pt-24">
        
        <?php if ($success): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-green-100 p-12 text-center">
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Pengajuan Tahap 2 Berhasil!</h2>
                <p class="text-gray-600 mb-8 max-w-lg mx-auto">Terima kasih telah melengkapi detail campaign Anda. Tim admin kami akan meninjau data ini sebelum campaign Anda resmi dipublikasikan.</p>
                <a href="riwayat-pengajuan.php" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-xl font-semibold hover:bg-primary-700 transition">
                    Kembali ke Riwayat Pengajuan
                </a>
            </div>
        <?php else: ?>
        
            <div class="flex items-center mb-8">
                <a href="riwayat-pengajuan.php" class="mr-4 w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-full text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">Pengajuan Akhir (Tahap 2)</h1>
                    <p class="text-gray-500 mt-1">Lengkapi detail untuk campaign: <span class="font-semibold text-primary-700"><?php echo htmlspecialchars($sub['title']); ?></span></p>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-8 flex items-start">
                    <i class="fas fa-exclamation-circle mt-1 mr-3 text-red-500 text-xl"></i>
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">
                
                <!-- Info Tahap 1 (Read-only) -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Informasi Tahap 1 (Pengajuan Awal)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl border border-gray-100">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Nama Pengaju</p>
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($sub['submitter_name']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Email</p>
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($sub['submitter_email']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Nomor Telepon</p>
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($sub['submitter_phone']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Organisasi/Komunitas</p>
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($sub['organization_name'] ?: '-'); ?></p>
                        </div>
                        <div class="md:col-span-2 border-t border-gray-200 my-2"></div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500 mb-1">Judul Campaign</p>
                            <p class="font-semibold text-gray-900 text-lg"><?php echo htmlspecialchars($sub['title']); ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500 mb-1">Deskripsi Singkat</p>
                            <p class="font-medium text-gray-700 bg-white p-3 rounded border border-gray-200"><?php echo nl2br(htmlspecialchars($sub['description'])); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Lokasi Penanaman</p>
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($sub['location']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Jenis Pohon</p>
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($sub['tree_type']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Target Pohon</p>
                            <p class="font-semibold text-gray-900"><?php echo number_format($sub['target_trees']); ?> pohon</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Detail Pembiayaan & Target Waktu</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Pohon (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price_per_tree" required min="1000" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 outline-none transition" placeholder="Contoh: 15000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penutupan (Deadline) <span class="text-red-500">*</span></label>
                            <input type="date" name="deadline" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 outline-none transition">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Informasi Tambahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 outline-none transition">
                                <option value="Umum">Umum</option>
                                <option value="Pesisir Pantai">Pesisir Pantai</option>
                                <option value="Pegunungan">Pegunungan</option>
                                <option value="Perkotaan">Perkotaan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mitra Penanaman (Opsional)</label>
                            <input type="text" name="partner" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 outline-none transition" placeholder="Contoh: Kelompok Tani Mekar Jaya" value="<?php echo htmlspecialchars($sub['organization_name'] ?? ''); ?>">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link Google Maps Lokasi</label>
                        <input type="url" name="map_url" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 outline-none transition" placeholder="Contoh: https://maps.google.com/...">
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Cerita Campaign</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="long_description" required rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 outline-none transition" placeholder="Ceritakan dengan lengkap latar belakang masalah, mengapa penanaman ini penting, dan dampak yang diharapkan..."></textarea>
                        <p class="text-xs text-gray-500 mt-2">Cerita yang menarik akan lebih mudah mendapatkan donasi.</p>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-gray-900">Benefit / Manfaat Campaign</h3>
                        <button type="button" id="addBenefitBtn" class="text-sm font-semibold text-primary-600 hover:text-primary-700"><i class="fas fa-plus"></i> Tambah</button>
                    </div>
                    <div id="benefitsContainer" class="space-y-3">
                        <div class="flex items-center gap-2 benefit-row">
                            <input type="text" name="benefits[]" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 outline-none transition" placeholder="Contoh: Mencegah abrasi laut">
                            <button type="button" class="text-gray-400 hover:text-red-500 p-2 opacity-0 cursor-default" disabled><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-primary-600 text-white rounded-xl font-bold hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 transition duration-300 transform hover:scale-[1.02]">
                        Submit Pengajuan Akhir <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
            
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('benefitsContainer');
            const addBtn = document.getElementById('addBenefitBtn');
            
            if(addBtn) {
                addBtn.addEventListener('click', function() {
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-2 benefit-row';
                    row.innerHTML = `
                        <input type="text" name="benefits[]" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-100 focus:border-primary-500 outline-none transition" placeholder="Manfaat lainnya...">
                        <button type="button" class="text-gray-400 hover:text-red-500 p-2" onclick="this.parentElement.remove()"><i class="fas fa-trash"></i></button>
                    `;
                    container.appendChild(row);
                });
            }
        });
    </script>
</body>
</html>
