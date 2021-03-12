<?php namespace inanh86\Modules\Auth;

use inanh86\DB\Khachhang as Customer;


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modules Xử lý yêu cầu liên qan tới khách hàng
 */
class Khachhang extends Token {

    // khai báo mã lổi
    const CUSTOM_LOGIN_FAIL = 1400; 
    const DANG_KY_FAIL = 1504;

    /**
     * Đăng nhập cho khách hàng
     * @param string $customer
     * @param string $pass
     */
    public static function dang_nhap($id,$pass) {
        $customer = new Customer();
        $khachhang_login =  $customer->dang_nhap($id,$pass);
        if (!$khachhang_login) {
            throw new \Exception("Tài khoản hoặc mật khẩu của bạn không đúng!", self::CUSTOM_LOGIN_FAIL);
        }
        return $khachhang_login;
    }
    /**
     * Khởi tạo tài khoản khách hàng
     */
    public static function dang_ky($data) {
        
        if( empty($data['id']) || empty( $data['pass']) || empty($data['name']) ) {
            throw new \Exception("không được bỏ trống thông tin người dùng", self::DANG_KY_FAIL);
        }
        $new_khachhang = new Customer();
        return $new_khachhang->dang_ky($data['id'], $data['pass'], $data['name'], $data['diachi']);
    }
}