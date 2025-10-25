<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'cache.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cache = new CacheManager($memcached);
$stats = $cache->getStats();

// Thống kê cache
$totalCacheCount = $productCacheCount = $totalHits = $totalMisses = 0;
if (is_array($stats)) {
    foreach ($stats as $server => $data) {
        $totalCacheCount += $data['curr_items'] ?? 0;
        $productCacheCount += $cache->countProducts();
        $totalHits += $data['get_hits'] ?? 0;
        $totalMisses += $data['get_misses'] ?? 0;
    }
}
$hitRatio = ($totalHits + $totalMisses) > 0 ? round(($totalHits / ($totalHits + $totalMisses)) * 100, 2) : 0;

// Lịch sử tìm kiếm
if (!isset($_SESSION['search_history'])) {
    $_SESSION['search_history'] = [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_history'])) {
    $_SESSION['search_history'] = [];
    header("Location: dashboard.php");
    exit;
}

// Tìm kiếm sản phẩm
$searchResults = [];
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$cacheStatus = '';
$responseTime = 0;
$hitTime = 0;
$missTime = 0;

if ($keyword !== '') {
    if (!in_array($keyword, $_SESSION['search_history'])) {
        $_SESSION['search_history'][] = $keyword;
    }

    $startTime = microtime(true);
    $cacheKey = "search_" . md5($keyword);
    $cachedSearch = $memcached->get($cacheKey);
    if ($cachedSearch !== false) {
        $searchResults = $cachedSearch;
        $cacheStatus = '✅ Cache Hit: tìm thấy kết quả trong bộ nhớ.';
        $hitTime = round((microtime(true) - $startTime) * 1000, 2);
        $responseTime = $hitTime;
    } else {
        $cacheStatus = '⚠️ Cache Miss: không tìm thấy, đang truy vấn từ cơ sở dữ liệu...';

        for ($i = 1; $i <= 1000; $i++) {
            $item = $memcached->get("product_$i");
            if ($item && stripos($item['name'], $keyword) !== false) {
                $searchResults[] = $item;
            }
        }

        if (empty($searchResults)) {
            $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ?");
            $stmt->execute(["%$keyword%"]);
            $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $memcached->set($cacheKey, $searchResults, 300);
        $missTime = round((microtime(true) - $startTime) * 1000, 2);
        $responseTime = $missTime;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Dashboard</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Dashboard Quản lý Cache</h2>
        <a href="logout.php" class="btn btn-outline-danger">Đăng xuất</a>
    </div>

    <!-- Tìm kiếm sản phẩm -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white">🔍 Tìm kiếm sản phẩm</div>
        <div class="card-body">
            <form method="get" class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="keyword" class="form-control" placeholder="Nhập tên sản phẩm..." required>
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-dark">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kết quả tìm kiếm -->
    <?php if ($keyword !== ''): ?>
        <h5 class="mt-4">🔍 Kết quả tìm kiếm cho: <strong><?= htmlspecialchars($keyword) ?></strong></h5>
        <?php if ($cacheStatus !== ''): ?>
            <div class="alert alert-info">
                <?= $cacheStatus ?><br>
                ⏱️ Thời gian phản hồi: <strong><?= $responseTime ?> ms</strong>
            </div>
        <?php endif; ?>
        <?php if (empty($searchResults)): ?>
            <div class="alert alert-warning">Không tìm thấy sản phẩm nào.</div>
        <?php else: ?>
            <table class="table table-bordered table-sm mt-3">
                <thead><tr><th>ID</th><th>Tên</th><th>Giá</th></tr></thead>
                <tbody>
                    <?php foreach ($searchResults as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= number_format($p['price']) ?> VNĐ</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Biểu đồ thời gian phản hồi -->
    <?php if ($keyword !== ''): ?>
        <div class="card mt-4">
            <div class="card-header bg-light">📊 So sánh thời gian phản hồi</div>
            <div class="card-body">
                <canvas id="responseChart" height="100"></canvas>
            </div>
        </div>
    <?php endif; ?>

    <!-- Lịch sử tìm kiếm -->
    <?php if (!empty($_SESSION['search_history'])): ?>
        <div class="card mb-4 mt-4">
            <div class="card-header bg-light">🕘 Lịch sử tìm kiếm gần đây</div>
            <div class="card-body">
                <ul class="list-inline">
                    <?php foreach ($_SESSION['search_history'] as $term): ?>
                        <li class="list-inline-item">
                            <a href="?keyword=<?= urlencode($term) ?>" class="btn btn-outline-secondary btn-sm"><?= htmlspecialchars($term) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <form method="post" class="mt-2">
                    <button type="submit" name="clear_history" class="btn btn-sm btn-danger">🗑️ Xóa lịch sử</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Thống kê cache -->
    <div class="row mt-4">
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
    </div>

       <!-- Điều hướng -->
    <div class="mb-4">
    <a href="edit_product.php" class="btn btn-outline-primary">✏️ Thêm sản phẩm</a>
    <a href="product_list.php" class="btn btn-outline-secondary">📋 Danh sách sản phẩm</a>
    <a href="manage_product.php" class="btn btn-outline-warning">🛠️ Quản lý sản phẩm DB</a>
    <a href="clear_all_cache.php"
       class="btn btn-danger ms-2"
       onclick="return confirm('Bạn có chắc muốn xóa toàn bộ cache?')">
       🧹 Xóa toàn bộ cache
    </a>
</div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-outline-secondary">🔙 Quay lại trang chủ</a>
    </div>
</div>


</body>
</html>