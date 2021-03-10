<?php namespace inanh86\Controller;

use WP_Error;

if(!defined('ABSPATH')) {
    exit;
}

class Baoloi {
    /**
     * Nhận lổi và in ra màn hình
     * @param int $code
     * @param string $messege
     * @param object $action báo cáo hành động dẫn tới lổi
     */
    public function __construct($code, $messege, $action=null) {
        $error = new WP_Error($code, $messege, ['status' => 200]);
        return $error;
    }
    /**
     * Ghi lổi vào database 
     * @param string $error
     */
    function save($error) {
        global $wpdb;
        
    }
}