<?php
// index.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require_once 'config/koneksi.php';
require_once 'models/Campaign.php';
require_once 'models/CampaignSubmission.php';

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
// Misal request URI: /api/campaign/list atau /campaign/list tergantung gateway config
// API gateway akan me-rewrite ke /index.php atau tidak. 
// Lebih aman gunakan query param action atau parsing string.
$path = rtrim($requestUri, '/');
$pathParts = explode('/', $path);
$action = end($pathParts);

$campaignModel = new Campaign();
$submissionModel = new CampaignSubmission();

if ($method === 'GET') {
    if ($action === 'list' || $action === 'campaign') {
        $status = $_GET['status'] ?? null;
        $limit = $_GET['limit'] ?? null;
        $campaigns = $campaignModel->getAll($status, $limit);
        echo json_encode([
            'success' => true,
            'data' => $campaigns,
            'total' => count($campaigns)
        ]);
        exit;
    }
    
    if (is_numeric($action) || isset($_GET['id'])) {
        $id = is_numeric($action) ? $action : $_GET['id'];
        $campaign = $campaignModel->getById($id);
        if ($campaign) {
            echo json_encode(['success' => true, 'data' => $campaign]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Campaign tidak ditemukan']);
        }
        exit;
    }
    
    if ($action === 'stats') {
        $stats = $campaignModel->getStats();
        echo json_encode(['success' => true, 'data' => $stats]);
        exit;
    }
    
    if ($action === 'submissions') {
        $submissions = $submissionModel->getAll();
        echo json_encode(['success' => true, 'data' => $submissions]);
        exit;
    }
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
    if ($action === 'submissions') {
        $result = $submissionModel->create($input);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Pengajuan berhasil', 'id' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Pengajuan gagal']);
        }
        exit;
    }
}

// Fallback if not found
http_response_code(404);
echo json_encode(['success' => false, 'message' => 'Endpoint tidak ditemukan']);
?>
