<?php
require_once 'auth.php';
require_once 'cache.php';
$cache = new CacheManager($memcached);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $ttl = $_POST['ttl'];

    $product = ['id' => $id, 'name' => $name, 'price' => $price];
    $cache->set("product_$id", $product, (int)$ttl);
    $message = "✅ Sản phẩm đã được lưu vào cache!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>✏️ Chỉnh sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">✏️ Chỉnh sửa sản phẩm</h2>
    <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>
    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label>ID sản phẩm</label>
            <input type="text" name="id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Thời gian cache (giây)</label>
            <input type="number" name="ttl" class="form-control" value="300">
        </div>
        <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
    </form>
    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">🔙 Quay lại Dashboard</a>
</div>
</body>
</html>