<?php
session_start();

// ================= KONEKSI DATABASE =================
require_once 'config/koneksi.php';

?>
<?php include 'includes/header.php'; ?>

    <!-- Programs Section -->
    <section id="programs" class="pt-32 pb-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-2 bg-primary-100 rounded-full text-primary-700 font-semibold text-sm mb-4">
                    <i class="fas fa-star mr-2"></i>Solusi Kami
                </span>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Program Penanaman Pohon untuk Semua
                </h2>
                <p class="text-xl text-gray-600">
                    Kami menyediakan berbagai program yang dapat disesuaikan dengan kebutuhan Anda:
                </p>
            </div>

            <!-- Programs Grid -->
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <!-- Corporatree Card -->
                <div class="group relative bg-white rounded-3xl overflow-hidden border-2 border-gray-100 hover:border-primary-300 transition duration-300" data-aos="fade-up" data-aos-delay="0">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-primary-50 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative p-8 flex flex-col">
                        <!-- Icon -->
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-building text-blue-600 text-3xl"></i>
                        </div>
                        <!-- Title & Subtitle -->
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Corporation</h3>
                        <p class="text-primary-600 font-semibold mb-4 text-sm">Program CSR Perusahaan</p>
                        <!-- Description -->
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            Program CSR dengan penanaman pohon yang dapat disesuaikan dengan target dan misi perusahaan Anda.
                        </p>
                        <!-- Features -->
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Target penanaman pohon sesuai kebutuhan</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Dokumentasi & laporan dampak</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Kustomisasi lokasi penanaman</span>
                            </li>
                        </ul>
                        <!-- CTA Button -->
                        <a href="corporation-detail.php" class="mt-auto inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:bg-primary-800 transition group/btn">
                            Pelajari Lebih Lanjut
                            <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-1 transition"></i>
                        </a>
                    </div>
                </div>

                <!-- Collaboratree Card -->
                <div class="group relative bg-white rounded-3xl overflow-hidden border-2 border-gray-100 hover:border-primary-300 transition duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-primary-50 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative p-8 flex flex-col">
                        <!-- Icon -->
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-handshake text-purple-600 text-3xl"></i>
                        </div>
                        <!-- Title & Subtitle -->
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Collaboration</h3>
                        <p class="text-primary-600 font-semibold mb-4 text-sm">Bundling Produk/Jasa</p>
                        <!-- Description -->
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            Kolaborasikan brand Anda dengan program donasi pohon untuk membangun citra yang peduli dan berkelanjutan.
                        </p>
                        <!-- Features -->
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Integrasi dengan produk/layanan Anda</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Meningkatkan brand awareness</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Pelanggan berkontribusi untuk lingkungan</span>
                            </li>
                        </ul>
                        <!-- CTA Button -->
                        <a href="collaboration-detail.php" class="mt-auto inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:bg-purple-700 transition group/btn">
                            Pelajari Lebih Lanjut
                            <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-1 transition"></i>
                        </a>
                    </div>
                </div>

                <!-- Campaign Submission Card -->
                <div class="group relative bg-white rounded-3xl overflow-hidden border-2 border-gray-100 hover:border-primary-300 transition duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-primary-50 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative p-8 flex flex-col">
                        <!-- Icon -->
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300">
                            <i class="fas fa-paper-plane text-blue-600 text-3xl"></i>
                        </div>
                        <!-- Title & Subtitle -->
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Ajukan Campaign</h3>
                        <p class="text-primary-600 font-semibold mb-4 text-sm">Program Pengajuan Campaign</p>
                        <!-- Description -->
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">
                            Mari berkolaborasi menghijaukan bumi dengan membuat campaign penanaman Anda sendiri bersama kami.
                        </p>
                        <!-- Features -->
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Buat campaign penanaman Anda sendiri</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Kelola dengan tim profesional kami</span>
                            </li>
                            <li class="flex items-start text-sm text-gray-700">
                                <i class="fas fa-check text-primary-600 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span>Dampak lingkungan berkelanjutan </span>
                            </li>
                        </ul>
                        <!-- CTA Button -->
                        <a href="deskripsi-pengajuan.php" class="mt-auto inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:bg-blue-700 transition group/btn">
                            Ajukan Campaign
                            <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-1 transition"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-3xl p-12 text-center text-white" data-aos="fade-up">
                <h3 class="text-3xl font-bold mb-4">Tertarik dengan Salah Satu Program?</h3>
                <p class="text-white/90 mb-8 text-lg">Hubungi tim kami untuk mendiskusikan program yang paling sesuai untuk Anda</p>
                <a href="mailto:info@sodakohpohon.com" class="inline-flex items-center px-8 py-4 bg-white text-primary-700 font-bold rounded-2xl hover:bg-gray-100 transition shadow-lg">
                    <i class="fas fa-envelope mr-3"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
