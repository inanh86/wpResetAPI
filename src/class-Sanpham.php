<?php 

namespace inanh86\Api;

use inanh86\Api\Resouce;

if(!defined('ABSPATH')) {
    exit;
}
/**
 * Lấy sản phẩm từ API 
 * @since 0.1
 */
class SanPham extends Resouce {
    
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
        $token =  \inanh86\Api\Permission::encodeToken([
"iss" => "http://example.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,
    "nbf" => 1357000000
        ], 'example_key');
        return $this->Resouce($token);
    }
}