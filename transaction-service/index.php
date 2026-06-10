<?php
// index.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

require_once 'config/koneksi.php';
require_once 'models/Cart.php';
require_once 'models/Donation.php';

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
$path = rtrim($requestUri, '/');
$pathParts = explode('/', $path);
$action = end($pathParts);

// Start session because Cart might rely on it, though in REST APIs we should use tokens.
// Keeping session start for compatibility with existing Cart.php if it uses $_SESSION.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cartModel = new Cart();
$donationModel = new Donation();

if ($method === 'GET') {
    if ($action === 'cart') {
        $summary = $cartModel->getSummary();
        $items = $cartModel->getItems();
        echo json_encode([
            'success' => true,
            'data' => [
                'summary' => $summary,
                'items' => $items
            ]
        ]);
        exit;
    }
    
    if ($action === 'donations') {
        $status = $_GET['status'] ?? null;
        $limit = $_GET['limit'] ?? null;
        $donations = $donationModel->getAll($status, $limit);
        echo json_encode(['success' => true, 'data' => $donations]);
        exit;
    }
    
    if (is_numeric($action) || isset($_GET['id'])) {
        // Assume donations/{id}
        $id = is_numeric($action) ? $action : $_GET['id'];
        $donation = $donationModel->getById($id);
        if ($donation) {
            echo json_encode(['success' => true, 'data' => $donation]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Donation tidak ditemukan']);
        }
        exit;
    }
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
    if ($action === 'cart') {
        $campaign_id = $input['campaign_id'] ?? 0;
        $quantity = $input['quantity'] ?? 1;
        
        $result = $cartModel->addItem($campaign_id, $quantity);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Item ditambahkan ke keranjang']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan item']);
        }
        exit;
    }
    
    if ($action === 'checkout') {
        $result = $donationModel->create($input);
        if ($result) {
            $cartModel->clearCart();
            echo json_encode(['success' => true, 'message' => 'Checkout berhasil', 'donation_id' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Checkout gagal']);
        }
        exit;
    }
    
    if ($action === 'confirm-payment') {
        $id = $input['id'] ?? 0;
        $result = $donationModel->updateStatus($id, 'paid');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Pembayaran dikonfirmasi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal konfirmasi pembayaran']);
        }
        exit;
    }
}

if ($method === 'DELETE') {
    if ($action === 'cart') {
        $cartModel->clearCart();
        echo json_encode(['success' => true, 'message' => 'Keranjang dibersihkan']);
        exit;
    }
}

// Fallback
http_response_code(404);
echo json_encode(['success' => false, 'message' => 'Endpoint tidak ditemukan']);
?>
