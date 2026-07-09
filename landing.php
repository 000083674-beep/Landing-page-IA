<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/functions.php';

$user = requireAuth(['user', 'admin']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Landing page | Tribuna F1</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a href="#hero" class="brand">Tribuna F1</a>
      <div class="header-actions">
        <span class="greeting">Hola, <?= e($user['full_name']) ?></span>
        <a class="btn btn-secondary" href="auth/logout.php">Cerrar sesión</a>
      </div>
    </div>
  </header>

  <main>
    <section id="hero" class="hero">
      <div class="hero-overlay"></div>
      <div class="container hero-content">
        <span class="eyebrow">F1 + universitarios</span>
        <h1>Tu tribuna F1: momentos épicos con onda universitaria</h1>
        <p class="hero-copy">Descubre highlights, debates y eventos pensados para estudiantes que viven la velocidad sin perder estilo.</p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="https://www.instagram.com/danrivera89?igsh=b3JhODlvb2NnOGR6&utm_source=qr" target="_blank" rel="noreferrer">Ver highlights</a>
          <a class="btn btn-secondary" href="https://www.instagram.com/" target="_blank" rel="noreferrer">Síguenos</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
