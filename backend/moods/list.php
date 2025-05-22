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

$headers = getallheaders();
$token = $headers['X-Token'] ?? null;
$secret = 'chiaveSuperSegreta123';

if (!$token) {
  echo json_encode(['success' => false, 'message' => 'Token mancante']);
  exit;
}

$payload = verifyJWT($token, $secret);
if (!$payload) {
  echo json_encode(['success' => false, 'message' => 'Token non valido']);
  exit;
}

$userId = $payload['id'] ?? null;

try {
  $stmt = $pdo->prepare("SELECT mood, date FROM moods WHERE user_id = ? ORDER BY date DESC");
  $stmt->execute([$userId]);
  $moods = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(['success' => true, 'moods' => $moods]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Errore nel caricamento']);
}