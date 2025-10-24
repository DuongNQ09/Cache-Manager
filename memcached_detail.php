<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);
$stats = $cache->getStats();

$hits = $misses = $curr_items = $memory = 0;
foreach ($stats as $server => $data) {
    $hits += $data['get_hits'] ?? 0;
    $misses += $data['get_misses'] ?? 0;
    $curr_items += $data['curr_items'] ?? 0;
    $memory += $data['bytes'] ?? 0;
}
$hit_rate = ($hits + $misses) > 0 ? round($hits / ($hits + $misses) * 100, 2) : 0;
$miss_rate = 100 - $hit_rate;
$memory_mb = round($memory / 1024 / 1024, 2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Chi tiết Memcached</title>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">📊 Phân tích hiệu suất Memcached</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <canvas id="hitMissPie"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="hitMissBar"></canvas>
        </div>
    </div>

    <table class="table table-bordered table-sm">
        <tr><th>Tổng mục đang cache</th><td><?= $curr_items ?></td></tr>
        <tr><th>Số lần truy xuất thành công (Hit)</th><td><?= $hits ?></td></tr>
        <tr><th>Số lần truy xuất thất bại (Miss)</th><td><?= $misses ?></td></tr>
        <tr><th>Tỷ lệ Hit</th><td><?= $hit_rate ?>%</td></tr>
        <tr><th>Dung lượng sử dụng</th><td><?= $memory_mb ?> MB</td></tr>
    </table>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">🔙 Quay lại Dashboard</a>
    </div>
</div>

<script>
const pieCtx = document.getElementById('hitMissPie').getContext('2d');
new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Hit', 'Miss'],
        datasets: [{
            data: [<?= $hits ?>, <?= $misses ?>],
            backgroundColor: ['#198754', '#dc3545'],
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Tỷ lệ Hit/Miss (Pie Chart)'
            }
        }
    }
});

const barCtx = document.getElementById('hitMissBar').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Hit', 'Miss'],
        datasets: [{
            label: 'Số lần truy xuất',
            data: [<?= $hits ?>, <?= $misses ?>],
            backgroundColor: ['#198754', '#dc3545'],
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Số lần truy xuất (Bar Chart)'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
</body>
</html>