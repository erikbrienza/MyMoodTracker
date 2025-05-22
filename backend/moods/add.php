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

// ✅ Recupera il token dall'header
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

$data = json_decode(file_get_contents("php://input"), true);
$mood = $data['mood'] ?? null;
$date = date('Y-m-d'); // solo uno al giorno

$validMoods = ['happy', 'neutral', 'sad'];
if (!in_array($mood, $validMoods)) {
  echo json_encode(['success' => false, 'message' => 'Mood non valido']);
  exit;
}

// ✅ Verifica se è già stato inserito un mood per oggi
$stmt = $pdo->prepare("SELECT id FROM moods WHERE user_id = ? AND date = ?");
$stmt->execute([$userId, $date]);

if ($stmt->rowCount() > 0) {
  echo json_encode(['success' => false, 'message' => 'Hai già inserito un mood per oggi']);
  exit;
}

// ✅ Inserisci il nuovo mood
$stmt = $pdo->prepare("INSERT INTO moods (user_id, mood, date) VALUES (?, ?, ?)");
$stmt->execute([$userId, $mood, $date]);

echo json_encode(['success' => true, 'message' => 'Mood salvato con successo']);