<?php
require_once 'auth.php';
require_once 'cache.php';

$cache = new CacheManager($memcached);

// Lấy danh sách key sản phẩm
$productKeys = $cache->getProductKeys();

if (!empty($productKeys)) {
    foreach ($productKeys as $key) {
        $cache->delete($key); // xóa từng key
    }
    // Reset lại danh sách product_keys
    $cache->set("product_keys", []);
}

// Quay lại trang danh sách sản phẩm cache
header("Location: product_cache_detail.php");
exit;