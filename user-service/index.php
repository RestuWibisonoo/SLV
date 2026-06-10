<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config/koneksi.php';

function jsonResponse($data, int $statusCode = 200)
{
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
    jsonResponse([
        'success' => true,
        'message' => 'OK'
    ]);
}

// Health check / root user service
if ($method === 'GET' && ($action === '' || $action === 'health' || $action === 'status')) {
    jsonResponse([
        'success' => true,
        'message' => 'User Service Online',
        'endpoints' => [
            'POST /api/user/login',
            'POST /api/user/register'
        ]
    ]);
}

if ($method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    if (!is_array($input)) {
        $input = $_POST;
    }

    if ($action === 'login') {
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if ($email === '' || $password === '') {
            jsonResponse([
                'success' => false,
                'message' => 'Email dan password harus diisi'
            ], 400);
        }

        $conn = getDB();

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if (!$result || $result->num_rows === 0) {
            jsonResponse([
                'success' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password'])) {
            jsonResponse([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $update->bind_param("i", $user['id']);
        $update->execute();

        unset($user['password']);

        jsonResponse([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => $user,
            'token' => base64_encode(json_encode([
                'id' => $user['id'],
                'role' => $user['role'] ?? 'user'
            ]))
        ]);
    }

    if ($action === 'register') {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $phone = trim($input['phone'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            jsonResponse([
                'success' => false,
                'message' => 'Nama, email, dan password wajib diisi'
            ], 400);
        }

        $conn = getDB();

        $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();

        $checkResult = $check->get_result();

        if ($checkResult && $checkResult->num_rows > 0) {
            jsonResponse([
                'success' => false,
                'message' => 'Email sudah terdaftar'
            ], 409);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';

        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password, phone, role, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param("sssss", $name, $email, $hash, $phone, $role);

        if ($stmt->execute()) {
            jsonResponse([
                'success' => true,
                'message' => 'Registrasi berhasil'
            ], 201);
        }

        jsonResponse([
            'success' => false,
            'message' => 'Registrasi gagal: ' . $conn->error
        ], 500);
    }
}

jsonResponse([
    'success' => false,
    'message' => 'Endpoint tidak ditemukan',
    'available_endpoints' => [
        'GET /api/user/',
        'GET /api/user/health',
        'POST /api/user/login',
        'POST /api/user/register'
    ]
], 404);
