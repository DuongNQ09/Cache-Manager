<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);
$stats = $cache->getStats();

// Tổng số item trong cache
$totalCacheCount = 0;
if (is_array($stats)) {
    foreach ($stats as $server => $data) {
        $totalCacheCount += $data['curr_items'] ?? 0;
    }
}

// Sản phẩm đang cache
$productCacheCount = $cache->countProducts();

// Hit/Miss
$totalHits = 0;
$totalMisses = 0;
if (is_array($stats)) {
    foreach ($stats as $server => $data) {
        $totalHits += $data['get_hits'] ?? 0;
        $totalMisses += $data['get_misses'] ?? 0;
    }
}
$hitRatio = ($totalHits + $totalMisses) > 0 
    ? round(($totalHits / ($totalHits + $totalMisses)) * 100, 2) 
    : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>📊 Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Dashboard Quản lý Cache</h2>
        <a href="logout.php" class="btn btn-outline-danger">Đăng xuất</a>
    </div>

    <div class="row">
        <!-- Tổng Cache -->
        <div class="col-md-4">
            <a href="cache_detail.php" class="text-decoration-none">
                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">🧠 Tổng Cache</h5>
                        <p class="card-text fs-4"><?= $totalCacheCount ?> mục</p>
                        <p class="text-white-50">Xem chi tiết ➜</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Sản phẩm đang cache -->
        <div class="col-md-4">
            <a href="product_cache_detail.php" class="text-decoration-none">
                <div class="card text-bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">📦 Sản phẩm đang cache</h5>
                        <p class="card-text fs-4"><?= $productCacheCount ?> sản phẩm</p>
                        <p class="text-white-50">Xem chi tiết ➜</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Hit/Miss -->
<div class="col-md-4">
    <a href="memcached_detail.php" class="text-decoration-none">
        <div class="card text-bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">📈 Hit/Miss</h5>
                <p class="card-text fs-6">
                    Hit: <?= $totalHits ?><br>
                    Miss: <?= $totalMisses ?><br>
                    Tỉ lệ Hit: <?= $hitRatio ?>%
                </p>
                <p class="text-white-50">Xem chi tiết ➜</p>
            </div>
        </div>
    </a>
</div>

    <!-- Các nút điều hướng -->
<div>
    <a href="edit_product.php" class="btn btn-outline-primary">✏️ Thêm sản phẩm</a>
    <a href="product_list.php" class="btn btn-outline-secondary">✏️ Danh sách sản phẩm</a>
    <a href="manage_product.php" class="btn btn-outline-warning">🛠️ Quản lý sản phẩm DB</a>
</div>
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-outline-secondary">🔙 Quay lại trang chủ</a>
    </div>
</div>
</body>
</html>