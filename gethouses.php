<?php
require 'config.php';
$stmt = $pdo->query("SELECT h.*, u.name as owner_name, u.phone as owner_phone FROM houses h JOIN users u ON h.owner_id = u.id WHERE is_available = 1 ORDER BY h.created_at DESC");
$houses = $stmt->fetchAll();

foreach ($houses as &$h) {
    $stmt2 = $pdo->prepare("SELECT filename FROM house_images WHERE house_id = ? ORDER BY id ASC");
    $stmt2->execute([$h['id']]);
    $imgs = $stmt2->fetchAll(PDO::FETCH_COLUMN);
    $h['images'] = $imgs;
}
header('Content-Type: application/json');
echo json_encode($houses);
?>