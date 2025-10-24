<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'cache.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $price = intval($_POST['price']);
    $ttl = intval($_POST['ttl']);

    // Lưu vào DB
    addProduct($id, $name, $price);

    // Lưu vào cache
    $product = ['id' => $id, 'name' => $name, 'price' => $price];
    $memcached->set("product_$id", $product, $ttl);

    $message = "✅ Đã lưu sản phẩm vào cơ sở dữ liệu và cache.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>✏️ Thêm sản phẩm vào cache & DB</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">✏️ Thêm sản phẩm vào hệ thống</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" class="mb-4">
        <div class="mb-3">
            <label>ID sản phẩm</label>
            <input type="number" name="id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Giá sản phẩm (VNĐ)</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Thời gian lưu cache (giây)</label>
            <input type="number" name="ttl" class="form-control" value="600" required>
        </div>
        <button type="submit" class="btn btn-success">💾 Lưu sản phẩm</button>
    </form>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
    </div>
</div>
</body>
</html>