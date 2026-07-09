<?php
session_start();

function requireAuth(array $allowedRoles = []): array {
    if (empty($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }

    $user = [
        'id' => $_SESSION['user_id'],
        'full_name' => $_SESSION['full_name'] ?? 'Usuario',
        'role' => $_SESSION['role'] ?? 'user',
    ];

    if ($allowedRoles && !in_array($user['role'], $allowedRoles, true)) {
        header('Location: landing.php');
        exit;
    }

    return $user;
}

function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function getCurrentUser(PDO $pdo): ?array {
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, full_name, email, role, created_at FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}
