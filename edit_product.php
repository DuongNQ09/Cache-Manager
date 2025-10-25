<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_POST['id'] ?? null;
    $name  = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;

    if ($id && $name) {
        // Tạo dữ liệu sản phẩm
        $productData = [
            'id'    => $id,
            'name'  => $name,
            'price' => $price,
            'time'  => date('Y-m-d H:i:s')
        ];

        // Lưu vào cache và cập nhật product_keys
        $cache->setProduct($id, $productData, 3600);

        $message = "✅ Đã thêm sản phẩm vào cache thành công!";
    } else {
        $message = "⚠️ Vui lòng nhập đầy đủ thông tin sản phẩm.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>✏️ Thêm sản phẩm vào Cache</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>✏️ Thêm sản phẩm vào Cache</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="id" class="form-label">Mã sản phẩm</label>
            <input type="text" name="id" id="id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">💾 Lưu vào Cache</button>
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
    </form>
</div>
</body>
</html>