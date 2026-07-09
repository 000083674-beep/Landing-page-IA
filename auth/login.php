<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['auth_error'] = 'Completa correo y contraseña.';
    header('Location: ../index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];
    unset($_SESSION['auth_error']);

    if ($user['role'] === 'admin') {
        header('Location: ../dashboard.php');
    } else {
        header('Location: ../landing.php');
    }
    exit;
}

$_SESSION['auth_error'] = 'Credenciales incorrectas.';
header('Location: ../index.php');
