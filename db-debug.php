<?php
require_once __DIR__ . '/config/database.php';

$emailToTest = 'admin@empresa.com';
$stmt = $pdo->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $emailToTest]);
$user = $stmt->fetch();

header('Content-Type: text/plain; charset=utf-8');
echo "DB_HOST=" . getenv('DB_HOST') . "\n";
echo "DB_PORT=" . getenv('DB_PORT') . "\n";
echo "DB_NAME=" . getenv('DB_NAME') . "\n";
echo "DB_USER=" . getenv('DB_USER') . "\n";
echo "MYSQL_HOST=" . getenv('MYSQL_HOST') . "\n";
echo "MYSQLDATABASE=" . getenv('MYSQLDATABASE') . "\n";
echo "MYSQL_DATABASE=" . getenv('MYSQL_DATABASE') . "\n";
echo "MYSQL_USER=" . getenv('MYSQL_USER') . "\n";
echo "MYSQL_PASSWORD=" . (getenv('MYSQL_PASSWORD') ? 'SET' : 'EMPTY') . "\n";
echo "MYSQL_URL=" . getenv('MYSQL_URL') . "\n";

echo "\n--- USER TEST ---\n";
if ($user) {
    echo "found=1\n";
    echo "id=" . $user['id'] . "\n";
    echo "email=" . $user['email'] . "\n";
    echo "role=" . $user['role'] . "\n";
    echo "password_hash=" . $user['password_hash'] . "\n";
} else {
    echo "found=0\n";
}
