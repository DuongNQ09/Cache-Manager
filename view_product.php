<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);

// Lấy ID sản phẩm từ query string
$id = $_GET['id'] ?? null;
if (!$id) {
    die("⚠️ Thiếu ID sản phẩm");
}

// 1. Thử lấy sản phẩm từ cache
$product = $cache->getProduct($id);

// 2. Nếu cache miss, fallback sang DB (nếu có DB)
if (!$product) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=cache_manager;charset=utf8", "root", "");
        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Lưu lại vào cache để lần sau nhanh hơn
            $cache->setProduct($id, $product, 3600);
        }
    } catch (PDOException $e) {
        die("Lỗi kết nối DB: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>📦 Xem sản phẩm</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>📦 Thông tin sản phẩm</h2>

    <?php if ($product): ?>
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h5 class="card-title">🆔 Mã sản phẩm: <?= htmlspecialchars($product['id']) ?></h5>
                <p class="card-text"><strong>📛 Tên:</strong> <?= htmlspecialchars($product['name']) ?></p>
                <p class="card-text"><strong>💰 Giá:</strong> <?= number_format($product['price'], 0, ',', '.') ?> đ</p>
                <p class="text-muted">
                    <?php if (isset($product['from_cache'])): ?>
                        (Dữ liệu lấy từ Cache)
                    <?php else: ?>
                        (Dữ liệu lấy từ Database và đã lưu vào Cache)
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-4">Không tìm thấy sản phẩm</div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="product_list.php" class="btn btn-outline-secondary">🔙 Quay lại danh sách</a>
    </div>
</div>
</body>
</html>