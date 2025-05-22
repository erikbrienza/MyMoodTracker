<?php
// Headers CORS + JSON
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, X-Token");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

// 🔧 Manca questo:
require_once('../config/db.php');
require_once('../utils/jwt.php');

$secret = 'chiaveSuperSegreta123'; // puoi centralizzarla se vuoi

$data = json_decode(file_get_contents("php://input"), true);
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
$password = $data['password'];

if (!$email || !$password) {
  echo json_encode(['success' => false, 'message' => 'Email o password mancanti']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$email]);
  if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => 'Email già registrata']);
    exit;
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
  $stmt->execute([$email, $hashedPassword]);
  $userId = $pdo->lastInsertId();

  $token = generateJWT(['id' => $userId], $secret);
  echo json_encode(['success' => true, 'token' => $token]);

} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Errore server']);
}