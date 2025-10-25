<?php
require_once 'config.php';

class CacheManager {
    private $memcached;

    public function __construct($memcached) {
        $this->memcached = $memcached;
    }

    /** ======================
     *  API tổng quát
     *  ====================== */
    public function set($key, $value, $ttl = 0) {
        return $this->memcached->set($key, $value, $ttl);
    }

    public function get($key) {
        return $this->memcached->get($key);
    }

    public function delete($key) {
        return $this->memcached->delete($key);
    }

    public function getStats() {
        return $this->memcached->getStats();
    }

    /** ======================
     *  API chuyên biệt cho sản phẩm
     *  ====================== */
    public function setProduct($id, $data, $ttl = 3600) {
        $key = "product_$id";
        $this->memcached->set($key, $data, $ttl);

        $keys = $this->memcached->get("product_keys") ?: [];
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            $this->memcached->set("product_keys", $keys);
        }
    }

    public function getProduct($id) {
        return $this->memcached->get("product_$id");
    }

    public function deleteProduct($id) {
        $key = "product_$id";
        $this->memcached->delete($key);

        $keys = $this->memcached->get("product_keys") ?: [];
        if (($idx = array_search($key, $keys)) !== false) {
            unset($keys[$idx]);
            $this->memcached->set("product_keys", array_values($keys));
        }
    }

    public function getProductKeys() {
        return $this->memcached->get("product_keys") ?: [];
    }

    public function countProducts() {
        return count($this->getProductKeys());
    }
}