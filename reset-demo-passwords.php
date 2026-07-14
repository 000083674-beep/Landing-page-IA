<?php
require_once __DIR__ . '/config/database.php';

$defaultPassword = 'password123';
$hash = password_hash($defaultPassword, PASSWORD_BCRYPT);
$emails = [
    'admin@empresa.com' => 'admin',
    'user@empresa.com' => 'user',
];

$stmt = $pdo->prepare('UPDATE users SET password_hash = :password_hash WHERE email = :email');
foreach ($emails as $email => $role) {
    $stmt->execute([':password_hash' => $hash, ':email' => $email]);
}

header('Content-Type: text/plain; charset=utf-8');
echo "Contraseñas reiniciadas a password123 para admin@empresa.com y user@empresa.com\n";
echo "Por seguridad, elimina reset-demo-passwords.php después de usarlo.\n";
