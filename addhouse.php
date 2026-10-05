<?php
require 'config.php';
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
  json_response(['success'=>false,'message'=>'Not authorized']);
}
$title = trim($_POST['title'] ?? '');
$rent = floatval($_POST['rent'] ?? 0);
$description = $_POST['description'] ?? '';
$location = $_POST['location'] ?? '';
$rooms = intval($_POST['rooms'] ?? 1);

if ($title === '' || $rent <= 0) json_response(['success'=>false,'message'=>'Missing fields']);

$stmt = $pdo->prepare("INSERT INTO houses (owner_id,title,description,location,rooms,rent) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $title, $description, $location, $rooms, $rent]);
$house_id = $pdo->lastInsertId();

// handle uploads (multiple images allowed)
$upload_dir = __DIR__ . '/uploads';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

if (!empty($_FILES['images'])) {
    $files = $_FILES['images'];
    for ($i=0;$i<count($files['name']);$i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
        $tmp = $files['tmp_name'][$i];
        $orig = basename($files['name'][$i]);
        $ext = pathinfo($orig, PATHINFO_EXTENSION);
        $safe = uniqid('img_', true) . '.' . $ext;
        if (!move_uploaded_file($tmp, $upload_dir . '/' . $safe)) continue;
        $stmt = $pdo->prepare("INSERT INTO house_images (house_id, filename) VALUES (?, ?)");
        $stmt->execute([$house_id, $safe]);
    }
}

json_response(['success'=>true,'message'=>'House added','house_id'=>$house_id]);
?>