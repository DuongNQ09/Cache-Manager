<?php
require_once 'auth.php';
require_once 'db.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    if ($_POST['submit'] === 'Thêm') {
        addProduct($pid, $name, $price);
        $message = "✅ Đã thêm sản phẩm.";
    } elseif ($_POST['submit'] === 'Cập nhật') {
        updateProduct($pid, $name, $price);
        $message = "✅ Đã cập nhật sản phẩm.";
    }
}

if ($action === 'xoa' && $id) {
    deleteProduct($id);
    $message = "🗑️ Đã xóa sản phẩm.";
}

$editProduct = ($action === 'sua' && $id) ? getProductById($id) : null;
$products = getAllProducts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>🛠️ Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">🛠️ Quản lý sản phẩm</h2>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 mb-4 shadow-sm">
        <div class="mb-3">
            <label>ID sản phẩm</label>
            <input type="number" name="id" class="form-control" value="<?= $editProduct['id'] ?? '' ?>" required <?= $editProduct ? 'readonly' : '' ?>>
        </div>
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="<?= $editProduct['name'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" value="<?= $editProduct['price'] ?? '' ?>" required>
        </div>
        <button type="submit" name="submit" value="<?= $editProduct ? 'Cập nhật' : 'Thêm' ?>" class="btn btn-success">
            <?= $editProduct ? 'Cập nhật' : 'Thêm' ?> sản phẩm
        </button>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= number_format($p['price'], 0, ',', '.') ?>₫</td>
                    <td>
                        <a href="?action=sua&id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="?action=xoa&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
    </div>
</div>
</body>
</html>