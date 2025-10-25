<?php
// Khởi tạo kết nối Memcached tập trung
$memcached = new Memcached();
$memcached->addServer("127.0.0.1", 11211);