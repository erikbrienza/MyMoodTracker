<?php
require_once('./backend/config/db.php');

$email = $_POST['email'];
$password = $_POST['password'];

if (!$email || !$password) {
  echo "Campi mancanti!";
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
  $hashed = password_hash($password, PASSWORD_DEFAULT);
  $stmt->execute([$email, $hashed]);
  echo "Inserito con successo!";
} catch (PDOException $e) {
  echo "Errore: " . $e->getMessage();
}