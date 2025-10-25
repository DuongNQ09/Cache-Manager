<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);
$productKeys = $cache->getProductKeys();
?>
<!DOCTYPE html>
<html>
<head>
    <title>📦 Chi tiết sản phẩm cache</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>📦 Danh sách sản phẩm đang cache</h2>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Key</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productKeys)): ?>
                <?php foreach ($productKeys as $i => $key): ?>
                    <?php $product = $memcached->get($key); ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($key) ?></td>
                        <td><?= htmlspecialchars($product['name'] ?? '—') ?></td>
                        <td><?= isset($product['price']) ? number_format($product['price']) . ' VNĐ' : '—' ?></td>
                        <td>
                            <a href="remove_product_cache.php?key=<?= urlencode($key) ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa cache này?')">
                               ❌ Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Không có sản phẩm nào trong cache</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Nút xóa toàn bộ -->
    <a href="clear_all_products.php"
       class="btn btn-danger"
       onclick="return confirm('Bạn có chắc muốn xóa toàn bộ sản phẩm trong cache?')">
       🗑️ Xóa toàn bộ sản phẩm cache
    </a>

    <!-- Nút quay lại -->
    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
    </div>
</div>
</body>
</html>