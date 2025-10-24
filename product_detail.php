<?php
require_once 'auth.php';
require_once 'cache.php';
require_once 'db.php';

$cache = new CacheManager($memcached);
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Thiếu ID sản phẩm.");
}

// Kiểm tra cache trước
$product = $cache->get("product_$id");
$fromCache = true;

if (!$product) {
    $product = getProductById($id);
    $fromCache = false;
    if ($product) {
        $cache->set("product_$id", $product, 600); // Cache 10 phút
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>🔍 Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">🔍 Chi tiết sản phẩm</h2>

    <?php if (!$product): ?>
        <div class="alert alert-danger">Không tìm thấy sản phẩm.</div>
    <?php else: ?>
        <div class="card p-4 shadow-sm">
            <p><strong>ID:</strong> <?= htmlspecialchars($product['id']) ?></p>
            <p><strong>Tên:</strong> <?= htmlspecialchars($product['name']) ?></p>
            <p><strong>Giá:</strong> <?= number_format($product['price'], 0, ',', '.') ?>₫</p>
            <p><strong>📦 Nguồn dữ liệu:</strong> <?= $fromCache ? 'Cache' : 'Database' ?></p>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="product_list.php" class="btn btn-outline-secondary">🔙 Quay lại danh sách</a>
    </div>
</div>
</body>
</html>