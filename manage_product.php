<?php
require_once 'auth.php';
require_once 'db.php';

$products = getAllProducts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>📋 Danh sách sản phẩm từ DB</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">📋 Danh sách sản phẩm từ cơ sở dữ liệu</h2>

    <?php if (empty($products)): ?>
        <div class="alert alert-warning">Không có sản phẩm nào trong cơ sở dữ liệu.</div>
    <?php else: ?>
        <table class="table table-bordered table-hover table-sm">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= number_format($p['price']) ?></td>
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