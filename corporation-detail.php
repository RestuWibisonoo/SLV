<?php
// corporation-detail.php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php include 'includes/header.php'; ?>

<!-- Custom Styles for this page -->
<style>
    :root {
        --primary-50: #f0fdf4;
        --primary-100: #dcfce7;
        --primary-600: #16a34a;
        --primary-700: #15803d;
        --primary-800: #166534;
        --blue-50: #eff6ff;
        --blue-100: #dbeafe;
        --blue-600: #2563eb;
    }

    /* Float animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }

    /* Pulse glow */
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.3); }
        50% { box-shadow: 0 0 0 16px rgba(22, 163, 74, 0); }
    }

    .float-card {
        animation: float 4s ease-in-out infinite;
    }

    .pulse-btn {
        animation: pulse-glow 2.5s ease-in-out infinite;
    }

    .gradient-text-blue {
        background: linear-gradient(135deg, #1d4ed8, #16a34a);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-bg {
        background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 50%, #eff6ff 100%);
    }

    .package-card {
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
    }

    .package-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 60px rgba(22, 163, 74, 0.15);
    }

    .package-card.featured {
        border: 2px solid #16a34a;
        position: relative;
    }

    .faq-item summary::-webkit-details-marker {
        display: none;
    }

    .faq-item[open] summary .faq-icon {
        transform: rotate(45deg);
    }

    .faq-icon {
        transition: transform 0.3s ease;
    }

    .section-label {
        font-family: 'Courier New', monospace;
        letter-spacing: 0.15em;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #16a34a;
        font-weight: 700;
    }

    .sticky-cta {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 50;
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .sticky-cta.visible {
        transform: translateY(0);
    }
</style>

<!-- ===================== HERO SECTION ===================== -->
<section class="hero-bg relative pt-32 pb-24 overflow-hidden">

    <!-- Decorative blobs -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-100 rounded-full filter blur-3xl opacity-40 -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary-100 rounded-full filter blur-3xl opacity-50 translate-y-1/2 -translate-x-1/4"></div>

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="index.php" class="hover:text-primary-600 transition">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="index.php#programs" class="hover:text-primary-600 transition">Program</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 font-semibold">Corporation</span>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- Left: Text Content -->
            <div data-aos="fade-right" data-aos-duration="900">

                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 rounded-full px-4 py-2 mb-6 text-sm font-semibold">
                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-white text-xs"></i>
                    </div>
                    Program CSR Perusahaan
                </div>

                <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                    Program CSR<br>
                    yang <span class="gradient-text-blue">Terukur</span>
                </h1>

                <p class="text-xl text-gray-600 leading-relaxed mb-8">
                    Program CSR berbasis penanaman pohon yang dapat disesuaikan dengan target dan misi perusahaan Anda — lengkap dengan dokumentasi dan laporan dampak.
                </p>

                <!-- Key highlights -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-bullseye text-primary-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Target Fleksibel</p>
                            <p class="text-gray-500 text-xs">Sesuai kebutuhan perusahaan</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-file-alt text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Laporan Lengkap</p>
                            <p class="text-gray-500 text-xs">Dokumentasi & impact report</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-map-marked-alt text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Kustomisasi Lokasi</p>
                            <p class="text-gray-500 text-xs">Pilih lokasi penanaman</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-certificate text-orange-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Sertifikat Resmi</p>
                            <p class="text-gray-500 text-xs">Bukti kontribusi perusahaan</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#contact-form" class="pulse-btn inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-2xl hover:from-primary-700 hover:to-primary-800 transition shadow-xl shadow-primary-600/30 text-lg">
                        <i class="fas fa-handshake mr-3"></i>
                        Diskusi Program CSR
                    </a>
                    <a href="#packages" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-bold rounded-2xl border-2 border-gray-200 hover:border-primary-600 hover:text-primary-600 transition text-lg">
                        <i class="fas fa-tags mr-3"></i>
                        Lihat Paket
                    </a>
                </div>
            </div>

            <!-- Right: Visual Dashboard Card -->
            <div data-aos="fade-left" data-aos-duration="900" class="relative">

                <!-- Main card -->
                <div class="relative z-10 bg-white rounded-3xl shadow-2xl border border-gray-100 p-8">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Dashboard CSR</p>
                            <h3 class="text-xl font-bold text-gray-900">PT Contoh Perusahaan</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-building text-blue-600 text-xl"></i>
                        </div>
                    </div>

                    <!-- Progress ring area -->
                    <div class="flex items-center gap-6 mb-6 p-4 bg-gray-50 rounded-2xl">
                        <div class="relative w-24 h-24 flex-shrink-0">
                            <svg viewBox="0 0 96 96" class="w-full h-full -rotate-90">
                                <circle cx="48" cy="48" r="40" stroke="#dcfce7" stroke-width="8" fill="none"/>
                                <circle cx="48" cy="48" r="40" stroke="#16a34a" stroke-width="8" fill="none"
                                        stroke-dasharray="251.2" stroke-dashoffset="62.8"
                                        stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xl font-extrabold text-primary-700">75%</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Progress Penanaman</p>
                            <p class="text-2xl font-extrabold text-gray-900">750 <span class="text-sm font-normal text-gray-500">/ 1.000 pohon</span></p>
                        </div>
                    </div>

                    <!-- Stats grid -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="text-center p-3 bg-primary-50 rounded-xl">
                            <p class="text-xl font-extrabold text-primary-700">12</p>
                            <p class="text-xs text-gray-500 mt-1">Lokasi</p>
                        </div>
                        <div class="text-center p-3 bg-blue-50 rounded-xl">
                            <p class="text-xl font-extrabold text-blue-700">4</p>
                            <p class="text-xs text-gray-500 mt-1">Jenis Pohon</p>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-xl">
                            <p class="text-xl font-extrabold text-orange-600">18t</p>
                            <p class="text-xs text-gray-500 mt-1">CO₂ Diserap</p>
                        </div>
                    </div>
                </div>

                <!-- Floating card -->
                <div class="float-card absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl border border-gray-100 px-5 py-4 z-20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-tree text-primary-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Pohon Tertanam Hari Ini</p>
                            <p class="text-sm font-bold text-gray-900">+23 Pohon 🌳</p>
                        </div>
                    </div>
                </div>

                <!-- Decorative blur -->
                <div class="absolute -z-10 inset-0 bg-gradient-to-br from-blue-100 to-primary-100 rounded-3xl transform rotate-3 scale-105 opacity-40"></div>
            </div>
        </div>
    </div>
</section>


<!-- ===================== WHAT IS IT SECTION ===================== -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <p class="section-label mb-3">Tentang Program</p>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Apa itu Program Corporation?</h2>
            <p class="text-xl text-gray-600">
                Program CSR (Corporate Social Responsibility) berbasis penanaman pohon yang dirancang khusus untuk perusahaan — 
                dari UMKM hingga korporasi besar — yang ingin memberikan dampak lingkungan nyata dan terukur.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition" data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-handshake text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Meningkatkan Reputasi Brand</h3>
                <p class="text-gray-600 leading-relaxed">
                    Tampilkan komitmen lingkungan perusahaan Anda dengan bukti nyata yang dapat dipublikasikan.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Memenuhi Regulasi ESG</h3>
                <p class="text-gray-600 leading-relaxed">
                    Laporan impact kami dapat digunakan sebagai bukti pemenuhan kewajiban CSR yang diakui secara hukum.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Libatkan Karyawan</h3>
                <p class="text-gray-600 leading-relaxed">
                    Program kunjungan lokasi dan penanaman bersama sebagai kegiatan team building bermakna.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- ===================== PACKAGES SECTION ===================== -->
<section id="packages" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <p class="section-label mb-3">Paket</p>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Pilih Paket yang Tepat</h2>
            <p class="text-xl text-gray-600">Semua paket dapat dikustomisasi sesuai kebutuhan perusahaan Anda.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Starter -->
            <div class="package-card bg-white rounded-3xl border-2 border-gray-100 p-8" data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-seedling text-gray-600 text-2xl"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Starter</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">100 Pohon</h3>
                <p class="text-primary-600 font-semibold text-sm mb-6">Mulai dari Rp 5.000.000</p>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Ideal untuk UMKM yang ingin memulai perjalanan CSR dengan langkah bermakna.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>100 pohon pilihan kami</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Dokumentasi foto & video</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Laporan dampak digital</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Sertifikat perusahaan</span>
                    </li>
                </ul>
                <a href="#contact-form" class="block w-full text-center px-6 py-3 border-2 border-primary-600 text-primary-700 font-bold rounded-xl hover:bg-primary-50 transition">
                    Pilih Paket Ini
                </a>
            </div>

            <!-- Business (Featured) -->
            <div class="package-card featured bg-white rounded-3xl p-8 shadow-2xl shadow-primary-600/10 relative" data-aos="fade-up" data-aos-delay="100">
                <!-- Badge -->
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-xs font-bold px-6 py-2 rounded-full shadow-lg">
                    ⭐ Paling Populer
                </div>

                <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 mt-4">
                    <i class="fas fa-tree text-primary-600 text-2xl"></i>
                </div>
                <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-2">Business</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">500 Pohon</h3>
                <p class="text-primary-600 font-semibold text-sm mb-6">Mulai dari Rp 22.500.000</p>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Cocok untuk perusahaan menengah dengan program CSR berdampak signifikan.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>500 pohon pilihan Anda</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Dokumentasi foto & video HD</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Laporan cetak & digital</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Sertifikat + plakat lokasi</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>1× kunjungan lokasi</span>
                    </li>
                </ul>
                <a href="#contact-form" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition shadow-lg shadow-primary-600/25">
                    Pilih Paket Ini
                </a>
            </div>

            <!-- Enterprise -->
            <div class="package-card bg-white rounded-3xl border-2 border-gray-100 p-8" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-building text-blue-600 text-2xl"></i>
                </div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2">Enterprise</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">Custom</h3>
                <p class="text-blue-600 font-semibold text-sm mb-6">Harga Negosiasi</p>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Untuk korporasi dengan kebutuhan khusus — skala ribuan pohon, multi-lokasi, pelaporan ESG lanjutan.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>Jumlah tidak terbatas</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>Tim dokumentasi dedicated</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>Laporan ESG standar internasional</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>Dashboard monitoring khusus</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>Dedicated account manager</span>
                    </li>
                </ul>
                <a href="#contact-form" class="block w-full text-center px-6 py-3 border-2 border-blue-600 text-blue-700 font-bold rounded-xl hover:bg-blue-50 transition">
                    Hubungi Kami
                </a>
            </div>
        </div>

        <p class="text-center text-sm text-gray-400 mt-8">
            * Harga belum termasuk pajak. Semua paket dapat dikustomisasi. Hubungi kami untuk penawaran khusus.
        </p>
    </div>
</section>


<!-- ===================== FAQ ===================== -->
<section class="py-24 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <p class="section-label mb-3">FAQ</p>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
        </div>

        <div class="space-y-4">
            <?php
            $faqs = [
                [
                    "q" => "Berapa minimum pohon yang bisa dipesan?",
                    "a" => "Minimum pemesanan adalah 100 pohon untuk paket Starter. Untuk kebutuhan lebih kecil, hubungi tim kami untuk diskusi lebih lanjut."
                ],
                [
                    "q" => "Apakah bisa memilih jenis pohon dan lokasi?",
                    "a" => "Ya! Di paket Business dan Enterprise, Anda dapat memilih jenis pohon dan lokasi penanaman. Tim kami akan memberikan rekomendasi berdasarkan kecocokan ekosistem."
                ],
                [
                    "q" => "Berapa lama proses dari konsultasi hingga penanaman?",
                    "a" => "Rata-rata 2-4 minggu setelah MoU ditandatangani dan pembayaran diterima. Untuk paket Enterprise dengan skala besar, timeline dapat bervariasi."
                ],
                [
                    "q" => "Apakah laporan bisa digunakan untuk laporan tahunan?",
                    "a" => "Tentu! Laporan dampak kami dirancang untuk dapat digunakan dalam laporan tahunan, presentasi stakeholder, maupun pelaporan ESG perusahaan."
                ],
                [
                    "q" => "Apakah ada jaminan pohon akan tumbuh?",
                    "a" => "Kami memiliki komitmen bahwa setiap pohon yang tidak tumbuh dalam 3 bulan pertama akan diganti tanpa biaya tambahan."
                ],
            ];
            foreach($faqs as $i => $faq):
            ?>
            <details class="faq-item border border-gray-200 rounded-2xl overflow-hidden group" data-aos="fade-up" data-aos-delay="<?php echo $i * 50; ?>">
                <summary class="flex items-center justify-between p-6 cursor-pointer select-none hover:bg-gray-50 transition">
                    <span class="font-semibold text-gray-900 pr-8"><?php echo htmlspecialchars($faq['q']); ?></span>
                    <i class="fas fa-plus faq-icon text-primary-600 flex-shrink-0 text-sm"></i>
                </summary>
                <div class="px-6 pb-6 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                    <?php echo htmlspecialchars($faq['a']); ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ===================== CONTACT FORM ===================== -->
<section id="contact-form" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <p class="section-label mb-3">Mulai Sekarang</p>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Diskusikan Program CSR Anda</h2>
            <p class="text-xl text-gray-600">Tim kami akan menghubungi Anda dalam 1×24 jam kerja.</p>
        </div>

        <div class="bg-gray-50 rounded-3xl border border-gray-200 p-10" data-aos="fade-up" data-aos-delay="100">
            <form method="POST" action="corporation-submit.php" class="space-y-6">

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama PIC <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Nama Anda"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="company" required placeholder="PT / CV / UD ..."
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="email@perusahaan.com"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon / WA</label>
                        <input type="tel" name="phone" placeholder="08xx-xxxx-xxxx"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Paket yang Diminati</label>
                    <select name="package" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                        <option value="">-- Pilih Paket --</option>
                        <option value="starter">Starter — 100 Pohon</option>
                        <option value="business">Business — 500 Pohon</option>
                        <option value="enterprise">Enterprise — Custom</option>
                        <option value="custom">Belum tahu, ingin konsultasi dulu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan / Kebutuhan Khusus</label>
                    <textarea name="message" rows="4" placeholder="Ceritakan tentang kebutuhan CSR perusahaan Anda..."
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm resize-none bg-white"></textarea>
                </div>

                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-2xl hover:from-primary-700 hover:to-primary-800 transition shadow-xl shadow-primary-600/25 text-lg">
                    <i class="fas fa-paper-plane mr-3"></i>
                    Kirim & Diskusi Bersama Tim Kami
                </button>

                <p class="text-center text-xs text-gray-400">
                    <i class="fas fa-shield-alt mr-1 text-primary-600"></i>
                    Informasi Anda aman dan tidak akan dibagikan ke pihak ketiga.
                </p>
            </form>
        </div>

        <!-- Alternative contact -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center text-center">
            <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20mengetahui%20lebih%20lanjut%20tentang%20Program%20Corporation"
               target="_blank"
               class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-green-500 text-white font-semibold rounded-xl hover:bg-green-600 transition shadow-lg shadow-green-500/25">
                <i class="fab fa-whatsapp text-xl"></i>
                Chat via WhatsApp
            </a>
            <a href="mailto:csr@sodakohpohon.com"
               class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-primary-600 hover:text-primary-600 transition">
                <i class="fas fa-envelope"></i>
                csr@sodakohpohon.com
            </a>
        </div>
    </div>
</section>


<!-- ===================== STICKY CTA ===================== -->
<div class="sticky-cta" id="stickyCta">
    <div class="bg-white border-t-2 border-primary-600 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
            <div class="hidden sm:block">
                <p class="font-bold text-gray-900 text-sm">Program Corporation</p>
                <p class="text-gray-500 text-xs">Mulai dari 100 pohon · Laporan lengkap</p>
            </div>
            <div class="flex items-center gap-3 flex-1 sm:flex-initial justify-end">
                <a href="#packages" class="px-5 py-2.5 border-2 border-primary-600 text-primary-700 font-semibold rounded-xl hover:bg-primary-50 transition text-sm whitespace-nowrap">
                    Lihat Paket
                </a>
                <a href="#contact-form" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-primary-800 transition shadow-lg shadow-primary-600/25 text-sm whitespace-nowrap">
                    <i class="fas fa-handshake mr-2"></i>Diskusi
                </a>
            </div>
        </div>
    </div>
</div>


<script>
    // Sticky CTA
    const stickyCta = document.getElementById('stickyCta');
    const heroSection = document.querySelector('section');

    window.addEventListener('scroll', () => {
        const heroBottom = heroSection.getBoundingClientRect().bottom;
        if (heroBottom < 0) {
            stickyCta.classList.add('visible');
        } else {
            stickyCta.classList.remove('visible');
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>