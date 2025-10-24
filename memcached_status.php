<?php
require_once 'auth.php';
require_once 'cache.php';
$cache = new CacheManager($memcached);
$stats = $cache->getStats();
?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Trạng thái Memcached</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css