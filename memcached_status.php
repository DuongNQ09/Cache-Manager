<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!DOCTYPE html>
<html>
<head>
    <title>📊 Trạng thái Memcached</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">📊 Trạng thái Memcached</h2>
    <?php
    require_once 'cache.php';
    $cache = new CacheManager($memcached);
    $stats = $cache->getStats();

    foreach ($stats as $server => $data) {
        echo "<div class='card mb-4'><div class='card-header bg-primary text-white'>Server: $server</div><div class='card-body'><table class='table table-bordered'>";
        foreach ($data as $key => $value) {
            echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
        }
        echo "</table></div></div>";
    }
    ?>
</div>
</body>
</html>