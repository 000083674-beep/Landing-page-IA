# Tribuna F1 - Sistema de login y dashboard

## Archivos principales
- `index.html` — landing page pública con acceso al login.
- `index.php` — formulario de login para usuarios y administradores.
- `landing.php` — landing page privada con saludo por nombre.
- `dashboard.php` — panel de administración para usuarios y ventas.
- `config/database.php` — conexión a MySQL.
- `schema.sql` — estructura de base de datos.
- `auth/login.php` y `auth/logout.php` — autenticación.

## Base de datos
Ejecuta el contenido de `schema.sql` en MySQL para crear:
- `users`
- `sales`

## Variables de entorno
Para Railway define:
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASSWORD`

## Despliegue en Railway
1. Sube este repositorio a GitHub.
2. Crea un servicio PHP en Railway.
3. Usa el comando de inicio: `php -S 0.0.0.0:$PORT -t .`
4. Agrega las variables de entorno de MySQL.

## Credenciales de demo
- Admin: `admin@empresa.com` / `password123`
- Usuario: `user@empresa.com` / `password123`
