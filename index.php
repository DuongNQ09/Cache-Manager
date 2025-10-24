<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>🧠 Cache Manager - Trang chủ</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5">🧠 Hệ thống Quản lý Cache</h1>
        <p class="lead">Quản lý sản phẩm và hiệu suất cache dễ dàng, nhanh chóng.</p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">✏️ Chỉnh sửa sản phẩm (cache)</h5>
                    <a href="edit_product.php" class="btn btn-primary">Truy cập</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">📊 Trạng thái Memcached</h5>
                    <a href="memcached_status.php" class="btn btn-info">Truy cập</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">📋 Danh sách sản phẩm</h5>
                    <a href="product_list.php" class="btn btn-secondary">Truy cập</a>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-4">
        <?php if ($isLoggedIn): ?>
            <a href="dashboard.php" class="btn btn-outline-success">📂 Vào Dashboard</a>
            <a href="logout.php" class="btn btn-outline-danger ms-2">🔓 Đăng xuất</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline-primary">🔐 Đăng nhập</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>