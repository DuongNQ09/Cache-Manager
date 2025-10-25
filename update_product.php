<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);

// Lấy ID sản phẩm từ query string
$id = $_GET['id'] ?? null;
if (!$id) {
    die("⚠️ Thiếu ID sản phẩm");
}

// Lấy dữ liệu sản phẩm từ cache
$product = $cache->getProduct($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;

    if ($name) {
        // Cập nhật dữ liệu sản phẩm
        $productData = [
            'id'    => $id,
            'name'  => $name,
            'price' => $price,
            'time'  => date('Y-m-d H:i:s')
        ];

        // Lưu lại vào cache (cập nhật product_keys luôn)
        $cache->setProduct($id, $productData, 3600);

        $message = "✅ Đã cập nhật sản phẩm trong cache thành công!";
        $product = $productData; // cập nhật lại dữ liệu hiển thị
    } else {
        $message = "⚠️ Vui lòng nhập tên sản phẩm.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>✏️ Cập nhật sản phẩm</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>✏️ Cập nhật sản phẩm trong Cache</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($product): ?>
        <form method="post" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Mã sản phẩm</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($product['id']) ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" name="name" id="name" class="form-control" 
                       value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá</label>
                <input type="number" name="price" id="price" class="form-control" 
                       value="<?= htmlspecialchars($product['price']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">💾 Lưu thay đổi</button>
            <a href="product_list.php" class="btn btn-outline-secondary">🔙 Quay lại danh sách</a>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Không tìm thấy sản phẩm trong cache</div>
        <a href="product_list.php" class="btn btn-outline-secondary">🔙 Quay lại danh sách</a>
    <?php endif; ?>
</div>
</body>
</html>