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
} catch (PDOException $e) {
    die('No se pudo conectar a la base de datos: ' . $e->getMessage());
}
