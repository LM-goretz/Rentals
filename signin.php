<?php
require 'config.php';
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
if (!$email || !$password) json_response(['success'=>false,'message'=>'Missing fields']);

$stmt = $pdo->prepare("SELECT id, password_hash, role, name FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
if (!$user) json_response(['success'=>false,'message'=>'Invalid credentials']);

$stored = $user['password_hash'];

if (strpos($stored, '$sha256$') === 0) {
    $hashpart = substr($stored, 8);
    if (hash('sha256', $password) !== $hashpart) {
        json_response(['success'=>false,'message'=>'Invalid credentials']);
    }
} else {
    if (!password_verify($password, $stored)) {
        json_response(['success'=>false,'message'=>'Invalid credentials']);
    }
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];
$_SESSION['name'] = $user['name'];

json_response(['success'=>true,'message'=>'Signed in','user'=>['id'=>$user['id'],'name'=>$user['name'],'role'=>$user['role']]]);
?>