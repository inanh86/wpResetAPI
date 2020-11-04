<?php namespace inanh86\Api;

if(!defined('ABSPATH')) {
    exit;
}
/**
 * Lấy sản phẩm từ API 
 * @since 0.1
 */
class SanPham extends \inanh86\Api\Resouce {
    
    protected $base = '/san-pham';

    public function dangky_route() {

        // gọi danh sách sản phẩm
        $router = $this->router($this->base.'/', [
            [
                'methods'             => $this->GET,
                'callback'            => [$this, 'danh_sach_khach_hang'],
                'permission_callback' => [$this, 'kientraquyentruycap']
            ]
        ]);
        return $router;
    }
    public function danh_sach_khach_hang($request) {
        var_dump($request);
    }
}