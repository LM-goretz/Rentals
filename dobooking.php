<?php
require 'config.php';
if (empty($_SESSION['user_id'])) json_response(['success'=>false,'message'=>'Login required']);
$house_id = intval($_POST['house_id'] ?? 0);
$start = $_POST['start_date'] ?? null;
$end = $_POST['end_date'] ?? null;
if (!$house_id || !$start) json_response(['success'=>false,'message'=>'Missing fields']);

$stmt = $pdo->prepare("INSERT INTO bookings (house_id,tenant_id,start_date,end_date,status) VALUES (?, ?, ?, ?, 'pending')");
$stmt->execute([$house_id, $_SESSION['user_id'], $start, $end]);
json_response(['success'=>true,'message'=>'Booking requested']);
?>