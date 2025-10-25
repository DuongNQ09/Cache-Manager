<?php
require_once 'auth.php';
require_once 'cache.php';

// Khởi tạo CacheManager
$cache = new CacheManager($memcached);

// Lấy ID sản phẩm từ query string
$id = $_GET['id'] ?? null;

if ($id === null) {
    die("Thiếu ID sản phẩm");
}

// Lấy dữ liệu sản phẩm từ cache
$product = $cache->getProduct($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>📦 Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>📦 Chi tiết sản phẩm</h2>

    <?php if ($product): ?>
        <pre><?php print_r($product); ?></pre>
    <?php else: ?>
        <div class="alert alert-warning">Không tìm thấy sản phẩm trong cache</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="product_cache_detail.php" class="btn btn-outline-secondary">🔙 Quay lại danh sách cache</a>
    </div>
</div>
</body>
</html>