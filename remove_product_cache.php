<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);

if (isset($_GET['key'])) {
    $key = $_GET['key'];

    // Nếu key có dạng product_ID thì xóa theo ID
    if (preg_match('/^product_(\d+)$/', $key, $matches)) {
        $cache->deleteProduct($matches[1]);
    } else {
        // fallback: xóa key bất kỳ
        $cache->delete($key);
    }
}

header("Location: product_cache_detail.php");
exit;