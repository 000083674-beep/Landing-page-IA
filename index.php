<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/config/database.php';

if (!empty($_SESSION['user_id'])) {
    $role = $_SESSION['role'] ?? 'user';
    if ($role === 'admin') {
        header('Location: dashboard.php');
    } else {
        header('Location: landing.php');
    }
    exit;
}

$error = $_SESSION['auth_error'] ?? '';
unset($_SESSION['auth_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Tribuna F1</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <main class="auth-shell">
    <div class="auth-card">
      <h1>Inicia sesión</h1>
      <p class="auth-subtitle">Accede según tu rol para entrar al panel o a la landing page.</p>
      <?php if ($error): ?>
        <div class="alert"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" action="auth/login.php" class="auth-form">
        <label>
          Correo
          <input type="email" name="email" required />
        </label>
        <label>
          Contraseña
          <input type="password" name="password" required />
        </label>
        <button type="submit">Entrar</button>
      </form>
      <p class="auth-hint">Demo: admin@empresa.com / password123</p>
      <p class="auth-hint">Demo: user@empresa.com / password123</p>
    </div>
  </main>
</body>
</html>
