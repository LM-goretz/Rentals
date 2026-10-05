<?php
require 'config.php';
if (empty($_SESSION['user_id'])) json_response(['success'=>false,'message'=>'Login required']);
$house_id = intval($_POST['house_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$comment = $_POST['comment'] ?? '';
if (!$house_id || $rating < 1 || $rating > 5) json_response(['success'=>false,'message'=>'Invalid data']);
$stmt = $pdo->prepare("INSERT INTO ratings (house_id,user_id,rating,comment) VALUES (?, ?, ?, ?)");
$stmt->execute([$house_id, $_SESSION['user_id'], $rating, $comment]);
json_response(['success'=>true,'message'=>'Rating submitted']);
?>