<?php namespace inanh86\DB;

use inanh86\DB\DB;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Truy cập database lấy thông tin khách hàng
 */
class Khachhang extends DB {

    const LOGIN_FAIL = 1501;
    const FAIL_PROSS = 1500;
    public $khachhang = 'khach_hang';
    public $khachhang_meta = 'khach_hang_meta';

    public function __construct($query=null) {
        if( !empty($query) ) {
            
        }
    }
    /**
     * Đăng nhập Client
     * @param int $id
     * @param string $pass
     */
    public function dang_nhap($id=null,$pass=null) {
        if (!empty($id) && !empty($pass)) {
            $this->data['id'] = sanitize_text_field($id);
            $this->data['pass'] = sanitize_text_field($pass);
            return $this->data['id'];
        } else {
            throw new \Exception("Số điện thoại và mật khẩu của bạn không được để trống", self::LOGIN_FAIL);
        }
    }
    /**
     * Đăng ký tài khoản
     * @param array $data
     */
    public function dang_ky($id,$pass,$name,$diachi) {
        global $wpdb;

        $this->data['id'] = sanitize_text_field($id);
        $this->data['pass'] = sanitize_text_field($pass);
        $this->data['name'] = sanitize_text_field($name);
        $this->data['diachi'] = sanitize_text_field($diachi);
        $set_date = date('Y-m-d H:i:s');
        
        // kiểm tra sdt khách hàng trước khi khởi tạo
        if( $this->goi_khach_hang($this->data['id'],'phone') ) {
            throw new \Exception("Khách hàng đã tồn tại", self::FAIL_PROSS);
        }

        // Thêm Khách hàng vào database
        $new_khachang = $wpdb->query($wpdb->prepare(
            "INSERT INTO {$wpdb->prefix}khach_hang (login, pass, phone, ngay_dang_ky)  VALUES (%d, %s, %d, %s)", 
            [
                $this->data['id'], // ID khách hàng
                wp_hash_password($this->data['pass']), // create pass MD5
                $this->data['id'], // add số điện thoại khách hàng
                $set_date, // cài đặt ngày khởi tạo khách hàng
            ]
        ));

        // Báo lổi nếu không insert được
        if( !$new_khachang ) {
            throw new \Exception("Có lổi trong quá trình xử lý tạo khách hàng mới", self::FAIL_PROSS);   
        }

        // Trả kết quả khi hoàn thành 
        return $this->resouce('Thêm khách hàng thành công');
    }
    /**
     * Gọi data khách hàng || kiểm tra id khách hàng || trả thông tin khách hàng
     * @param int $id sử dụng id khách hàng để gọi
     * @use id hoặc số điện thoại
     */
    public function goi_khach_hang($id=null,$phone=null) {
        global $wpdb;

        if( empty($id) ) {
            throw new \Exception("id khách hàng không tồn tại", self::FAIL_PROSS);
        }
        if ( !strlen($id) === 9 ) {
            throw new \Exception("Lổi số điện thoại khách hàng không đúng", self::FAIL_PROSS);   
        }
        // kiểm tra đầu vào là id hay là sdt 
        if(!empty($phone)) {
            $this->data['id'] = substr($id, 1,8);
        } else {
            $this->data['id'] = $id;
        }
        
        // xử lý query truy cập kiểm tra xem khách hàng có mặt ở đó k?
        $query = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}{$this->khachhang} WHERE login = %d", [$this->data['id']]
        ));

        if( !$query ) {
            return false;
        }
        
        return $this->resouce($query);
    }
}