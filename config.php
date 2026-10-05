<?php
// config.php - update DB credentials below before running
session_start();

$db_host = '127.0.0.1';
$db_name = 'greenwood';
$db_user = 'root';         // <-- change to your DB user
$db_pass = '';             // <-- change to your DB password
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed: '.$e->getMessage()]);
  exit;
}

function json_response($data) {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data);
  exit;
}
?>