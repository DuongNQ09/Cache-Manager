<?php
$host = 'localhost';
$dbname = 'cache_manager';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}

function getAllProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addProduct($id, $name, $price) {
    global $pdo;
    $stmt = $pdo->prepare("REPLACE INTO products (id, name, price) VALUES (?, ?, ?)");
    return $stmt->execute([$id, $name, $price]);
}

function updateProduct($id, $name, $price) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
    return $stmt->execute([$name, $price, $id]);
}

function deleteProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}
?>