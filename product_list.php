<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);

// 1. Lấy sản phẩm từ database
$pdo = new PDO("mysql:host=localhost;dbname=cache_manager;charset=utf8", "root", "");
$stmt = $pdo->query("SELECT id, name, price FROM products");
$dbProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Lấy sản phẩm từ cache
$productKeys = $cache->getProductKeys();
$cacheProducts = [];
foreach ($productKeys as $key) {
    $id = str_replace("product_", "", $key);
    $product = $cache->getProduct($id);
    if ($product) {
        $cacheProducts[] = $product;
    }
}

// 3. Gộp dữ liệu
$allProducts = [];

// Thêm sản phẩm từ DB
foreach ($dbProducts as $p) {
    $allProducts[] = [
        'id'    => $p['id'],
        'name'  => $p['name'],
        'price' => $p['price'],
        'source'=> 'Database'
    ];
}

// Thêm sản phẩm từ Cache
foreach ($cacheProducts as $p) {
    $allProducts[] = [
        'id'    => $p['id'],
        'name'  => $p['name'],
        'price' => $p['price'],
        'source'=> 'Cache'
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>📋 Danh sách sản phẩm</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>📋 Danh sách sản phẩm (DB + Cache)</h2>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Thời gian</th>
                <th>Nguồn</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($allProducts)): ?>
                <?php foreach ($allProducts as $i => $p): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= number_format($p['price'], 0, ',', '.') ?> đ</td>
                        <td>
                            <?php if ($p['source'] === 'Database'): ?>
                                <span class="badge bg-primary">Database</span>
                            <?php else: ?>
                                <span class="badge bg-success">Cache</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($p['source'] === 'Cache'): ?>
                                <a href="update_product.php?id=<?= urlencode($p['id']) ?>" class="btn btn-sm btn-outline-primary">✏️ Sửa</a>
                                <a href="remove_product_cache.php?key=<?= urlencode('product_'.$p['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Xóa sản phẩm này khỏi cache?')">❌ Xóa</a>
                            <?php else: ?>
                                <a href="view_product.php?id=<?= urlencode($p['id']) ?>" class="btn btn-sm btn-outline-info">👁️ Xem</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Không có sản phẩm nào</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
    </div>
</div>
</body>
</html>