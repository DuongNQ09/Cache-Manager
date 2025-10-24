<?php
require_once 'config.php';

class CacheManager {
    private $memcached;

    public function __construct($memcached) {
        $this->memcached = $memcached;
    }

    public function set($key, $value, $ttl = 300) {
        return $this->memcached->set($key, $value, $ttl);
    }

    public function get($key) {
        return $this->memcached->get($key);
    }

    public function delete($key) {
        return $this->memcached->delete($key);
    }

    public function flush() {
        return $this->memcached->flush();
    }

    public function getStats() {
        return $this->memcached->getStats();
    }
}
?>