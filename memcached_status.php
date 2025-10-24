<?php
require_once 'cache.php';
$cache = new CacheManager($memcached);
$stats = $cache->getStats();

echo "<h2>📊 Trạng thái Memcached</h2>";
foreach ($stats as $server => $data) {
    echo "<h3>Server: $server</h3><ul>";
    foreach ($data as $key => $value) {
        echo "<li><strong>$key:</strong> $value</li>";
    }
    echo "</ul>";
}
?>