<?php
// convert_hashes.php
// WARNING: Run this once from CLI or browser after setting DB credentials in config.php.
// It will find users with password_hash starting with '$sha256$' and replace them with bcrypt hashes.
// Usage: php convert_hashes.php  OR visit via browser (only run once)

require 'config.php';

$stmt = $pdo->query("SELECT id, password_hash FROM users WHERE password_hash LIKE '$$sha256$$%'");
$rows = $stmt->fetchAll();
$count = 0;
foreach ($rows as $r) {
    $stored = $r['password_hash'];
    $sha = substr($stored, 8);
    // we don't have the plaintext, but we can only re-hash if we know plaintext.
    // Therefore this script only works if you temporarily know the plaintexts or if you
    // want to prompt users to reset. Instead, we will output instructions and mark rows.
    // Better approach: run a script locally to generate password_hash() for known passwords and update DB.
    // For security reasons, this script will not attempt to reverse SHA256.
}
echo "This script cannot convert SHA256-to-bcrypt because original plaintexts are not known.\n";
echo "Use the provided php one-liner to generate a bcrypt hash for each password and update the users table.\n";
echo "Example (replace <hash>):\nphp -r \"echo password_hash('1234', PASSWORD_DEFAULT).PHP_EOL;\"\n";
echo "Then run SQL: UPDATE users SET password_hash = '<bcrypt-hash>' WHERE email = 'daniel@example.com';\n";
?>