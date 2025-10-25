<?php
require_once 'auth.php';
require_once 'cache.php';

$memcached->flush();
header("Location: dashboard.php");
exit;