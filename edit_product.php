<?php
require_once 'cache.php';
$cache = new CacheManager($memcached);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $ttl = $_POST['ttl'];

    $product = ['id' => $id, 'name' => $name, 'price' => $price];
    $cache->set("product_$id", $product, (int)$ttl);
    echo "✅ Sản phẩm đã được lưu vào cache!";
}
?>

<form method="POST">
    <input type="text" name="id" placeholder="ID sản phẩm" required><br>
    <input type="text" name="name" placeholder="Tên sản phẩm" required><br>
    <input type="number" name="price" placeholder="Giá" required><br>
    <input type="number" name="ttl" placeholder="Thời gian cache (giây)" value="300"><br>
    <button type="submit">Lưu sản phẩm</button>
</form>