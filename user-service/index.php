<?php
// index.php
header('Content-Type: application/json');

require_once 'config/koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
$path = rtrim($requestUri, '/');
$pathParts = explode('/', $path);
$action = end($pathParts); // Get the last part, e.g., 'login', 'register'

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
    if ($action === 'login') {
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Email dan password harus diisi']);
            exit;
        }
        
        $conn = getDB();
        $email_esc = $conn->real_escape_string($email);
        $result = $conn->query("SELECT * FROM users WHERE email = '{$email_esc}' LIMIT 1");
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Update last login
                $conn->query("UPDATE users SET last_login = NOW() WHERE id = {$user['id']}");
                
                // Exclude password from response
                unset($user['password']);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'data' => $user,
                    'token' => base64_encode(json_encode(['id' => $user['id'], 'role' => $user['role']])) // Dummy token for API
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Password salah']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Email tidak ditemukan']);
        }
        exit;
    } 
    
    if ($action === 'register') {
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        $phone = $input['phone'] ?? '';
        
        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
            exit;
        }
        
        $conn = getDB();
        $email_esc = $conn->real_escape_string($email);
        
        // Cek email
        $check = $conn->query("SELECT id FROM users WHERE email = '{$email_esc}'");
        if ($check && $check->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar']);
            exit;
        }
        
        $name_esc = $conn->real_escape_string($name);
        $phone_esc = $conn->real_escape_string($phone);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, password, phone, role, created_at) VALUES ('{$name_esc}', '{$email_esc}', '{$hash}', '{$phone_esc}', 'user', NOW())";
        
        if ($conn->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Registrasi berhasil']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registrasi gagal: ' . $conn->error]);
        }
        exit;
    }
}

// Fallback if not found
http_response_code(404);
echo json_encode(['success' => false, 'message' => 'Endpoint tidak ditemukan']);
?>
