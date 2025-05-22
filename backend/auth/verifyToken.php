<?php
require_once('../utils/jwt.php');

$secret = 'chiaveSuperSegreta123';
$headers = getallheaders();

if (!isset($headers['X-Token'])) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Token mancante']);
  exit;
}

$decoded = verifyJWT($headers['X-Token'], $secret);

if (!$decoded) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Token non valido o scaduto']);
  exit;
}

// Ora $decoded['id'] contiene l'ID dell'utente loggato
$userId = $decoded['id'];