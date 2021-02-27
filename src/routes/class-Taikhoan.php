<?php namespace inanh86\Routes;
if(!defined('ABSPATH')) {
    exit;
}

use inanh86\Api\Auth\Hanh_dong as Taikhoan;

class QuanlyTaiKhoan extends \inanh86\Api\Resouce {

    protected $base = '/tai-khoan';
    protected $code_error = 'api_nhanvien_error';
    
    /**
     * Đăng ký endpoint /tai-khoan
     * @see link https://blog.com/restAPI
     * @version 1.0
     */
    public function dangky_route() {
        // Danh Sách Khách Hàng
        $router = $this->router($this->base.'/dang-nhap', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'dang_nhap'],
                'permission_callback' => '__return_true'
            ]
        ]);
        return $router;
    }
    /**
     * Đang nhập hệ thống
     * @param object $requset
     */
    public function dang_nhap($requset) {
        try {
            $Client = new Taikhoan();
            return $this->Resouce($Client->danh_nhap($requset['tendangnhap'], $requset['matkhau']));
        } catch(\Exception $e) {
            return $this->Error($e->getCode(), $e->getMessage());
        }
    }
}