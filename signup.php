<?php
require 'config.php';
$data = $_POST;
if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
  json_response(['success'=>false,'message'=>'Missing fields']);
}
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
if (!$email) json_response(['success'=>false,'message'=>'Invalid email']);

$name = trim($data['name']);
$role = in_array($data['role'] ?? 'tenant', ['tenant','owner','admin']) ? $data['role'] : 'tenant';
$password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (role,name,email,password_hash,phone) VALUES (?, ?, ?, ?, ?)");
try {
  $stmt->execute([$role, $name, $email, $password_hash, $data['phone'] ?? null]);
  json_response(['success'=>true,'message'=>'Account created']);
} catch (PDOException $e) {
  if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
    json_response(['success'=>false,'message'=>'Email already registered']);
  }
  json_response(['success'=>false,'message'=>'DB error: '.$e->getMessage()]);
}
?>