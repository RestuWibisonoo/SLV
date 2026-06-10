<?php
// users/riwayat-pengajuan.php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once '../config/koneksi.php';
$conn = getDB();

$user_email = $_SESSION['user_email'] ?? '';
$campaigns = [];

if ($user_email) {
    $stmt = $conn->prepare("
        SELECT id, title, target_trees, tree_type, location, status, stage, created_at 
        FROM campaign_submissions 
        WHERE submitter_email = ? 
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $campaigns[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengajuan Campaign - Sodakoh Pohon</title>
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

    <div class="max-w-6xl mx-auto px-4 py-10 pt-24 animate-fade-in">
        <div class="flex items-center mb-8">
            <a href="profile.php" class="mr-4 text-gray-500 hover:text-primary-600 transition-colors w-10 h-10 flex items-center justify-center bg-white/50 rounded-full hover:bg-white backdrop-blur-sm shadow-sm">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Pengajuan Campaign</h1>
        </div>
        
        <div class="glass-card rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
            <?php if (empty($campaigns)): ?>
                <div class="p-16 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-tr from-gray-100 to-gray-50 border border-white shadow-inner text-gray-400 mb-6">
                        <i class="fas fa-seedling text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada pengajuan</h3>
                    <p class="text-gray-500 mt-1 mb-8 max-w-sm mx-auto">Anda belum pernah mengajukan campaign penanaman pohon. Mari mulai berkontribusi lebih besar!</p>
                    <a href="../deskripsi-pengajuan.php" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-primary-600 transition shadow-lg shadow-primary-500/30 transform hover:scale-105 duration-200">
                        Buat Pengajuan Campaign
                    </a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50 text-gray-600 uppercase text-xs leading-normal border-b border-gray-200/60">
                            <tr>
                                <th class="py-4 px-6 font-bold tracking-wider">Tanggal</th>
                                <th class="py-4 px-6 font-bold tracking-wider">Campaign</th>
                                <th class="py-4 px-6 font-bold tracking-wider">Target & Lokasi</th>
                                <th class="py-4 px-6 font-bold tracking-wider">Status & Tahap</th>
                                <th class="py-4 px-6 font-bold tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-medium">
                            <?php foreach ($campaigns as $c): ?>
                                <tr class="border-b border-gray-100 hover:bg-white/60 transition duration-200">
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 text-gray-500">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                            <?php echo date('d M Y, H:i', strtotime($c['created_at'])); ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-900 font-semibold"><?php echo htmlspecialchars($c['title']); ?></td>
                                    <td class="py-4 px-6">
                                        <div class="text-xs text-gray-600"><i class="fas fa-tree mr-1 text-primary-500"></i> <?php echo number_format($c['target_trees']); ?> pohon (<?php echo htmlspecialchars($c['tree_type']); ?>)</div>
                                        <div class="text-xs text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($c['location']); ?></div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col items-start gap-2">
                                            <?php 
                                                $statusClass = 'bg-yellow-100/80 text-yellow-700 border-yellow-200';
                                                $statusText = 'Menunggu Validasi';
                                                
                                                if ($c['status'] === 'approved') {
                                                    $statusClass = 'bg-green-100/80 text-green-700 border-green-200';
                                                    $statusText = 'Disetujui';
                                                } elseif ($c['status'] === 'rejected') {
                                                    $statusClass = 'bg-red-100/80 text-red-700 border-red-200';
                                                    $statusText = 'Ditolak';
                                                }
                                                
                                                $stageText = $c['stage'] == 1 ? 'Tahap 1' : 'Tahap 2';
                                            ?>
                                            <span class="py-1 px-3 rounded-md text-xs font-bold border <?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                            </span>
                                            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                <i class="fas fa-layer-group mr-1"></i> <?php echo $stageText; ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <?php if ($c['stage'] == 1 && $c['status'] === 'approved'): ?>
                                            <a href="pengajuan-tahap2.php?id=<?php echo $c['id']; ?>" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-xs font-bold rounded-lg hover:bg-primary-700 transition shadow-sm hover:shadow">
                                                Lanjutkan ke Tahap 2 <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                        <?php elseif ($c['stage'] == 1 && $c['status'] !== 'approved'): ?>
                                            <a href="edit-pengajuan.php?id=<?php echo $c['id']; ?>" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition shadow-sm">
                                                <i class="fas fa-edit mr-2"></i> Edit
                                            </a>
                                        <?php elseif ($c['stage'] == 2 && $c['status'] === 'approved'): ?>
                                            <span class="text-xs text-green-600 font-semibold"><i class="fas fa-check-circle mr-1"></i> Dipublikasikan</span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
