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
(4, 'Tai nghe Sony WH-1000XM5', 6990000),
(5, 'Màn hình LG UltraWide 34"', 11500000),
(6, 'Ổ cứng SSD Samsung 1TB', 2500000);
(7, 'MacBook Pro M3', 42900000),
(8, 'iPad Air 2024', 18900000),
(9, 'Apple Watch Series 9', 10900000);