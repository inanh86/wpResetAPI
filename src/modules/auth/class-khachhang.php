<?php namespace inanh86\Modules\Auth;

use inanh86\DB\Khachhang as Customer;
use Exception;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Modules Xử lý yêu cầu liên qan tới khách hàng
 */
class Khachhang extends Token {

    // khai báo mã lổi
    const LOGIN_FAIL = 1400; 
    const DANG_KY_FAIL = 1504;
    public static $key_encode_token = 'inanh86_khach_hang_api';

    /**
     * Đăng nhập cho khách hàng
     * @param string $customer
     * @param string $pass
     */
    public static function dang_nhap($id=null,$pass=null) {

        if( empty($id) || empty($pass) ) {
            throw new Exception("Lổi không được bỏ trống id/pass", self::LOGIN_FAIL);   
        }

        $khach_hang = new Customer();
        
        if ( !$khach_hang->dang_nhap($id,$pass) ) {
            throw new Exception("Tài khoản hoặc mật khẩu của bạn không đúng!", self::LOGIN_FAIL);
        }

        // gọi db khách hàng
        $khachhang = $khach_hang->results();
        
        // trả về thông tin khách hàng đăng nhập thành công
        return [
            'id' => $khachhang['id'],
            'phone' => '0'.$khachhang['phone'],
            'token' => self::khoi_tao_token('khach_hang', $khachhang['id'],
                [
                    'quyentruycap' => self::quyen_truy_cap($khachhang['id']),
                    'levels' => self::set_levels($khachhang['id']),
                ], 
            self::$key_encode_token),
            'status' => null,
        ];

    }
    /**
     * Khởi tạo tài khoản khách hàng
     * @param array $data
     */
    public static function dang_ky($data) {
        
        // kiểm tra các điều kiện có đủ hay chưa 
        if( empty($data['id']) || empty( $data['pass']) || empty($data['name']) ) {
            throw new Exception("không được bỏ trống thông tin người dùng", self::DANG_KY_FAIL);
        }
        // gọi class và chạy yêu cầu
        $new_khachhang = new Customer();
        return $new_khachhang->dang_ky($data['id'], $data['pass'], $data['name'], $data['diachi']);
        
    }
    /**
     * Set quyên truy cập cho khách hàng
     * @param int $id
     */
    public static function quyen_truy_cap($id=null) {
        
    }
    /**
     * Set Levels khách hàng 
     */
    public static function set_levels($id=null) {

    }
}