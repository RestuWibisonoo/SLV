<?php
// users/history.php
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once '../config/koneksi.php';
$conn = getDB();

$user_email = $_SESSION['user_email'] ?? '';
$donations = [];

if ($user_email) {
    $stmt = $conn->prepare("
        SELECT d.created_at as date, c.title as campaign, d.amount, d.status 
        FROM donations d 
        JOIN campaigns c ON d.campaign_id = c.id 
        WHERE d.donor_email = ? 
        ORDER BY d.created_at DESC
    ");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $status_map = [
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Berhasil',
        'failed' => 'Gagal',
        'cancelled' => 'Dibatalkan'
    ];
    
    while ($row = $result->fetch_assoc()) {
        $row['status'] = $status_map[$row['status']] ?? $row['status'];
        $donations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Donasi - Sodakoh Pohon</title>
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

    <div class="max-w-5xl mx-auto px-4 py-10 pt-24 animate-fade-in">
        <div class="flex items-center mb-8">
            <a href="profile.php" class="mr-4 text-gray-500 hover:text-primary-600 transition-colors w-10 h-10 flex items-center justify-center bg-white/50 rounded-full hover:bg-white backdrop-blur-sm shadow-sm">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Histori Sedekah</h1>
        </div>
        
        <div class="glass-card rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
            <?php if (empty($donations)): ?>
                <div class="p-16 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-tr from-gray-100 to-gray-50 border border-white shadow-inner text-gray-400 mb-6">
                        <i class="fas fa-receipt text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada donasi</h3>
                    <p class="text-gray-500 mt-1 mb-8 max-w-sm mx-auto">Mulai sedekah pohon pertama Anda sekarang untuk memberikan dampak nyata.</p>
                    <a href="../campaign.php" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold rounded-xl hover:from-primary-700 hover:to-primary-600 transition shadow-lg shadow-primary-500/30 transform hover:scale-105 duration-200">
                        Lihat Campaign Penanaman
                    </a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50 text-gray-600 uppercase text-xs leading-normal border-b border-gray-200/60">
                            <tr>
                                <th class="py-4 px-6 font-bold tracking-wider">Tanggal</th>
                                <th class="py-4 px-6 font-bold tracking-wider">Campaign</th>
                                <th class="py-4 px-6 font-bold tracking-wider">Jumlah</th>
                                <th class="py-4 px-6 font-bold tracking-wider">Status</th>
                                <th class="py-4 px-6 font-bold tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-medium">
                            <?php foreach ($donations as $d): ?>
                                <tr class="border-b border-gray-100 hover:bg-white/60 transition duration-200">
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 text-gray-500">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                            <?php echo date('d M Y', strtotime($d['date'])); ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-900 font-semibold"><?php echo htmlspecialchars($d['campaign']); ?></td>
                                    <td class="py-4 px-6 text-primary-700 font-bold">Rp <?php echo number_format($d['amount'], 0, ',', '.'); ?></td>
                                    <td class="py-4 px-6">
                                        <span class="bg-green-100/80 text-green-700 py-1.5 px-4 rounded-full text-xs font-bold tracking-wide border border-green-200">
                                            <?php echo $d['status']; ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="#" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-primary-50 text-gray-600 hover:text-primary-600 text-xs font-bold rounded-lg transition-colors group">
                                            Detail <i class="fas fa-chevron-right ml-2 text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                        </a>
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