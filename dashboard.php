<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/functions.php';

$user = requireAuth(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_user') {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if ($fullName !== '' && $email !== '' && $password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role) VALUES (:full_name, :email, :password_hash, :role)');
            $stmt->execute([
                ':full_name' => $fullName,
                ':email' => $email,
                ':password_hash' => $hash,
                ':role' => $role,
            ]);
        }
    }

    if ($action === 'update_user') {
        $id = (int)($_POST['user_id'] ?? 0);
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';
        $password = $_POST['password'] ?? '';

        if ($id > 0 && $fullName !== '' && $email !== '') {
            if ($password !== '') {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare('UPDATE users SET full_name = :full_name, email = :email, role = :role, password_hash = :password_hash WHERE id = :id');
                $stmt->execute([':full_name' => $fullName, ':email' => $email, ':role' => $role, ':password_hash' => $hash, ':id' => $id]);
            } else {
                $stmt = $pdo->prepare('UPDATE users SET full_name = :full_name, email = :email, role = :role WHERE id = :id');
                $stmt->execute([':full_name' => $fullName, ':email' => $email, ':role' => $role, ':id' => $id]);
            }
        }
    }
}

$usersStmt = $pdo->query('SELECT id, full_name, email, role, created_at FROM users ORDER BY id DESC');
$users = $usersStmt->fetchAll();

$dailySalesStmt = $pdo->prepare('SELECT SUM(amount) AS total FROM sales WHERE sale_date = CURDATE()');
$dailySalesStmt->execute();
$dailySales = $dailySalesStmt->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | Tribuna F1</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a href="dashboard.php" class="brand">Dashboard</a>
      <div class="header-actions">
        <span class="greeting">Bienvenido, <?= e($user['full_name']) ?></span>
        <a class="btn btn-secondary" href="auth/logout.php">Cerrar sesión</a>
      </div>
    </div>
  </header>

  <main class="dashboard-shell">
    <section class="container dashboard-grid">
      <div class="card dashboard-card">
        <h2>Ventas diarias</h2>
        <p class="metric">$<?= e(number_format((float)($dailySales['total'] ?? 0), 2)) ?></p>
        <p>Resumen del día actual</p>
      </div>

      <div class="card dashboard-card">
        <h2>Agregar usuario</h2>
        <form method="post" class="auth-form">
          <input type="hidden" name="action" value="create_user" />
          <label>Nombre<input type="text" name="full_name" required /></label>
          <label>Correo<input type="email" name="email" required /></label>
          <label>Contraseña<input type="password" name="password" required /></label>
          <label>Rol<select name="role"><option value="user">User</option><option value="admin">Admin</option></select></label>
          <button type="submit">Guardar</button>
        </form>
      </div>
    </section>

    <section class="container card users-table-wrap">
      <h2>Usuarios</h2>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Fecha</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= e($u['full_name']) ?></td>
              <td><?= e($u['email']) ?></td>
              <td><?= e($u['role']) ?></td>
              <td><?= e($u['created_at']) ?></td>
              <td>
                <form method="post" class="inline-form">
                  <input type="hidden" name="action" value="update_user" />
                  <input type="hidden" name="user_id" value="<?= e($u['id']) ?>" />
                  <input type="text" name="full_name" value="<?= e($u['full_name']) ?>" required />
                  <input type="email" name="email" value="<?= e($u['email']) ?>" required />
                  <select name="role">
                    <option value="user" <?= $u['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                  </select>
                  <input type="password" name="password" placeholder="Nueva contraseña" />
                  <button type="submit">Actualizar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </main>
</body>
</html>
