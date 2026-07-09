CREATE DATABASE IF NOT EXISTS landing_app;
USE landing_app;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  description VARCHAR(255) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  sale_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (full_name, email, password_hash, role) VALUES
('Administrador', 'admin@empresa.com', '$2y$10$R2pBfBA1/l4wdBCkGv9GyOOLAppVy4R13hN8dm6Pjl9f1E6.3uQzu', 'admin'),
('Usuario Demo', 'user@empresa.com', '$2y$10$R2pBfBA1/l4wdBCkGv9GyOOLAppVy4R13hN8dm6Pjl9f1E6.3uQzu', 'user');

INSERT INTO sales (description, amount, sale_date) VALUES
('Venta de ejemplo 1', 150.50, CURDATE()),
('Venta de ejemplo 2', 89.99, CURDATE()),
('Venta de ejemplo 3', 245.00, DATE_SUB(CURDATE(), INTERVAL 1 DAY));
