<?php
require_once 'auth.php';
require_once 'cache.php';
$cache = new CacheManager($memcached);
$stats = $cache->getStats();

$productCount = 0;
foreach ($stats as $server => $data) {
    $productCount += $data['curr_items'] ?? 0;
}
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
        <div class="col-md-4">
            <div class="card text-bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">🧠 Tổng Cache</h5>
                    <p class="card-text fs-4"><?= $productCount ?> mục</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">📦 Sản phẩm đang cache</h5>
                    <p class="card-text fs-4"><?= $productCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">📈 Hit/Miss</h5>
                    <p class="card-text fs-6">
                        <?php
                        foreach ($stats as $server => $data) {
                            echo "Hit: {$data['get_hits']}<br>Miss: {$data['get_misses']}";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <a href="edit_product.php" class="btn btn-outline-primary">✏️ Thêm sản phẩm</a>
    <a href="product_list.php" class="btn btn-outline-secondary">📋 Giao diện sản phẩm cũ</a>
    <a href="manage_product.php" class="btn btn-outline-warning">🛠️ Quản lý sản phẩm DB</a>
</div>
</body>
</html>