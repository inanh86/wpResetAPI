<?php namespace inanh86\DB;

if(!defined('ABSPATH')) {
    exit;
}

class DB {

    public $results;
    public $data = [];
    const HOANTHANH = 200;

    public function db() {
        global $wpdb;
        return $wpdb;
    }
    /**
     * Thông báo hoàn thành 1 cái gì đó 
     * @param string $data
     */
    public function resouce($data=null) {
        if( empty($data) ) {
            throw new \Exception("Không được bỏ trống nội dung");
        }
        return [
            'code' => self::HOANTHANH,
            'messege' => $data,
        ];
    }
}