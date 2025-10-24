<?php
require_once 'auth.php';       // Bảo vệ bằng đăng nhập
require_once 'cache.php';      // Lớp quản lý cache

$cache = new CacheManager($memcached);
$stats = $cache->getStats();
?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Trạng thái Memcached</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">📊 Trạng thái Memcached</h2>

    <?php if (!$stats): ?>
        <div class="alert alert-danger">Không thể lấy thông tin từ Memcached.</div>
    <?php else: ?>
        <?php foreach ($stats as $server => $data): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    Server: <?= htmlspecialchars($server) ?>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Thông số</th>
                                <th>Giá trị</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $key => $value): ?>
                                <tr>
                                    <td><?= htmlspecialchars($key) ?></td>
                                    <td><?= htmlspecialchars($value) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
    </div>
</div>
</body>
</html>