<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);
$stats = $cache->getStats();
?>
<!DOCTYPE html>
<html>
<head>
    <title>🧠 Chi tiết tổng cache</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>🧠 Thống kê tổng cache</h2>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>Server</th>
                <th>Items</th>
                <th>Hits</th>
                <th>Misses</th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($stats)): ?>
                <?php foreach ($stats as $server => $data): ?>
                    <tr>
                        <td><?= $server ?></td>
                        <td><?= $data['curr_items'] ?? 0 ?></td>
                        <td><?= $data['get_hits'] ?? 0 ?></td>
                        <td><?= $data['get_misses'] ?? 0 ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Không lấy được dữ liệu từ Memcached</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
</div>
</body>
</html>