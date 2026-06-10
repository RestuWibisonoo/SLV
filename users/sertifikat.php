<?php
// users/certificate.php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once '../config/koneksi.php';
$conn = getDB();

$user_email = $_SESSION['user_email'] ?? '';
$certificates = [];

if ($user_email) {
    $stmt = $conn->prepare("
        SELECT c.* 
        FROM certificates c
        JOIN donations d ON c.donation_id = d.id
        WHERE d.donor_email = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }
}
$total_certificates = count($certificates);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Digital - Sodakoh Pohon</title>
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
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
    </style>
</head>
<body class="antialiased">

    <?php include '../includes/header.php'; ?>

    <div class="max-w-4xl mx-auto px-4 py-10 pt-24 animate-fade-in">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
            <div class="flex items-center">
                <a href="profile.php" class="mr-4 text-gray-500 hover:text-primary-600 transition-colors w-10 h-10 flex items-center justify-center bg-white/50 rounded-full hover:bg-white backdrop-blur-sm shadow-sm">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Sertifikat Sedekah</h1>
            </div>
            <span class="text-sm font-semibold bg-white/80 px-4 py-2 rounded-full text-gray-600 border border-gray-200 shadow-sm backdrop-blur-md inline-flex items-center">
                <i class="fas fa-award text-primary-500 mr-2 text-lg"></i>
                Total Sertifikat: <?php echo $total_certificates; ?>
            </span>
        </div>
        
        <?php if ($total_certificates > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($certificates as $cert): ?>
                    <div class="glass-card rounded-2xl p-6 relative overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 group">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-primary-100 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600 border border-primary-100">
                                    <i class="fas fa-award text-2xl"></i>
                                </div>
                                <span class="text-xs font-bold bg-primary-100 text-primary-700 px-3 py-1 rounded-full border border-primary-200 shadow-sm">
                                    <?php echo date('d M Y', strtotime($cert['issued_at'])); ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($cert['campaign_name']); ?></h3>
                            <p class="text-sm text-gray-500 mb-4">Diberikan kepada <span class="font-semibold text-gray-700"><?php echo htmlspecialchars($cert['donor_name']); ?></span> atas partisipasi penanaman <span class="font-bold text-primary-600"><?php echo htmlspecialchars($cert['trees_count']); ?> Pohon</span>.</p>
                            
                            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-xs font-mono text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-200"><?php echo htmlspecialchars($cert['certificate_number']); ?></span>
                                <a href="../sertifikat.php?id=<?php echo $cert['id']; ?>" class="text-sm font-semibold text-primary-600 hover:text-primary-700 flex items-center group-hover:translate-x-1 transition-transform bg-primary-50 hover:bg-primary-100 px-3 py-1.5 rounded-lg">
                                    Lihat <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card rounded-2xl p-12 text-center transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-tr from-primary-100 to-primary-50 rounded-full flex items-center justify-center shadow-inner border border-white">
                    <i class="fas fa-certificate text-4xl text-primary-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum ada sertifikat</h3>
                <p class="text-gray-500 max-w-md mx-auto leading-relaxed mb-8">
                    Sertifikat digital akan otomatis muncul di sini setelah donasi Anda diverifikasi dan pohon berhasil ditanam.
                </p>
                <a href="../campaign.php" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-primary-600 transition shadow-lg shadow-primary-500/30 transform hover:scale-105 duration-200">
                    Mulai Sedekah Pohon
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>