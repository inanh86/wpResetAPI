<?php namespace inanh86\Modules\Auth;

use inanh86\DB\Khachhang as Customer;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modules Xử lý yêu cầu liên qan tới khách hàng
 */
class Khachhang {

    // khai báo mã lổi
    const CUSTOM_LOGIN_FAIL = 1400; 
    
    /**
     * Đăng nhập cho khách hàng
     * @param string $customer
     * @param string $pass
     */
    public static function dang_nhap($user, $pass) {
        $customer = new Customer($user, $pass);
        $khachhang_login =  $customer->dang_nhap();
        if (!$khachhang_login) {
            throw new \Exception("Tài khoản hoặc mật khẩu của bạn không đúng!", self::CUSTOM_LOGIN_FAIL);
        }
        return $khachhang_login;
    }
    /**
     * Khởi tạo tài khoản khách hàng
     * @param string $customer
     */
    public static function dang_ky($user,$pass,$phone,$name) {
        $new_khachhang = new Customer();
    }
}