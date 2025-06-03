<?php
DB_HOST=localhost
DB_NAME=mymoodtracker
DB_USER=root
DB_PASS=password

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Errore connessione DB']);
  exit;
}
?>
