<?php
// users/profile.php
session_start();

// Proteksi: Jika belum login, tendang ke login.php
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? 'User';
$user_email = $_SESSION['user_email'] ?? '';
$user_phone = $_SESSION['user_phone'] ?? '';
$user_avatar = $_SESSION['user_avatar'] ?? null;

// Ambil status dan message dari query parameter
$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';

// Ambil data terbaru dari database
require_once '../config/koneksi.php';
if ($user_id) {
    $conn = getDB();
    $result = $conn->query("SELECT name, email, phone, avatar FROM users WHERE id = {$user_id} LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_name = $user['name'];
        $user_email = $user['email'];
        $user_phone = $user['phone'] ?? '';
        $user_avatar = $user['avatar'] ?? null;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Sodakoh Pohon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #f0fdf4 0%, #fefce8 100%); 
            min-height: 100vh;
        } 
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        }
        .avatar-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #f0e9df;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .avatar-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            border: 4px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .glass-input {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(229, 231, 235, 0.8);
            backdrop-filter: blur(4px);
        }
        .glass-input:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }
    </style>
</head>
<body class="antialiased">

    <!-- Include Header dari root directory -->
    <?php include '../includes/header.php'; ?>

    <div class="pt-24 max-w-4xl mx-auto px-4 py-10">
        <!-- Notification Modal -->
        <div id="notificationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-xl shadow-lg p-6 max-w-sm w-full mx-4 animate-fade-in">
                <div class="flex items-start gap-4">
                    <div id="modalIcon" class="flex-shrink-0"></div>
                    <div class="flex-1">
                        <h3 id="modalTitle" class="text-lg font-bold"></h3>
                        <p id="modalMessage" class="text-gray-600 mt-2"></p>
                    </div>
                    <button id="closeModalBtn" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="mt-6 flex justify-end">
                    <button id="closeModalBtn2" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">Profil Saya</h1>
        
        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="glass-card rounded-2xl p-6 sticky top-24 animate-fade-in" style="animation-delay: 0.1s; opacity: 0;">
                    <div class="text-center">
                        <!-- Avatar -->
                        <div class="relative inline-block mb-4 group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                            <?php if ($user_avatar && file_exists('../' . $user_avatar)): ?>
                                <img src="../<?php echo htmlspecialchars($user_avatar); ?>" alt="Avatar" class="avatar-preview mx-auto transition duration-300 group-hover:brightness-90">
                            <?php else: ?>
                                <div class="avatar-placeholder mx-auto transition duration-300 group-hover:brightness-90">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="bg-black/50 text-white rounded-full p-2 backdrop-blur-sm">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($user_name); ?></h2>
                        <p class="text-sm text-gray-500 break-all bg-gray-100/80 rounded-full px-3 py-1 inline-block"><?php echo htmlspecialchars($user_email); ?></p>
                        
                        <div class="mt-8 pt-6 border-t border-gray-200/60 text-left space-y-2">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-4">Menu Utama</h3>
                            
                            <a href="profile.php" class="flex items-center px-4 py-3 bg-primary-50 text-primary-700 rounded-xl transition font-medium group">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                Profil Saya
                            </a>
                            
                            <a href="riwayat.php" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary-600 rounded-xl transition font-medium group">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:bg-primary-50 group-hover:text-primary-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-history"></i>
                                </div>
                                Histori Donasi
                            </a>
                            
                            <a href="sertifikat.php" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary-600 rounded-xl transition font-medium group">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:bg-primary-50 group-hover:text-primary-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                Sertifikat Digital
                            </a>
                            
                            <a href="riwayat-pengajuan.php" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-primary-600 rounded-xl transition font-medium group">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 group-hover:bg-primary-50 group-hover:text-primary-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-list-alt"></i>
                                </div>
                                Riwayat Pengajuan
                            </a>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-gray-200/60">
                            <a href="../auth.php?action=logout" class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition font-medium group">
                                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 group-hover:bg-red-100 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Update Profile Section -->
                <div class="glass-card rounded-2xl p-6 md:p-8 animate-fade-in hover:-translate-y-1 transition-transform duration-300" style="animation-delay: 0.2s; opacity: 0;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-4">
                            <i class="fas fa-user-edit"></i>
                        </span>
                        Informasi Profil
                    </h2>
                    
                    <form action="./actions/update_profile.php" method="POST">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required class="w-full px-4 py-3 glass-input rounded-xl outline-none transition" placeholder="Masukkan nama lengkap">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                <input type="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly class="w-full px-4 py-3 bg-gray-100/50 border border-gray-200 rounded-xl cursor-not-allowed text-gray-500">
                                <p class="text-xs text-gray-500 mt-2 flex items-center"><i class="fas fa-info-circle mr-1"></i> Email tidak dapat diubah</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp / Telepon</label>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($user_phone); ?>" class="w-full px-4 py-3 glass-input rounded-xl outline-none transition" placeholder="081234567890">
                                <p class="text-xs text-gray-500 mt-2 flex items-center"><i class="fas fa-info-circle mr-1"></i> Opsional - Nomor telepon untuk kontak lebih lanjut</p>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-primary-600 transition shadow-lg shadow-primary-500/30 inline-flex items-center transform hover:scale-105 duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Upload Avatar Section (Hidden by default, triggered from sidebar) -->
                <div class="glass-card rounded-2xl p-6 md:p-8 animate-fade-in" style="animation-delay: 0.3s; opacity: 0; display: none;" id="avatarUploadPanel">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-4">
                            <i class="fas fa-camera"></i>
                        </span>
                        Foto Profil
                    </h2>
                    
                    <div id="avatarSection">
                        <div class="space-y-4">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-primary-500 hover:bg-primary-50/50 transition-colors" id="uploadArea">
                                <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/webp" style="display: none;">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-700 font-semibold text-lg">Klik atau drag gambar di sini</p>
                                <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG, WebP (Max 5MB)</p>
                            </div>
                            
                            <div id="uploadPreview" style="display: none;" class="text-center p-6 bg-gray-50/50 rounded-xl border border-gray-100">
                                <img id="previewImage" class="avatar-preview mx-auto shadow-lg mb-6">
                                <div class="flex gap-3 mt-4 justify-center">
                                    <button type="button" id="uploadConfirmBtn" class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-xl hover:from-green-600 hover:to-green-700 transition-all shadow-lg shadow-green-500/30 inline-flex items-center">
                                        <i class="fas fa-check mr-2"></i>Upload Sekarang
                                    </button>
                                    <button type="button" id="uploadCancelBtn" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors inline-flex items-center">
                                        <i class="fas fa-times mr-2"></i>Batal
                                    </button>
                                </div>
                            </div>
                            
                            <div id="uploadProgress" style="display: none;" class="space-y-3 p-6 bg-gray-50/50 rounded-xl">
                                <div class="flex items-center justify-between text-sm font-medium">
                                    <span class="text-primary-600 flex items-center"><i class="fas fa-circle-notch fa-spin mr-2"></i> Mengupload...</span>
                                    <span id="progressPercent" class="text-gray-700">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                                    <div id="progressBar" class="bg-gradient-to-r from-primary-500 to-primary-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="glass-card rounded-2xl p-6 md:p-8 animate-fade-in hover:-translate-y-1 transition-transform duration-300" id="change-password" style="animation-delay: 0.4s; opacity: 0;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center mr-4">
                            <i class="fas fa-lock"></i>
                        </span>
                        Ubah Password
                    </h2>
                    
                    <form action="./actions/change_password.php" method="POST">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="old_password" required class="w-full px-4 py-3 glass-input rounded-xl outline-none transition pr-12" placeholder="Masukkan password lama">
                                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="0">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="new_password" required class="w-full px-4 py-3 glass-input rounded-xl outline-none transition pr-12" placeholder="Masukkan password baru (min 6 karakter)">
                                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 flex items-center"><i class="fas fa-shield-alt mr-1"></i> Minimal 6 karakter</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="confirm_password" required class="w-full px-4 py-3 glass-input rounded-xl outline-none transition pr-12" placeholder="Konfirmasi password baru">
                                    <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-gray-800 text-white font-semibold rounded-xl hover:bg-gray-900 transition shadow-lg inline-flex items-center transform hover:scale-105 duration-200">
                                <i class="fas fa-check mr-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    
    <script>
        const notificationModal = document.getElementById('notificationModal');
        const modalIcon = document.getElementById('modalIcon');
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const closeModalBtn2 = document.getElementById('closeModalBtn2');

        function showModal(status, message) {
            modalMessage.textContent = message;
            
            if (status === 'success') {
                modalTitle.textContent = 'Berhasil!';
                modalTitle.className = 'text-lg font-bold text-green-700';
                modalIcon.innerHTML = '<div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center"><i class="fas fa-check-circle text-green-600 text-2xl"></i></div>';
                closeModalBtn2.className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition';
            } else {
                modalTitle.textContent = 'Error!';
                modalTitle.className = 'text-lg font-bold text-red-700';
                modalIcon.innerHTML = '<div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center"><i class="fas fa-exclamation-circle text-red-600 text-2xl"></i></div>';
                closeModalBtn2.className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition';
            }
            
            notificationModal.classList.remove('hidden');
        }

        function closeModal() {
            notificationModal.classList.add('hidden');
            // Clear query params from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        closeModalBtn.addEventListener('click', closeModal);
        closeModalBtn2.addEventListener('click', closeModal);

        // Check if status query param exists and show modal
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const message = urlParams.get('message');

        if (status && message) {
            showModal(status, message);
        }

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach((btn, index) => {
            btn.addEventListener('click', function() {
                const target = parseInt(this.dataset.target);
                const inputs = document.querySelectorAll('input[type="password"]');
                const input = inputs[target];
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Avatar upload handling
        const uploadArea = document.getElementById('uploadArea');
        const avatarInput = document.getElementById('avatarInput');
        const uploadPreview = document.getElementById('uploadPreview');
        const previewImage = document.getElementById('previewImage');
        const uploadConfirmBtn = document.getElementById('uploadConfirmBtn');
        const uploadCancelBtn = document.getElementById('uploadCancelBtn');
        const uploadProgress = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        const progressPercent = document.getElementById('progressPercent');
        
        let selectedFile = null;

        // Click to upload
        uploadArea.addEventListener('click', () => avatarInput.click());

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-primary-500', 'bg-primary-50');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-primary-500', 'bg-primary-50');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-primary-500', 'bg-primary-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });

        // File input change
        avatarInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            selectedFile = file;
            const reader = new FileReader();
            
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                uploadArea.style.display = 'none';
                uploadPreview.style.display = 'block';
                // Show the upload panel if it was hidden
                document.getElementById('avatarUploadPanel').style.display = 'block';
                // Scroll to upload panel smoothly
                document.getElementById('avatarUploadPanel').scrollIntoView({behavior: 'smooth', block: 'center'});
            };
            
            reader.readAsDataURL(file);
        }

        uploadCancelBtn.addEventListener('click', () => {
            selectedFile = null;
            avatarInput.value = '';
            uploadArea.style.display = 'block';
            uploadPreview.style.display = 'none';
        });

        uploadConfirmBtn.addEventListener('click', async () => {
            if (!selectedFile) return;

            // Validate file
            if (selectedFile.size > 5 * 1024 * 1024) {
                showModal('error', 'Ukuran file terlalu besar (max 5MB)');
                return;
            }

            uploadArea.style.display = 'none';
            uploadPreview.style.display = 'none';
            uploadProgress.style.display = 'block';

            const formData = new FormData();
            formData.append('avatar', selectedFile);

            try {
                const xhr = new XMLHttpRequest();
                
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percent + '%';
                        progressPercent.textContent = percent + '%';
                    }
                });

                xhr.addEventListener('load', () => {
                    uploadProgress.style.display = 'none';
                    
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showModal('success', response.message);
                            // Reload page after modal close to show new avatar
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            showModal('error', response.message);
                            uploadArea.style.display = 'block';
                        }
                    } else {
                        showModal('error', 'Error: Upload gagal');
                        uploadArea.style.display = 'block';
                    }
                });

                xhr.addEventListener('error', () => {
                    uploadProgress.style.display = 'none';
                    showModal('error', 'Error: Koneksi gagal');
                    uploadArea.style.display = 'block';
                });

                xhr.open('POST', './actions/upload_avatar.php');
                xhr.send(formData);
            } catch (error) {
                uploadProgress.style.display = 'none';
                showModal('error', 'Error: ' + error.message);
                uploadArea.style.display = 'block';
            }
        });
    </script>
</body>
</html>