<?php
require_once 'config/koneksi.php';

$cert_no = trim($_GET['no'] ?? '');

if (empty($cert_no)) {
    // Fallback to the latest certificate for preview purposes if no parameter is given
    $cert = db_get_row("SELECT c.*, d.donation_number, d.amount, d.campaign_id FROM certificates c JOIN donations d ON d.id = c.donation_id ORDER BY c.id DESC LIMIT 1");
    
    if (!$cert) {
        http_response_code(404);
        die('Belum ada sertifikat di database.');
    }
    
    $cert_no = $cert['certificate_number'];
} else {
    $cert_no_safe = getDB()->real_escape_string($cert_no);
    $cert = db_get_row("SELECT c.*, d.donation_number, d.amount, d.campaign_id FROM certificates c JOIN donations d ON d.id = c.donation_id WHERE c.certificate_number = '{$cert_no_safe}'");
    
    if (!$cert) {
        http_response_code(404);
        die('Sertifikat tidak ditemukan. Pastikan nomor sertifikat sudah benar.');
    }
}

// Generate URL Verifikasi
$verify_url = BASE_URL . "/sertifikat.php?no=" . urlencode($cert['certificate_number']);
// Generate URL QR Code menggunakan API
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($verify_url);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Donasi - <?= htmlspecialchars($cert['donor_name']) ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;800&family=Inter:wght@400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           PRINT STYLES — A4 Landscape exact fit
           ============================================ */
        @page { 
            size: A4 landscape; 
            margin: 0; 
        }

        @media print {
            html, body { 
                margin: 0; 
                padding: 0;
                width: 297mm;
                height: 210mm;
                background: white;
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
            .print-hidden { 
                display: none !important; 
            }
            .screen-wrapper {
                display: contents;
            }
            .cert-container { 
                box-shadow: none !important; 
                margin: 0 !important;
                border-radius: 0 !important;
                /* Exact A4 landscape dimensions */
                width: 297mm !important; 
                height: 210mm !important;
                transform: none !important;
            }
        }

        /* ============================================
           SCREEN STYLES
           ============================================ */
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .print-controls {
            display: flex;
            gap: 1rem;
            justify-content: center;
            padding: 1.5rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #059669;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
        }
        .btn-primary:hover { background-color: #047857; }

        .btn-secondary {
            background-color: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        .btn-secondary:hover { background-color: #f9fafb; }

        .btn i { margin-right: 0.5rem; }

        /* Screen wrapper: scales the cert preview to fit viewport */
        .screen-wrapper {
            display: flex;
            justify-content: center;
            padding: 0 1rem 2rem;
            overflow-x: auto;
        }

        /* ============================================
           CERTIFICATE CONTAINER
           Designed at 297mm × 210mm (A4 landscape).
           On screen we render at a fixed pixel size 
           that maintains the exact aspect ratio.
           ============================================ */
        .cert-container {
            width: 297mm;
            height: 210mm;
            background: white;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 4px;
            flex-shrink: 0;
        }

        /* Background pattern */
        .cert-bg {
            position: absolute;
            inset: 0;
            background: url('https://www.transparenttextures.com/patterns/cubes.png'), linear-gradient(135deg, #f8fafc 0%, #f0fdf4 100%);
            z-index: 1;
            opacity: 0.8;
        }

        /* Decorative borders */
        .cert-border {
            position: absolute;
            inset: 6mm;
            border: 2px solid #059669;
            z-index: 2;
            pointer-events: none;
        }

        .cert-border-inner {
            position: absolute;
            inset: 8mm;
            border: 1px solid rgba(5, 150, 105, 0.3);
            z-index: 2;
            pointer-events: none;
        }

        /* Corner accents */
        .cert-corner {
            position: absolute;
            width: 25mm;
            height: 25mm;
            z-index: 3;
        }

        .corner-tl { top: 0; left: 0; border-top: 6px solid #059669; border-left: 6px solid #059669; }
        .corner-tr { top: 0; right: 0; border-top: 6px solid #059669; border-right: 6px solid #059669; }
        .corner-bl { bottom: 0; left: 0; border-bottom: 6px solid #059669; border-left: 6px solid #059669; }
        .corner-br { bottom: 0; right: 0; border-bottom: 6px solid #059669; border-right: 6px solid #059669; }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 60mm;
            color: rgba(5, 150, 105, 0.03);
            z-index: 5;
            pointer-events: none;
        }

        /* ============================================
           CERTIFICATE CONTENT — Flexbox column layout
           that stretches to fill the container and 
           pushes the footer to the bottom.
           ============================================ */
        .cert-content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12mm 18mm 10mm;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }

        /* Header / Logo */
        .cert-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 3mm;
        }
        .cert-logo-circle {
            width: 12mm;
            height: 12mm;
            background-color: #059669;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .cert-logo-circle i {
            color: white;
            font-size: 6mm;
        }
        .cert-brand {
            font-size: 6mm;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .cert-brand-green { color: #047857; }
        .cert-brand-brown { color: #7f6042; }

        /* Certificate number */
        .cert-number {
            font-size: 3.2mm;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4mm;
        }
        .cert-number span { color: #1f2937; }

        /* Title */
        .cert-title {
            font-family: 'Playfair Display', serif;
            font-size: 10mm;
            font-weight: 800;
            color: #064e3b;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0 0 2mm;
            line-height: 1.2;
        }

        /* Subtitle */
        .cert-subtitle {
            font-size: 3.5mm;
            color: #059669;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 5mm;
        }

        /* Donor name */
        .cert-name {
            font-family: 'Great Vibes', cursive;
            font-size: 14mm;
            color: #047857;
            margin: 3mm 0;
            line-height: 1.2;
            border-bottom: 2px solid rgba(5, 150, 105, 0.2);
            padding-bottom: 3mm;
            min-width: 60%;
            text-align: center;
        }

        /* Description block */
        .cert-description {
            text-align: center;
            max-width: 200mm;
            margin-top: 3mm;
        }

        .cert-description p {
            margin: 0;
            line-height: 1.5;
        }

        .cert-desc-text {
            font-size: 3.8mm;
            color: #374151;
        }

        .cert-desc-trees {
            font-size: 5mm;
            font-weight: 700;
            color: #047857;
            margin: 2mm 0;
        }

        .cert-desc-campaign {
            font-size: 3.8mm;
            color: #374151;
        }
        .cert-desc-campaign strong {
            font-weight: 700;
        }

        /* ============================================
           FOOTER SECTION — pushed to bottom via 
           margin-top: auto on this element
           ============================================ */
        .cert-footer {
            margin-top: auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 6mm;
        }

        /* QR section (left) */
        .cert-qr {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .cert-qr-box {
            padding: 2mm;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 2mm;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 1.5mm;
        }
        .cert-qr-box img {
            width: 28mm;
            height: 28mm;
            display: block;
        }
        .cert-qr-label {
            font-size: 3.2mm;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.5mm;
        }
        .cert-qr-link {
            font-size: 2.8mm;
            color: #059669;
            text-decoration: none;
        }
        .cert-qr-link:hover {
            text-decoration: underline;
        }

        /* Date section (center) */
        .cert-date {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 6mm;
        }
        .cert-date-line {
            width: 14mm;
            height: 1px;
            background-color: #10b981;
            opacity: 0.5;
            border-radius: 1px;
            margin-bottom: 2mm;
        }
        .cert-date-label {
            font-size: 3.8mm;
            font-weight: 600;
            color: #4b5563;
        }
        .cert-date-value {
            font-size: 4.2mm;
            font-weight: 700;
            color: #1f2937;
        }

        /* Signature section (right) */
        .cert-signature {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .cert-sig-label {
            font-size: 3.8mm;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 2mm;
        }
        .cert-sig-name-cursive {
            font-family: 'Great Vibes', cursive;
            font-size: 10mm;
            color: #1f2937;
            opacity: 0.7;
            margin-bottom: 1mm;
            line-height: 1;
        }
        .cert-sig-line {
            width: 45mm;
            border-bottom: 1px solid #1f2937;
            margin-bottom: 2mm;
        }
        .cert-sig-name {
            font-size: 4.2mm;
            font-weight: 700;
            color: #1f2937;
        }
        .cert-sig-title {
            font-size: 3.2mm;
            color: #6b7280;
        }

        /* ============================================
           RESPONSIVE SCALING for small screens
           ============================================ */
        @media screen and (max-width: 1200px) {
            .screen-wrapper {
                justify-content: flex-start;
                padding: 0 0 2rem;
            }
            .cert-container {
                transform-origin: top left;
            }
        }
    </style>
</head>
<body>

    <!-- Controls (Not printed) -->
    <div class="print-hidden print-controls">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak PDF
        </button>
        <a href="<?= BASE_URL ?>/index.php" class="btn btn-secondary">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>

    <!-- Screen wrapper: handles scaling for small viewports -->
    <div class="screen-wrapper" id="screenWrapper">
        
        <!-- Certificate Container — sized exactly to A4 landscape -->
        <div class="cert-container" id="certificate">
            
            <!-- Background & Decorative borders -->
            <div class="cert-bg"></div>
            <div class="cert-border"></div>
            <div class="cert-border-inner"></div>
            <div class="cert-corner corner-tl"></div>
            <div class="cert-corner corner-tr"></div>
            <div class="cert-corner corner-bl"></div>
            <div class="cert-corner corner-br"></div>
            
            <i class="fas fa-tree watermark"></i>

            <div class="cert-content">
                <!-- Header Logo / Brand -->
                <div class="cert-header">
                    <div class="cert-logo-circle">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div class="cert-brand">
                        <span class="cert-brand-green">Sodakoh</span><span class="cert-brand-brown">Pohon</span>
                    </div>
                </div>

                <div class="cert-number">
                    No. Sertifikat: <span><?= htmlspecialchars($cert['certificate_number']) ?></span>
                </div>

                <h1 class="cert-title">Sertifikat Donasi</h1>
                <div class="cert-subtitle">Diberikan Sebagai Bentuk Penghargaan Kepada</div>

                <!-- Donor Name -->
                <h2 class="cert-name"><?= htmlspecialchars($cert['donor_name']) ?></h2>

                <!-- Description -->
                <div class="cert-description">
                    <p class="cert-desc-text">
                        Atas partisipasi aktif dan kontribusinya dalam menjaga kelestarian lingkungan hidup dengan menyedekahkan
                    </p>
                    <p class="cert-desc-trees">
                        <?= number_format($cert['trees_count']) ?> Pohon
                    </p>
                    <p class="cert-desc-campaign">
                        melalui campaign <strong>"<?= htmlspecialchars($cert['campaign_name']) ?>"</strong>
                    </p>
                </div>

                <!-- Footer Section (QR, Date, Signature) — auto-pushed to bottom -->
                <div class="cert-footer">
                    
                    <!-- Left: QR Code & Verification -->
                    <div class="cert-qr">
                        <div class="cert-qr-box">
                            <img src="<?= $qr_url ?>" alt="QR Code">
                        </div>
                        <p class="cert-qr-label">Pindai untuk verifikasi</p>
                        <a href="<?= $verify_url ?>" class="cert-qr-link">
                            sodakohpohon.site/sertifikat.php?no=<?= htmlspecialchars($cert['certificate_number']) ?>
                        </a>
                    </div>

                    <!-- Center: Date -->
                    <div class="cert-date">
                        <div class="cert-date-line"></div>
                        <p class="cert-date-label">Diterbitkan pada</p>
                        <p class="cert-date-value"><?= date('d F Y', strtotime($cert['issued_at'])) ?></p>
                    </div>

                    <!-- Right: Signature -->
                    <div class="cert-signature">
                        <p class="cert-sig-label">Disahkan Oleh</p>
                        <div class="cert-sig-name-cursive">Sodakoh Pohon</div>
                        <div class="cert-sig-line"></div>
                        <p class="cert-sig-name"><?= htmlspecialchars($cert['issued_by']) ?></p>
                        <p class="cert-sig-title">Direktur / Pengelola Campaign</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Script to scale down on small screens -->
    <script>
        function scaleCertificate() {
            const wrapper = document.getElementById('screenWrapper');
            const cert = document.getElementById('certificate');
            if (!wrapper || !cert) return;
            
            const windowWidth = window.innerWidth;
            // Get the actual rendered width of the certificate (in px)
            const certNaturalWidth = cert.offsetWidth;
            
            if (windowWidth < certNaturalWidth + 40) {
                const scale = (windowWidth - 32) / certNaturalWidth;
                cert.style.transform = `scale(${scale})`;
                cert.style.transformOrigin = 'top left';
                wrapper.style.height = `${cert.offsetHeight * scale}px`;
            } else {
                cert.style.transform = 'none';
                wrapper.style.height = 'auto';
            }
        }

        window.addEventListener('resize', scaleCertificate);
        window.addEventListener('DOMContentLoaded', scaleCertificate);
    </script>
</body>
</html>
