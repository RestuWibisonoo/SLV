<?php
// admin-service/index.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/models/AdminCampaign.php';
require_once __DIR__ . '/models/AdminTransaction.php';
require_once __DIR__ . '/models/AdminPlanting.php';
require_once __DIR__ . '/models/AdminSubmission.php';

function jsonResponse($data, int $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($requestUri, '/');
$parts = $path === '' ? [] : explode('/', $path);
$action = end($parts) ?: '';

if ($method === 'OPTIONS') {
    jsonResponse(['success' => true, 'message' => 'OK']);
}

if ($method === 'GET' && ($action === '' || $action === 'health' || $action === 'status' || $action === 'admin')) {
    jsonResponse(['success' => true, 'message' => 'Admin Service Online']);
}

$db = Database::getInstance();

// ---- LOGIN ENDPOINT ----
if ($method === 'POST' && $action === 'login') {
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    if (!is_array($input)) $input = $_POST;

    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if ($email === '' || $password === '') {
        jsonResponse(['success' => false, 'message' => 'Email dan password harus diisi'], 400);
    }

    $userConn = $db->getUserDB();
    $stmt = $userConn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        jsonResponse(['success' => false, 'message' => 'Email tidak ditemukan atau bukan admin'], 404);
    }

    $user = $result->fetch_assoc();
    if (!password_verify($password, $user['password'])) {
        jsonResponse(['success' => false, 'message' => 'Password salah'], 401);
    }

    $update = $userConn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $update->bind_param("i", $user['id']);
    $update->execute();

    unset($user['password']);

    jsonResponse([
        'success' => true,
        'message' => 'Login berhasil',
        'data' => $user,
        'token' => base64_encode(json_encode(['id' => $user['id'], 'role' => 'admin']))
    ]);
}

$campaignModel = new AdminCampaign();
$transactionModel = new AdminTransaction();
$plantingModel = new AdminPlanting();
$submissionModel = new AdminSubmission();

// ---- DASHBOARD STATS ----
if ($method === 'GET' && $action === 'dashboard') {
    $stats = $campaignModel->getStats();
    
    $userConn = $db->getUserDB();
    $res = $userConn->query("SELECT COUNT(*) as total FROM users WHERE role='user'");
    if ($res) $stats['total_users'] = $res->fetch_assoc()['total'];

    jsonResponse(['success' => true, 'data' => $stats]);
}

// ---- GET ENDPOINTS ----
if ($method === 'GET') {
    if ($action === 'campaigns') {
        $status = $_GET['status'] ?? null;
        jsonResponse(['success' => true, 'data' => $campaignModel->getAll($status)]);
    }
    if (is_numeric($action) && in_array('campaigns', $parts)) {
        $campaign = $campaignModel->getById($action);
        if ($campaign) jsonResponse(['success' => true, 'data' => $campaign]);
        jsonResponse(['success' => false, 'message' => 'Campaign tidak ditemukan'], 404);
    }
    
    if ($action === 'donations') {
        $status = $_GET['status'] ?? null;
        jsonResponse(['success' => true, 'data' => $transactionModel->getAllDonations($status)]);
    }
    
    if ($action === 'submissions') {
        jsonResponse(['success' => true, 'data' => $submissionModel->getAll()]);
    }
    
    if ($action === 'plantings') {
        jsonResponse(['success' => true, 'data' => $plantingModel->getAll()]);
    }
    if (is_numeric($action) && in_array('plantings', $parts)) {
        $planting = $plantingModel->getById($action);
        if ($planting) jsonResponse(['success' => true, 'data' => $planting]);
        jsonResponse(['success' => false, 'message' => 'Planting tidak ditemukan'], 404);
    }
}

// ---- POST ENDPOINTS (CREATE / UPDATE via form-data) ----
if ($method === 'POST') {
    if ($action === 'campaigns') {
        $input = $_POST;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imagePath = $campaignModel->uploadImage($_FILES['image']);
            if ($imagePath) $input['image'] = $imagePath;
        }
        $resultId = $campaignModel->create($input);
        if ($resultId) {
            if (isset($input['benefits']) && is_array($input['benefits'])) {
                foreach ($input['benefits'] as $b) {
                    if (trim($b) !== '') $campaignModel->addBenefit($resultId, trim($b));
                }
            }
            jsonResponse(['success' => true, 'message' => 'Campaign berhasil dibuat', 'id' => $resultId], 201);
        }
        jsonResponse(['success' => false, 'message' => 'Gagal membuat campaign'], 500);
    }
    
    if (is_numeric($action) && in_array('campaigns', $parts)) {
        $id = $action;
        $input = $_POST;
        if (empty($input)) $input = json_decode(file_get_contents('php://input'), true) ?? [];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imagePath = $campaignModel->uploadImage($_FILES['image']);
            if ($imagePath) $input['image'] = $imagePath;
        }
        
        unset($input['benefits']);
        if ($campaignModel->update($id, $input)) {
            if (isset($_POST['benefits']) && is_array($_POST['benefits'])) {
                $campaignModel->clearBenefits($id);
                foreach ($_POST['benefits'] as $b) {
                    if (trim($b) !== '') $campaignModel->addBenefit($id, trim($b));
                }
            }
            jsonResponse(['success' => true, 'message' => 'Campaign berhasil diupdate']);
        }
        jsonResponse(['success' => false, 'message' => 'Gagal update campaign'], 500);
    }
    
    if ($action === 'plantings') {
        $input = $_POST;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imagePath = $plantingModel->uploadImage($_FILES['image']);
            if ($imagePath) $input['image'] = $imagePath;
        }
        $resultId = $plantingModel->create($input);
        if ($resultId) jsonResponse(['success' => true, 'id' => $resultId], 201);
        jsonResponse(['success' => false, 'message' => 'Gagal membuat penanaman'], 500);
    }
    
    if (is_numeric($action) && in_array('plantings', $parts)) {
        $id = $action;
        $input = $_POST;
        if (empty($input)) $input = json_decode(file_get_contents('php://input'), true) ?? [];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imagePath = $plantingModel->uploadImage($_FILES['image']);
            if ($imagePath) $input['image'] = $imagePath;
        }
        
        if ($plantingModel->update($id, $input)) {
            jsonResponse(['success' => true, 'message' => 'Penanaman berhasil diupdate']);
        }
        jsonResponse(['success' => false, 'message' => 'Gagal update penanaman'], 500);
    }
}

// ---- PUT ENDPOINTS ----
if ($method === 'PUT') {
    if (is_numeric($action) && in_array('donations', $parts)) {
        $input = json_decode(file_get_contents('php://input'), true);
        if ($transactionModel->updateStatus($action, $input['status'] ?? '')) {
            jsonResponse(['success' => true, 'message' => 'Status donasi diupdate']);
        }
        jsonResponse(['success' => false, 'message' => 'Gagal update donasi'], 500);
    }
    
    if (is_numeric($action) && in_array('submissions', $parts)) {
        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? '';
        if ($status === 'approved') {
            if ($submissionModel->approveToCampaign($action)) jsonResponse(['success' => true, 'message' => 'Pengajuan disetujui']);
        } else {
            if ($submissionModel->updateStatus($action, $status)) jsonResponse(['success' => true, 'message' => 'Status pengajuan diupdate']);
        }
        jsonResponse(['success' => false, 'message' => 'Gagal update pengajuan'], 500);
    }
    
    if ($action === 'profile') {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $new_password = $input['new_password'] ?? '';
        $adminId = $input['id'] ?? 1; 
        
        $userConn = $db->getUserDB();
        $email_esc = $userConn->real_escape_string($email);
        $name_esc = $userConn->real_escape_string($name);
        
        $sql = "UPDATE users SET name = '$name_esc', email = '$email_esc'";
        if ($new_password !== '') {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $sql .= ", password = '$hash'";
        }
        $sql .= " WHERE id = " . (int)$adminId . " AND role = 'admin'";
        
        if ($userConn->query($sql)) jsonResponse(['success' => true, 'message' => 'Profil berhasil diupdate']);
        jsonResponse(['success' => false, 'message' => 'Gagal update profil'], 500);
    }
}

// ---- DELETE ENDPOINTS ----
if ($method === 'DELETE') {
    if (is_numeric($action) && in_array('campaigns', $parts)) {
        if ($campaignModel->delete($action)) jsonResponse(['success' => true, 'message' => 'Campaign dihapus']);
        jsonResponse(['success' => false], 500);
    }
    
    if (is_numeric($action) && in_array('plantings', $parts)) {
        if ($plantingModel->delete($action)) jsonResponse(['success' => true, 'message' => 'Penanaman dihapus']);
        jsonResponse(['success' => false], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Endpoint tidak ditemukan'], 404);
