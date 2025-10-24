CREATE DATABASE IF NOT EXISTS cache_manager;
USE cache_manager;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price INT NOT NULL
);

-- Dữ liệu mẫu
INSERT INTO products (id, name, price) VALUES
(1, 'Laptop Dell XPS', 25000000),
(2, 'Chuột Logitech MX Master', 1800000),
(3, 'Bàn phím Keychron K2', 2200000);