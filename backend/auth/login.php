<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type, X-Token");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

require_once('../config/db.php');
require_once('../utils/jwt.php');

$secret = 'chiaveSuperSegreta123';

$data = json_decode(file_get_contents("php://input"), true);
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
$password = $data['password'];

if (!$email || !$password) {
  echo json_encode(['success' => false, 'message' => 'Email o password mancanti']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $token = generateJWT(['id' => $user['id']], $secret);
    echo json_encode(['success' => true, 'token' => $token]);
  } else {
    echo json_encode(['success' => false, 'message' => 'Credenziali non valide']);
  }

} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Errore server']);
}