<?php
require_once 'auth.php';         // Bắt buộc đăng nhập
require_once 'cache.php';        // Quản lý cache
require_once 'db.php';           // Kết nối CSDL (nếu có)

$cache = new CacheManager($memcached);

$products = getAllProducts();
if (!$products) {
    // Nếu chưa có cache, lấy từ DB
    $products = getAllProducts(); // Hàm giả định trong db.php
    $cache->set('product_list', $products, 600); // Cache 10 phút
}
if (empty($products)) {
    $products = getAllProducts(); // từ db.php
    $source = 'DB';
} else {
    $source = 'Cache';
}
if ($source === 'Cache' && empty($products)) {
    echo "<div class='alert alert-warning'>Không có sản phẩm nào trong cache. Đang lấy từ DB...</div>";
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>📋 Danh sách sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">📋 Danh sách sản phẩm</h2>

    <?php if (empty($products)): ?>
        <div class="alert alert-warning">Không có sản phẩm nào.</div>
    <?php else: ?>
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= number_format($p['price'], 0, ',', '.') ?>₫</td>
                        <td><a href="product_detail.php?id=<?= urlencode($p['id']) ?>" class="btn btn-sm btn-outline-info">Xem</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
    </div>
    

</div>
</body>
</html>