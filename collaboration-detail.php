<?php
// collaboration-detail.php
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
            <span class="text-gray-900 font-semibold">Kolaborasi</span>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- Left: Text Content -->
            <div data-aos="fade-right" data-aos-duration="900">

                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 rounded-full px-4 py-2 mb-6 text-sm font-semibold">
                    <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-handshake text-white text-xs"></i>
                    </div>
                    Program Kolaborasi & Kemitraan
                </div>

                <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                    Kolaborasi<br>
                    yang <span class="gradient-text-blue">Berdampak</span>
                </h1>

                <p class="text-xl text-gray-600 leading-relaxed mb-8">
                    Bergabunglah dalam gerakan penanaman pohon bersama komunitas, organisasi, dan mitra global — wujudkan dampak lingkungan yang nyata dan berkelanjutan.
                </p>

                <!-- Key highlights -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-users text-primary-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Komunitas Global</p>
                            <p class="text-gray-500 text-xs">Jaringan volunteer terukur</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-chart-line text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Transparansi Data</p>
                            <p class="text-gray-500 text-xs">Tracking real-time dampak</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-network-wired text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Ekosistem Kolaboratif</p>
                            <p class="text-gray-500 text-xs">Berbagi sumber daya & expertise</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-leaf text-orange-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Biodiversity Plus</p>
                            <p class="text-gray-500 text-xs">Fokus pada ekosistem lokal</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#contact-form" class="pulse-btn inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-2xl hover:from-primary-700 hover:to-primary-800 transition shadow-xl shadow-primary-600/30 text-lg">
                        <i class="fas fa-handshake mr-3"></i>
                        Mari Berkolaborasi
                    </a>
                    <a href="#packages" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-700 font-bold rounded-2xl border-2 border-gray-200 hover:border-primary-600 hover:text-primary-600 transition text-lg">
                        <i class="fas fa-tags mr-3"></i>
                        Lihat Program
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
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kolaborasi Aktif</p>
                            <h3 class="text-xl font-bold text-gray-900">Komunitas Pohon Indonesia</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-handshake text-blue-600 text-xl"></i>
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
                                <span class="text-xl font-extrabold text-primary-700">68%</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Target Kolaborasi</p>
                            <p class="text-2xl font-extrabold text-gray-900">6,800 <span class="text-sm font-normal text-gray-500">/ 10.000 pohon</span></p>
                        </div>
                    </div>

                    <!-- Stats grid -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="text-center p-3 bg-primary-50 rounded-xl">
                            <p class="text-xl font-extrabold text-primary-700">45</p>
                            <p class="text-xs text-gray-500 mt-1">Partner</p>
                        </div>
                        <div class="text-center p-3 bg-blue-50 rounded-xl">
                            <p class="text-xl font-extrabold text-blue-700">12k</p>
                            <p class="text-xs text-gray-500 mt-1">Volunteer</p>
                        </div>
                        <div class="text-center p-3 bg-orange-50 rounded-xl">
                            <p class="text-xl font-extrabold text-orange-600">52t</p>
                            <p class="text-xs text-gray-500 mt-1">CO₂ Diserap</p>
                        </div>
                    </div>
                </div>

                <!-- Floating card -->
                <div class="float-card absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl border border-gray-100 px-5 py-4 z-20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-heart text-primary-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Partnership Baru Hari Ini</p>
                            <p class="text-sm font-bold text-gray-900">+3 Organisasi 🤝</p>
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
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Apa itu Program Kolaborasi?</h2>
            <p class="text-xl text-gray-600">
                Program Kolaborasi adalah wadah bagi komunitas, organisasi sosial, lembaga pendidikan, dan mitra global 
                untuk bersama-sama menanam pohon dan menciptakan dampak lingkungan yang terukur serta berkelanjutan.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition" data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-people-arrows text-primary-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Kekuatan Kolaborasi</h3>
                <p class="text-gray-600 leading-relaxed">
                    Satu organisasi mungkin terbatas, tetapi bersama mitra dan komunitas, dampak menjadi luar biasa besar dan berkelanjutan.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-leaf text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Transparansi & Akuntabilitas</h3>
                <p class="text-gray-600 leading-relaxed">
                    Setiap pohon tercatat digital, dipantau berkala, dan dampaknya dapat diverifikasi oleh semua pihak.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-globe text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Berdampak Global, Lokal</h3>
                <p class="text-gray-600 leading-relaxed">
                    Kontribusi dari berbagai negara terkumpul untuk memperkuat ekosistem lokal yang membutuhkan.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- ===================== PACKAGES SECTION ===================== -->
<section id="packages" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <p class="section-label mb-3">Program</p>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Pilih Level Kolaborasi</h2>
            <p class="text-xl text-gray-600">Semua program dapat disesuaikan dengan kapasitas dan misi organisasi Anda.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Community -->
            <div class="package-card bg-white rounded-3xl border-2 border-gray-100 p-8" data-aos="fade-up" data-aos-delay="0">
                <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-users text-gray-600 text-2xl"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Community</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">Komunitas Lokal</h3>
                <p class="text-primary-600 font-semibold text-sm mb-6">Dari Rp 500.000</p>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Untuk komunitas grassroot yang ingin memulai gerakan penanaman pohon di lingkungan mereka.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Hingga 100 pohon</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Training volunteer dasar</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Dashboard monitoring akses</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Sertifikat partisipasi</span>
                    </li>
                </ul>
                <a href="#contact-form" class="block w-full text-center px-6 py-3 border-2 border-primary-600 text-primary-700 font-bold rounded-xl hover:bg-primary-50 transition">
                    Daftar Sekarang
                </a>
            </div>

            <!-- Organization (Featured) -->
            <div class="package-card featured bg-white rounded-3xl p-8 shadow-2xl shadow-primary-600/10 relative" data-aos="fade-up" data-aos-delay="100">
                <!-- Badge -->
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-xs font-bold px-6 py-2 rounded-full shadow-lg">
                    ⭐ Paling Banyak Dipilih
                </div>

                <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 mt-4">
                    <i class="fas fa-building text-primary-600 text-2xl"></i>
                </div>
                <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-2">Organization</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">Organisasi Mitra</h3>
                <p class="text-primary-600 font-semibold text-sm mb-6">Dari Rp 2.500.000</p>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Ideal untuk NGO, lembaga pendidikan, dan organisasi yang serius dengan program keberlanjutan.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>100 - 500 pohon</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Training komprehensif volunteer</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Dashboard analytics lanjutan</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Laporan dampak kuartalan</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-primary-600"></i>
                        <span>Dukungan media & PR</span>
                    </li>
                </ul>
                <a href="#contact-form" class="block w-full text-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-xl hover:from-primary-700 hover:to-primary-800 transition shadow-lg shadow-primary-600/25">
                    Hubungi Kami
                </a>
            </div>

            <!-- Network -->
            <div class="package-card bg-white rounded-3xl border-2 border-gray-100 p-8" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-network-wired text-blue-600 text-2xl"></i>
                </div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2">Network</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">Global Network</h3>
                <p class="text-blue-600 font-semibold text-sm mb-6">Custom Partnership</p>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Untuk jaringan organisasi multi-regional dengan program kolaborasi skala besar dan struktur kompleks.
                </p>
                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>500+ pohon di multi-lokasi</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>API integrasi ecosystem</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>Laporan ESG internasional</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>Dedicated success manager</span>
                    </li>
                    <li class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fas fa-check-circle text-blue-600"></i>
                        <span>Custom reporting suite</span>
                    </li>
                </ul>
                <a href="#contact-form" class="block w-full text-center px-6 py-3 border-2 border-blue-600 text-blue-700 font-bold rounded-xl hover:bg-blue-50 transition">
                    Hubungi Kami
                </a>
            </div>
        </div>

        <p class="text-center text-sm text-gray-400 mt-8">
            * Harga dapat berubah sesuai detail program. Semua paket dapat dikustomisasi dengan benefit tambahan.
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
                    "q" => "Siapa saja yang bisa bergabung dalam program kolaborasi?",
                    "a" => "Komunitas lokal, LSM, lembaga pendidikan, koperasi, jaringan organisasi sosial, bahkan individu atau grup informal yang punya misi lingkungan."
                ],
                [
                    "q" => "Bagaimana cara transparansi dalam pelaksanaan program?",
                    "a" => "Setiap pohon dicatat digital dengan GPS, foto dokumentasi, dan tracking pertumbuhan. Semua mitra dapat akses dashboard real-time untuk monitoring."
                ],
                [
                    "q" => "Apakah volunteer bisa terlibat langsung dalam penanaman?",
                    "a" => "Tentu! Kami mendorong volunteer langsung terlibat. Kami sediakan training, peralatan, dan panduan untuk penanaman berkualitas."
                ],
                [
                    "q" => "Bagaimana jika program memiliki target bulanan tertentu?",
                    "a" => "Bisa! Target dapat dikustomisasi per bulan, quarter, atau tahun sesuai kapasitas organisasi Anda. Fleksibilitas adalah kunci kami."
                ],
                [
                    "q" => "Apakah ada biaya operasional tersembunyi?",
                    "a" => "Tidak ada biaya tersembunyi. Semua komponen dijelaskan di depan: biaya pohon, pelatihan, monitoring, dan laporan — semuanya transparan."
                ],
                [
                    "q" => "Bagaimana dengan pemeliharaan pohon setelah ditanam?",
                    "a" => "Kami dan mitra komunitas lokal menangani pemeliharaan 1 tahun pertama. Monitoring dilanjutkan sesuai paket yang dipilih."
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
            <p class="section-label mb-3">Mari Kolaborasi</p>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Bergabunglah dengan Program Kami</h2>
            <p class="text-xl text-gray-600">Tim kami akan menghubungi Anda dalam 1×24 jam kerja untuk diskusi lebih lanjut.</p>
        </div>

        <div class="bg-gray-50 rounded-3xl border border-gray-200 p-10" data-aos="fade-up" data-aos-delay="100">
            <form method="POST" action="collaboration-submit.php" class="space-y-6">

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kontak <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Nama Anda"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Organisasi <span class="text-red-500">*</span></label>
                        <input type="text" name="organization" required placeholder="Komunitas / NGO / Sekolah ..."
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="email@organisasi.com"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon / WA</label>
                        <input type="tel" name="phone" placeholder="08xx-xxxx-xxxx"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Organisasi</label>
                    <select name="org_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="community">Komunitas Lokal</option>
                        <option value="ngo">LSM / NGO</option>
                        <option value="education">Lembaga Pendidikan</option>
                        <option value="cooperative">Koperasi</option>
                        <option value="network">Jaringan Organisasi</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Program yang Diminati</label>
                    <select name="program" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm bg-white">
                        <option value="">-- Pilih Program --</option>
                        <option value="community">Community — Hingga 100 pohon</option>
                        <option value="organization">Organization — 100-500 pohon</option>
                        <option value="network">Network — Custom Scale</option>
                        <option value="consult">Belum tahu, ingin konsultasi dulu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan / Rencana Program Kolaborasi</label>
                    <textarea name="message" rows="4" placeholder="Ceritakan tentang visi organisasi Anda dan rencana program penanaman pohon..."
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:outline-none transition text-sm resize-none bg-white"></textarea>
                </div>

                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-bold rounded-2xl hover:from-primary-700 hover:to-primary-800 transition shadow-xl shadow-primary-600/25 text-lg">
                    <i class="fas fa-handshake mr-3"></i>
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
            <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20mengetahui%20lebih%20lanjut%20tentang%20Program%20Kolaborasi"
               target="_blank"
               class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-green-500 text-white font-semibold rounded-xl hover:bg-green-600 transition shadow-lg shadow-green-500/25">
                <i class="fab fa-whatsapp text-xl"></i>
                Chat via WhatsApp
            </a>
            <a href="mailto:collaboration@sodakohpohon.com"
               class="inline-flex items-center justify-center gap-3 px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-primary-600 hover:text-primary-600 transition">
                <i class="fas fa-envelope"></i>
                collaboration@sodakohpohon.com
            </a>
        </div>
    </div>
</section>


<!-- ===================== STICKY CTA ===================== -->
<div class="sticky-cta" id="stickyCta">
    <div class="bg-white border-t-2 border-primary-600 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
            <div class="hidden sm:block">
                <p class="font-bold text-gray-900 text-sm">Program Kolaborasi</p>
                <p class="text-gray-500 text-xs">Bergabung bersama ribuan organisasi & komunitas</p>
            </div>
            <div class="flex items-center gap-3 flex-1 sm:flex-initial justify-end">
                <a href="#packages" class="px-5 py-2.5 border-2 border-primary-600 text-primary-700 font-semibold rounded-xl hover:bg-primary-50 transition text-sm whitespace-nowrap">
                    Lihat Program
                </a>
                <a href="#contact-form" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-primary-800 transition shadow-lg shadow-primary-600/25 text-sm whitespace-nowrap">
                    <i class="fas fa-handshake mr-2"></i>Kolaborasi
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
