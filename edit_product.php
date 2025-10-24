<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!DOCTYPE html>
<html>
<head>
    <title>✏️ Chỉnh sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">✏️ Chỉnh sửa sản phẩm</h2>
    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label>ID sản phẩm</label>
            <input type="text" name="id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Thời gian cache (giây)</label>
            <input type="number" name="ttl" class="form-control" value="300">
        </div>
        <button type="submit" class="btn btn-success">Lưu sản phẩm</button>
    </form>
</div>
</body>
</html>