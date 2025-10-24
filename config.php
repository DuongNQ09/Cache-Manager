<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
$memcached = new Memcached();
$memcached->addServer('localhost', 11211);
?>