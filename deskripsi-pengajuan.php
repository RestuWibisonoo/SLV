<?php
// deskripsi-pengajuan.php - Halaman Deskripsi Pengajuan Campaign
session_start();
require_once 'config/koneksi.php';
?>
<?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="pt-32 pb-12 bg-gradient-to-b from-primary-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                    Ajukan Campaign Penanaman Anda
                </h1>
                <p class="text-xl text-gray-600">
                    Punya lahan atau komunitas? Mari berkolaborasi menghijaukan bumi dengan membuat campaign penanaman Anda sendiri.
                </p>
            </div>
        </div>
    </section>

    <!-- Information Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-2 bg-primary-100 rounded-full text-primary-700 font-semibold text-sm mb-4">
                    <i class="fas fa-info-circle mr-2"></i>Panduan Pengajuan
                </span>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Bagaimana Cara Mengajukan Campaign?
                </h2>
                <p class="text-xl text-gray-600">
                    Kami membuat proses pengajuan campaign semudah mungkin. Ikuti panduan di bawah ini untuk memulai.
                </p>
            </div>

            <!-- What is Campaign Submission -->
            <div class="bg-gradient-to-r from-primary-50 to-earth-50 rounded-3xl p-8 mb-12 border border-primary-100" data-aos="fade-up">
                <div class="grid md:grid-cols-3 gap-8">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-primary-200 flex items-center justify-center mb-4">
                            <i class="fas fa-lightbulb text-primary-700 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Apa itu Pengajuan Campaign?</h3>
                        <p class="text-gray-700">
                            Pengajuan campaign adalah cara Anda untuk mengusulkan program penanaman pohon dengan target, lokasi, dan deskripsi yang spesifik. Tim kami akan meninjau dan memvalidasi sebelum diluncurkan.
                        </p>
                    </div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-earth-200 flex items-center justify-center mb-4">
                            <i class="fas fa-check-circle text-earth-700 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Proses Validasi</h3>
                        <p class="text-gray-700">
                            Setelah pengajuan, tim kami akan meninjau data Anda dalam 1-3 hari kerja. Kami akan menghubungi Anda via email atau WhatsApp untuk verifikasi lebih lanjut.
                        </p>
                    </div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-200 flex items-center justify-center mb-4">
                            <i class="fas fa-rocket text-blue-700 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Campaign Go Live</h3>
                        <p class="text-gray-700">
                            Setelah disetujui, campaign Anda akan ditampilkan di platform kami dan dapat menerima donasi dari ribuan pengguna.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Process Steps -->
            <div class="mb-12" data-aos="fade-up">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">Langkah-Langkah Pengajuan</h3>
                <div class="grid md:grid-cols-4 gap-4">
                    <!-- Step 1 -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-6 border-2 border-primary-200 hover:border-primary-400 transition">
                            <div class="absolute -top-5 -left-3 w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                                1
                            </div>
                            <div class="pt-4">
                                <i class="fas fa-file-alt text-primary-600 text-2xl mb-3 block"></i>
                                <h4 class="font-bold text-gray-900 mb-2">Isi Data Diri</h4>
                                <p class="text-sm text-gray-600">
                                    Isi informasi lengkap Anda dan organisasi/komunitas (jika ada).
                                </p>
                            </div>
                        </div>
                        <div class="hidden md:block absolute top-1/2 -right-2 w-4 h-1 bg-gray-300"></div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-6 border-2 border-primary-200 hover:border-primary-400 transition">
                            <div class="absolute -top-5 -left-3 w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                                2
                            </div>
                            <div class="pt-4">
                                <i class="fas fa-tree text-earth-600 text-2xl mb-3 block"></i>
                                <h4 class="font-bold text-gray-900 mb-2">Detail Campaign</h4>
                                <p class="text-sm text-gray-600">
                                    Jelaskan tentang campaign, lokasi, jenis pohon, dan target.
                                </p>
                            </div>
                        </div>
                        <div class="hidden md:block absolute top-1/2 -right-2 w-4 h-1 bg-gray-300"></div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-6 border-2 border-primary-200 hover:border-primary-400 transition">
                            <div class="absolute -top-5 -left-3 w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                                3
                            </div>
                            <div class="pt-4">
                                <i class="fas fa-image text-blue-600 text-2xl mb-3 block"></i>
                                <h4 class="font-bold text-gray-900 mb-2">Upload Foto</h4>
                                <p class="text-sm text-gray-600">
                                    Tambahkan foto lokasi atau ilustrasi campaign Anda.
                                </p>
                            </div>
                        </div>
                        <div class="hidden md:block absolute top-1/2 -right-2 w-4 h-1 bg-gray-300"></div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-6 border-2 border-primary-200 hover:border-primary-400 transition">
                            <div class="absolute -top-5 -left-3 w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                                4
                            </div>
                            <div class="pt-4">
                                <i class="fas fa-check text-green-600 text-2xl mb-3 block"></i>
                                <h4 class="font-bold text-gray-900 mb-2">Kirim & Tunggu</h4>
                                <p class="text-sm text-gray-600">
                                    Kirim pengajuan dan kami akan meninjau dalam 1-3 hari kerja.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <!-- Requirements Checklist -->
                <div data-aos="fade-right">
                    <div class="bg-white rounded-2xl shadow-card p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-3"></i>Persyaratan Pengajuan
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Data Diri Lengkap</span>
                                    <p class="text-sm text-gray-600 mt-1">Nama, email, nomor telepon aktif yang dapat dihubungi.</p>
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Judul Campaign Jelas</span>
                                    <p class="text-sm text-gray-600 mt-1">Deskriptif dan mudah dipahami oleh calon donatur.</p>
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Lokasi Spesifik</span>
                                    <p class="text-sm text-gray-600 mt-1">Lokasi penanaman yang jelas dan terukur.</p>
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Target Pohon Realistis</span>
                                    <p class="text-sm text-gray-600 mt-1">Minimal 100 pohon dan dapat diverifikasi.</p>
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Foto Pendukung</span>
                                    <p class="text-sm text-gray-600 mt-1">Minimal 1 foto lokasi (PNG, JPG, WEBP max 5MB).</p>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Benefits -->
                <div data-aos="fade-left">
                    <div class="bg-white rounded-2xl shadow-card p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-3"></i>Keuntungan Campaign
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="fas fa-heart text-primary-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Jangkauan Luas</span>
                                    <p class="text-sm text-gray-600 mt-1">Diakses oleh ribuan donatur di seluruh Indonesia.</p>
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-heart text-primary-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Dokumentasi Lengkap</span>
                                    <p class="text-sm text-gray-600 mt-1">Setiap donasi didokumentasikan dengan bukti penanaman.</p>
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-heart text-primary-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Transparan & Terpercaya</span>
                                    <p class="text-sm text-gray-600 mt-1">Laporan progress dan dampak lingkungan terukur.</p>
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-heart text-primary-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Dukungan Tim</span>
                                    <p class="text-sm text-gray-600 mt-1">Kami siap membantu dalam proses publikasi dan dokumentasi.</p>
                                </span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-heart text-primary-600 font-bold mr-3 mt-1 flex-shrink-0"></i>
                                <span class="text-gray-700">
                                    <span class="font-semibold">Dampak Nyata</span>
                                    <p class="text-sm text-gray-600 mt-1">Kontribusi langsung untuk penghijauan dan lingkungan.</p>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg p-6 mb-12" data-aos="fade-up">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-blue-900 mb-2">Catatan Penting</h3>
                        <ul class="text-sm text-blue-800 space-y-2">
                            <li><strong>• Verifikasi Lahan:</strong> Pastikan Anda memiliki izin atau kepemilikan lahan untuk penanaman.</li>
                            <li><strong>• Keakuratan Data:</strong> Semua informasi harus akurat dan dapat diverifikasi oleh tim kami.</li>
                            <li><strong>• Durasi Review:</strong> Tim kami akan mengkontak dalam 1-3 hari kerja untuk verifikasi lebih lanjut.</li>
                            <li><strong>• Transparansi:</strong> Setiap donasi akan dikelola dengan penuh transparansi dan laporan berkala diberikan kepada donatur.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up">
                <a href="campaign.php" class="inline-flex items-center justify-center px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 font-bold rounded-2xl hover:border-primary-600 hover:text-primary-600 transition">
                    <i class="fas fa-arrow-left mr-3"></i>
                    Kembali ke Campaign
                </a>
                <a href="pengajuan-campaign.php" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-2xl hover:from-primary-700 hover:to-primary-800 transition shadow-lg shadow-primary-600/30">
                    <i class="fas fa-arrow-right mr-3"></i>
                    Mulai Pengajuan Campaign
                </a>
            </div>
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
    </script>
</body>
</html>
