<?php
// admin/pengajuan.php - Pengajuan Campaign
session_start();
require_once '../config/koneksi.php';
require_once '../models/CampaignSubmission.php';

// Cek autentikasi
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

$submissionModel = new CampaignSubmission();

// Filter by status and stage
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$stage_filter = isset($_GET['stage']) ? (int)$_GET['stage'] : 1;

// Ambil data pengajuan dari database
$status_param = ($status_filter != 'all') ? $status_filter : null;
$submissions = $submissionModel->getAll($status_param, $stage_filter);

// Calculate totals
$stats = $submissionModel->getStats();
$total_submissions = $stats['total_submissions'];
$pending_count = $stats['pending_submissions'];
$approved_count = $stats['approved_submissions'];
$rejected_count = $stats['rejected_submissions'];

$current_page = 'pengajuan';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Campaign - Sodakoh Pohon</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .sidebar-link {
            transition: all 0.2s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(5, 150, 105, 0.1);
            color: #059669;
        }

        .sidebar-link.active {
            background-color: #059669;
            color: white;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .table-header {
            background-color: #f9fafb;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background-color: #f9fafb;
        }
    </style>
</head>

<body>
    <div class="flex h-screen bg-gray-100">
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 ml-72 overflow-y-auto">
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex justify-between items-center px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Pengajuan Campaign</h1>

                    <div class="flex items-center space-x-4">
                        <button class="relative p-2 text-gray-500 hover:text-primary-600 transition">
                            <i class="fas fa-bell text-xl"></i>
                        </button>
                        <div class="flex items-center text-sm text-gray-600 bg-gray-100 rounded-xl px-4 py-2">
                            <i class="fas fa-calendar-alt mr-2 text-primary-600"></i>
                            <?php echo date('d F Y'); ?>
                        </div>
                    </div>
                </div>
            </header>

            <div class="px-8 py-6">
                <!-- Tabs for Stage 1 and Stage 2 -->
                <div class="flex space-x-4 mb-6">
                    <a href="pengajuan.php?stage=1" class="px-6 py-3 rounded-xl font-semibold transition <?php echo $stage_filter == 1 ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50'; ?>">
                        Pengajuan Awal
                    </a>
                    <a href="pengajuan.php?stage=2" class="px-6 py-3 rounded-xl font-semibold transition <?php echo $stage_filter == 2 ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50'; ?>">
                        Pengajuan Akhir
                    </a>
                </div>
                
                <!-- Stats Summary -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file-alt text-primary-600 text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500">Total</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Total Pengajuan</p>
                        <p class="text-2xl font-extrabold text-gray-900">
                            <?php echo number_format($total_submissions); ?>
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Menunggu</p>
                        <p class="text-2xl font-extrabold text-yellow-600">
                            <?php echo number_format($pending_count); ?>
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Disetujui</p>
                        <p class="text-2xl font-extrabold text-green-600">
                            <?php echo number_format($approved_count); ?>
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Ditolak</p>
                        <p class="text-2xl font-extrabold text-red-600">
                            <?php echo number_format($rejected_count); ?>
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="flex-1 relative">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" placeholder="Cari nama pengaju, judul campaign..." id="searchInput"
                                class="pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:border-primary-600 focus:ring-2 focus:ring-primary-100 outline-none w-full">
                        </div>

                        <div class="flex items-center gap-3">
                            <select id="statusFilter"
                                class="px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-600 focus:ring-2 focus:ring-primary-100 outline-none">
                                <option value="all" <?php echo $status_filter=='all' ? 'selected' : ''; ?>>Semua Status</option>
                                <option value="pending" <?php echo $status_filter=='pending' ? 'selected' : ''; ?>>Menunggu</option>
                                <option value="approved" <?php echo $status_filter=='approved' ? 'selected' : ''; ?>>Disetujui</option>
                                <option value="rejected" <?php echo $status_filter=='rejected' ? 'selected' : ''; ?>>Ditolak</option>
                            </select>

                            <button onclick="resetFilters()"
                                class="px-4 py-3 text-gray-600 hover:text-gray-900 border border-gray-200 rounded-xl hover:bg-gray-50 transition"
                                title="Reset Filter">
                                <i class="fas fa-undo-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submissions Table -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left">Pengaju</th>
                                    <th class="px-6 py-4 text-left">Judul Campaign</th>
                                    <th class="px-6 py-4 text-left">Lokasi</th>
                                    <th class="px-6 py-4 text-left">Target Pohon</th>
                                    <th class="px-6 py-4 text-left">Status</th>
                                    <th class="px-6 py-4 text-left">Tanggal</th>
                                    <th class="px-6 py-4 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($submissions)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-inbox text-gray-300 text-3xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-lg font-medium mb-1">Belum ada pengajuan</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($submissions as $sub): ?>
                                <tr class="table-row submission-row"
                                    data-search="<?php echo htmlspecialchars(strtolower($sub['submitter_name'] . ' ' . $sub['title'] . ' ' . $sub['organization_name'])); ?>">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-gray-900"><?php echo htmlspecialchars($sub['submitter_name']); ?></span>
                                            <span class="text-xs text-gray-500"><?php echo htmlspecialchars($sub['organization_name'] ?: 'Individu'); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($sub['title']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?php echo htmlspecialchars($sub['location']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-gray-900">
                                            <?php echo number_format($sub['target_trees']); ?> pohon
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $status = $sub['status'];
                                        $statusClass = 'status-pending';
                                        $statusIcon = 'clock';
                                        $statusLabel = 'Menunggu';
                                        if ($status == 'approved') {
                                            $statusClass = 'status-approved';
                                            $statusIcon = 'check-circle';
                                            $statusLabel = 'Disetujui';
                                        } elseif ($status == 'rejected') {
                                            $statusClass = 'status-rejected';
                                            $statusIcon = 'times-circle';
                                            $statusLabel = 'Ditolak';
                                        }
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <i class="fas fa-<?php echo $statusIcon; ?> mr-1"></i>
                                            <?php echo $statusLabel; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?php echo date('d/m/Y', strtotime($sub['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="viewDetail(<?php echo $sub['id']; ?>)"
                                                class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="deleteSubmission(<?php echo $sub['id']; ?>)"
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Hapus Pengajuan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <span class="text-sm text-gray-600">
                            Menampilkan <?php echo count($submissions); ?> pengajuan
                        </span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const submissionsData = <?php echo json_encode($submissions); ?>;

        function viewDetail(dbId) {
            const sub = submissionsData.find(s => s.id == dbId);

            if (sub) {
                const statusMap = {
                    'approved': '<span class="status-badge status-approved"><i class="fas fa-check-circle mr-1"></i>Disetujui</span>',
                    'pending': '<span class="status-badge status-pending"><i class="fas fa-clock mr-1"></i>Menunggu</span>',
                    'rejected': '<span class="status-badge status-rejected"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>'
                };

                let actionButtons = '';
                if (sub.status === 'pending') {
                    let accBtnText = sub.stage == 2 ? 'Setujui & Publikasikan' : 'Setujui';
                    actionButtons = `
                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100">
                            <button onclick="updateStatus(${sub.id}, 'rejected')" class="px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-lg hover:bg-red-100 transition">Tolak</button>
                            <button onclick="updateStatus(${sub.id}, 'approved')" class="px-4 py-2 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition">${accBtnText}</button>
                        </div>
                    `;
                } else if (sub.status === 'approved') {
                    if (sub.stage == 1) {
                        actionButtons = `
                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100">
                                <button onclick="updateStatus(${sub.id}, 'rejected')" class="px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-lg hover:bg-red-100 transition">Batalkan Persetujuan</button>
                            </div>
                        `;
                    } else {
                        actionButtons = `
                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100">
                                <span class="text-green-600 font-semibold"><i class="fas fa-check-circle"></i> Sudah Dipublikasikan</span>
                            </div>
                        `;
                    }
                }

                // Format wa number
                let phone = sub.submitter_phone.trim();
                if (phone.startsWith('0')) {
                    phone = '62' + phone.substring(1);
                } else if (phone.startsWith('+')) {
                    phone = phone.substring(1);
                }
                
                let waTemplate = `Assalamualaikum Bapak/Ibu/Saudara ${sub.submitter_name},\n\nKami dari admin platform *Sodakoh Pohon*.\nKami telah menerima pengajuan campaign penanaman berjudul *'${sub.title}'*.\n\nTerkait pengajuan tersebut, kami ingin...`;

                let imageHtml = '';
                if (sub.image) {
                    let images = [];
                    try {
                        let parsed = JSON.parse(sub.image);
                        if (Array.isArray(parsed)) {
                            images = parsed;
                        } else {
                            images = [sub.image];
                        }
                    } catch (e) {
                        // Fallback for single image string from old records
                        images = [sub.image];
                    }

                    if (images.length > 0) {
                        let gridHtml = images.map(img => {
                            let imgPath = img.startsWith('http') ? img : '../' + img;
                            return `
                                <div class="rounded-lg overflow-hidden bg-gray-100 border border-gray-200 h-32">
                                    <img src="${imgPath}" alt="Ilustrasi" class="w-full h-full object-cover">
                                </div>
                            `;
                        }).join('');

                        let gridCols = images.length > 1 ? 'grid-cols-2 gap-3' : 'grid-cols-1';
                        
                        imageHtml = `
                            <div class="h-full">
                                <h4 class="font-semibold text-gray-900 mb-3 border-b border-gray-200 pb-2">Foto Lokasi / Ilustrasi</h4>
                                <div class="grid ${gridCols} h-full content-start">
                                    ${gridHtml}
                                </div>
                            </div>
                        `;
                    }
                }

                Swal.fire({
                    title: 'Detail Pengajuan Campaign',
                    html: `
                        <div class="text-left">
                            <div class="flex flex-col lg:flex-row gap-4 mb-4">
                                <!-- Kolom Kiri: Info -->
                                <div class="flex-1 space-y-4">
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                        <h4 class="font-semibold text-gray-900 mb-3 border-b border-gray-200 pb-2">Informasi Pengaju</h4>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <p class="text-gray-500">Nama:</p>
                                            <p class="font-medium">${sub.submitter_name}</p>
                                            <p class="text-gray-500">Email:</p>
                                            <p>${sub.submitter_email}</p>
                                            <p class="text-gray-500 flex items-center">Telepon:</p>
                                            <div class="flex items-center space-x-2">
                                                <span>${sub.submitter_phone}</span>
                                                <a href="https://wa.me/${phone}?text=${encodeURIComponent(waTemplate)}" target="_blank" class="inline-flex items-center justify-center w-7 h-7 bg-[#25D366] bg-opacity-10 text-[#25D366] rounded-md hover:bg-opacity-20 transition" title="Hubungi via WhatsApp">
                                                    <i class="fab fa-whatsapp text-lg"></i>
                                                </a>
                                            </div>
                                            <p class="text-gray-500">Organisasi/Komunitas:</p>
                                            <p>${sub.organization_name || '-'}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 rounded-xl border border-gray-100">
                                        <h4 class="font-semibold text-gray-900 mb-3 border-b border-gray-200 pb-2">Detail Campaign</h4>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <p class="text-gray-500">Judul:</p>
                                            <p class="font-medium">${sub.title}</p>
                                            <p class="text-gray-500">Lokasi:</p>
                                            <p>${sub.location}</p>
                                            <p class="text-gray-500">Jenis Pohon:</p>
                                            <p>${sub.tree_type}</p>
                                            <p class="text-gray-500">Target Pohon:</p>
                                            <p class="font-semibold">${sub.target_trees} pohon</p>
                                            <p class="text-gray-500">Status:</p>
                                            <div>${statusMap[sub.status]}</div>
                                            <p class="text-gray-500">Tanggal Pengajuan:</p>
                                            <p>${sub.created_at}</p>
                                            
                                            ${sub.stage == 2 ? `
                                            <div class="col-span-2 border-t border-gray-100 my-2"></div>
                                            <p class="text-gray-500">Harga per Pohon:</p>
                                            <p class="font-semibold">Rp ${parseFloat(sub.price_per_tree).toLocaleString('id-ID')}</p>
                                            <p class="text-gray-500">Deadline:</p>
                                            <p>${sub.deadline || '-'}</p>
                                            <p class="text-gray-500">Kategori:</p>
                                            <p>${sub.category || '-'}</p>
                                            <p class="text-gray-500">Mitra:</p>
                                            <p>${sub.partner || '-'}</p>
                                            <p class="text-gray-500">Link Maps:</p>
                                            <p>${sub.map_url ? `<a href="${sub.map_url}" target="_blank" class="text-primary-600 hover:underline">Buka Maps</a>` : '-'}</p>
                                            ` : ''}
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-xs text-gray-500 mb-1">Deskripsi Singkat:</p>
                                            <p class="text-sm bg-gray-50 p-3 rounded-lg">${sub.description.replace(/\\n/g, '<br>')}</p>
                                        </div>
                                        ${sub.stage == 2 && sub.long_description ? `
                                        <div class="mt-4">
                                            <p class="text-xs text-gray-500 mb-1">Deskripsi Lengkap:</p>
                                            <p class="text-sm bg-gray-50 p-3 rounded-lg max-h-32 overflow-y-auto">${sub.long_description.replace(/\\n/g, '<br>')}</p>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                <!-- Kolom Kanan: Foto -->
                                ${imageHtml ? `
                                <div class="w-full lg:w-72 flex-shrink-0 p-4 rounded-xl border border-gray-100 bg-gray-50/50">
                                    ${imageHtml}
                                </div>
                                ` : ''}
                            </div>
                            
                            ${actionButtons}
                        </div>
                    `,
                    showConfirmButton: false,
                    showCloseButton: true,
                    width: '900px'
                });
            }
        }

        function updateStatus(id, status) {
            let title = status === 'approved' ? 'Setujui Pengajuan?' : 'Tolak Pengajuan?';
            let icon = status === 'approved' ? 'question' : 'warning';
            let confirmBtnColor = status === 'approved' ? '#059669' : '#ef4444';

            Swal.fire({
                title: title,
                text: 'Apakah Anda yakin ingin mengubah status pengajuan ini?',
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('status', status);

                    fetch('../controllers/adminController.php?action=update_submission_status', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Sukses!', data.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
                    });
                }
            });
        }

        function deleteSubmission(id) {
            Swal.fire({
                title: 'Hapus Pengajuan?',
                text: 'Data pengajuan ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('id', id);

                    fetch('../controllers/adminController.php?action=delete_submission', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Terhapus!', data.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
                    });
                }
            });
        }

        // Apply filters
        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const urlParams = new URLSearchParams(window.location.search);
            let stage = urlParams.get('stage') || '1';
            
            let url = 'pengajuan.php?stage=' + stage;
            if (status !== 'all') url += '&status=' + status;
            window.location.href = url;
        }

        document.getElementById('statusFilter').addEventListener('change', applyFilters);

        function resetFilters() {
            const urlParams = new URLSearchParams(window.location.search);
            let stage = urlParams.get('stage') || '1';
            window.location.href = 'pengajuan.php?stage=' + stage;
        }

        // Client-side search
        document.getElementById('searchInput').addEventListener('input', function () {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('.submission-row');

            rows.forEach(row => {
                const searchData = row.dataset.search || '';
                row.style.display = searchData.includes(term) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
