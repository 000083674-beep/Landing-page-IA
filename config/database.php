<?php
$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: getenv('MYSQL_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: getenv('MYSQL_DATABASE') ?: getenv('MYSQLDATABASE') ?: '';
$dbUser = getenv('DB_USER') ?: getenv('MYSQL_USER') ?: getenv('MYSQLUSER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: getenv('MYSQL_PASSWORD') ?: getenv('MYSQL_ROOT_PASSWORD') ?: '';

if (!$dbName) {
    $mysqlUrl = getenv('MYSQL_URL') ?: getenv('DATABASE_URL') ?: '';
    if ($mysqlUrl !== '') {
        $parts = parse_url($mysqlUrl);
        if ($parts !== false) {
            $dbName = ltrim($parts['path'] ?? '', '/');
            $host = $host === 'localhost' && !empty($parts['host']) ? $parts['host'] : $host;
            $port = $port === '3306' && !empty($parts['port']) ? $parts['port'] : $port;
            $dbUser = $dbUser === 'root' && !empty($parts['user']) ? $parts['user'] : $dbUser;
            $dbPassword = $dbPassword === '' && isset($parts['pass']) ? $parts['pass'] : $dbPassword;
        }
    }
}

$dbName = $dbName ?: 'landing_app';

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      full_name VARCHAR(120) NOT NULL,
      email VARCHAR(160) NOT NULL UNIQUE,
      password_hash VARCHAR(255) NOT NULL,
      role ENUM('admin','user') NOT NULL DEFAULT 'user',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS sales (
      id INT AUTO_INCREMENT PRIMARY KEY,
      description VARCHAR(255) NOT NULL,
      amount DECIMAL(10,2) NOT NULL,
      sale_date DATE NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $hasUsers = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($hasUsers === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (full_name, email, password_hash, role) VALUES (:full_name, :email, :password_hash, :role)');
        $defaultPassword = '$2y$10$R2pBfBA1/l4wdBCkGv9GyOOLAppVy4R13hN8dm6Pjl9f1E6.3uQzu';
        $stmt->execute([':full_name' => 'Administrador', ':email' => 'admin@empresa.com', ':password_hash' => $defaultPassword, ':role' => 'admin']);
        $stmt->execute([':full_name' => 'Usuario Demo', ':email' => 'user@empresa.com', ':password_hash' => $defaultPassword, ':role' => 'user']);
    }
} catch (PDOException $e) {
    die('No se pudo conectar a la base de datos: ' . $e->getMessage());
}
