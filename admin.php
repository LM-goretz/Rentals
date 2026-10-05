<?php
require 'config.php';
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo 'Access denied';
    exit;
}

// handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'set_booking_status') {
        $id = intval($_POST['booking_id']);
        $status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    } elseif ($action === 'toggle_availability') {
        $hid = intval($_POST['house_id']);
        $val = intval($_POST['val']);
        $stmt = $pdo->prepare("UPDATE houses SET is_available = ? WHERE id = ?");
        $stmt->execute([$val, $hid]);
    }
    header('Location: admin.php');
    exit;
}

// fetch data
$bookings = $pdo->query("SELECT b.*, u.name as tenant_name, h.title as house_title FROM bookings b JOIN users u ON b.tenant_id = u.id JOIN houses h ON b.house_id = h.id ORDER BY b.created_at DESC")->fetchAll();
$users = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id ASC")->fetchAll();
$houses = $pdo->query("SELECT * FROM houses ORDER BY id ASC")->fetchAll();
?>
<!doctype html>
<html><body>
<h1>Admin Dashboard</h1>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?> | <a href="logout.php">Logout</a></p>

<h2>Bookings</h2>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>House</th><th>Tenant</th><th>Start</th><th>End</th><th>Status</th><th>Action</th></tr>
<?php foreach($bookings as $b): ?>
<tr>
<td><?php echo $b['id']; ?></td>
<td><?php echo htmlspecialchars($b['house_title']); ?></td>
<td><?php echo htmlspecialchars($b['tenant_name']); ?></td>
<td><?php echo $b['start_date']; ?></td>
<td><?php echo $b['end_date']; ?></td>
<td><?php echo $b['status']; ?></td>
<td>
<form method="post" style="display:inline">
<input type="hidden" name="action" value="set_booking_status">
<input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
<select name="status">
  <option value="pending">pending</option>
  <option value="confirmed">confirmed</option>
  <option value="cancelled">cancelled</option>
  <option value="completed">completed</option>
</select>
<button>Set</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>

<h2>Houses</h2>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Title</th><th>Owner ID</th><th>Available</th><th>Action</th></tr>
<?php foreach($houses as $h): ?>
<tr>
<td><?php echo $h['id']; ?></td>
<td><?php echo htmlspecialchars($h['title']); ?></td>
<td><?php echo $h['owner_id']; ?></td>
<td><?php echo $h['is_available'] ? 'Yes' : 'No'; ?></td>
<td>
<form method="post" style="display:inline">
<input type="hidden" name="action" value="toggle_availability">
<input type="hidden" name="house_id" value="<?php echo $h['id']; ?>">
<input type="hidden" name="val" value="<?php echo $h['is_available'] ? 0 : 1; ?>">
<button><?php echo $h['is_available'] ? 'Mark unavailable' : 'Mark available'; ?></button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>

<h2>Users</h2>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>
<?php foreach($users as $u): ?>
<tr>
<td><?php echo $u['id']; ?></td>
<td><?php echo htmlspecialchars($u['name']); ?></td>
<td><?php echo htmlspecialchars($u['email']); ?></td>
<td><?php echo $u['role']; ?></td>
</tr>
<?php endforeach; ?>
</table>

</body></html>
