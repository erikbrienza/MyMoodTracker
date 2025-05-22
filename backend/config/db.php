<?php
$host = 'localhost';
$db = 'mymoodtracker';
$user = 'root'; 
$pass = 'root';     
$charset = 'utf8mb4';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Errore connessione DB']);
  exit;
}
?>