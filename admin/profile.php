<?php
// admin/profile.php
session_start();
require_once '../config/koneksi.php';

// Cek autentikasi
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit;
}

$conn = getDB();
$admin_id = $_SESSION['admin_id'];
$success_msg = '';
$error_msg = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($name) || empty($email)) {
        $error_msg = 'Nama dan Email tidak boleh kosong.';
    } else {
        // Cek apakah email sudah digunakan oleh user lain
        $email_esc = $conn->real_escape_string($email);
        $check_sql = "SELECT id FROM users WHERE email = '$email_esc' AND id != $admin_id";
        $check_result = $conn->query($check_sql);

        if ($check_result && $check_result->num_rows > 0) {
            $error_msg = 'Email sudah digunakan oleh akun lain.';
        } else {
            // Update name and email
            $name_esc = $conn->real_escape_string($name);
            $update_sql = "UPDATE users SET name = '$name_esc', email = '$email_esc'";
            
            // Handle password update if provided
            $update_password = false;
            if (!empty($new_password)) {
                if ($new_password !== $confirm_password) {
                    $error_msg = 'Konfirmasi password tidak cocok.';
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_sql .= ", password = '$hashed_password'";
                    $update_password = true;
                }
            }

            if (empty($error_msg)) {
                $update_sql .= " WHERE id = $admin_id AND role = 'admin'";
                if ($conn->query($update_sql)) {
                    $success_msg = 'Profil berhasil diperbarui.';
                    // Update session variables
                    $_SESSION['admin_name'] = $name;
                    $_SESSION['admin_email'] = $email;
                } else {
                    $error_msg = 'Terjadi kesalahan saat memperbarui profil: ' . $conn->error;
                }
            }
        }
    }
}

// Ambil data admin saat ini
$sql = "SELECT name, email FROM users WHERE id = $admin_id AND role = 'admin' LIMIT 1";
$result = $conn->query($sql);
$admin_data = $result->fetch_assoc();

$current_page = 'profile';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - Sodakoh Pohon</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
                            800: '#065f46',
                            900: '#064e3b',
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
    </style>
</head>

<body>
    <div class="flex h-screen bg-gray-100">
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 ml-72 overflow-y-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex justify-between items-center px-8 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Profil</h1>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-500 hover:text-primary-600 transition">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Profile Content -->
            <div class="px-8 py-6">
                <div class="max-w-2xl mx-auto">
                    <?php if (!empty($success_msg)): ?>
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?php echo htmlspecialchars($success_msg); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($error_msg)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                    <?php endif; ?>

                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex items-center space-x-6 mb-8 pb-8 border-b border-gray-100">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin_data['name']); ?>&background=059669&color=fff&size=100"
                                    alt="Admin Avatar" class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg">
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($admin_data['name']); ?></h2>
                                <p class="text-gray-500"><?php echo htmlspecialchars($admin_data['email']); ?></p>
                                <span class="inline-block mt-2 bg-primary-100 text-primary-700 text-xs px-2 py-1 rounded-full font-semibold">Administrator</span>
                            </div>
                        </div>

                        <form action="profile.php" method="POST">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Dasar</h3>
                            <div class="space-y-5 mb-8">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="text" name="name" value="<?php echo htmlspecialchars($admin_data['name']); ?>" required
                                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:border-primary-600 focus:ring-2 focus:ring-primary-100 outline-none transition">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <div class="relative">
                                        <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required
                                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:border-primary-600 focus:ring-2 focus:ring-primary-100 outline-none transition">
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-4">Ubah Password <span class="text-sm font-normal text-gray-400">(Opsional)</span></h3>
                            <div class="space-y-5 mb-8">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="password" name="new_password" placeholder="Kosongkan jika tidak ingin mengubah password"
                                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:border-primary-600 focus:ring-2 focus:ring-primary-100 outline-none transition">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        <input type="password" name="confirm_password" placeholder="Ulangi password baru"
                                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:border-primary-600 focus:ring-2 focus:ring-primary-100 outline-none transition">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4 border-t border-gray-100">
                                <button type="submit" class="bg-primary-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-600/25 flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
