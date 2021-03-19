<?php namespace inanh86\DB;

use inanh86\DB\DB;
use Exception;

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
            $this->query($query);
        }
    }
    /**
     * Gọi danh sách khách hàng theo 
     * @param [] $data
     */
    public function query($data=null) {

    }
    /**
     * Trả kết quả khi xử lý thành công
     */
    public function results() {
        return $this->results;
    }
    /**
     * Đăng nhập Client
     * @param int $id
     * @param string $pass
     */
    public function dang_nhap($id=null,$pass=null) {
        
        $this->data['id'] = sanitize_text_field($id);
        $this->data['pass'] = sanitize_text_field($pass);
        $client = $this->goi_khach_hang('login', $this->data['id']);

        if( $client && wp_check_password( $this->data['pass'], $client[0]->pass) ) {
            return $this->results = [
                'id' => $client[0]->khach_hang_id,
                'phone' => $client[0]->phone,
                'name' => $this->goi_meta($client[0]->khach_hang_id, 'ten_khach_hang'),
            ];
        } else {
            throw new Exception("Mật khẩu của bạn không đúng", self::LOGIN_FAIL);
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
        
        // Kiểm tra phone number
        if ( is_phone_number($this->data['id']) ) {
            throw new Exception("Số điện thoại khách hàng bị sai, hình như thiếu số", self::FAIL_PROSS);
        }

        // kiểm tra sdt khách hàng trước khi khởi tạo
        if( $this->goi_khach_hang( 'phone', $this->data['id'] ) ) {
            throw new Exception("Khách hàng đã tồn tại", self::FAIL_PROSS);
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
        
        // Thêm db meta cho customer
        $this->add_data([
            'id' => $wpdb->insert_id,
            'meta_value' => $meta->value,
            'meta_key' => $meta->key
        ]);
        

        // Báo lổi nếu không insert được
        if( !$new_khachang ) {
            throw new Exception("Có lổi trong quá trình xử lý tạo khách hàng mới", self::FAIL_PROSS);   
        }

        // Trả kết quả khi hoàn thành 
        return $this->resouce('Thêm khách hàng thành công');
    }
    /**
     * Gọi data khách hàng theo id 
     * 
     * @@ Sử dụng để gọi 1 khách hàng duy nhất
     * @param int $id sử dụng id khách hàng để gọi
     * @use id hoặc số điện thoại
     */
    public function goi_khach_hang($type=null, $id=null) {
        global $wpdb;

        if( empty($id) ) {
            throw new Exception("id khách hàng không tồn tại", self::FAIL_PROSS);
        }
        if ( is_phone_number($id) ) {
            throw new Exception("Lổi số điện thoại khách hàng không đúng", self::FAIL_PROSS);   
        }
        if( $type === "phone" || $type === 'login' ) {
            $id_khachHang = substr($id, 1,8);
        } else {
            $id_khachHang = $id;
        }

        // xử lý query truy cập kiểm tra xem khách hàng có mặt ở đó k?
        $query = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}{$this->khachhang} WHERE login = %d", [$id_khachHang]
        ));

        if( !$query ) {
            return false;
        }
        return $query;
    }
    /**
     * Thêm data cho khách hàng
     * @param int $id
     */
    public function add_data($data=null) {
        global $wpdb;

        // Xử lý query db
        $meta_khachhang = $wpdb->query($wpdb->prepare(
            "INSERT INTO {$wpdb->prefix}khach_hang_meta (khach_hang_id, meta_key, meta_value) VALUES(%d, %s, %s)",
            [$data['id'], $data['meta_key'], $data['meta_value']]
        ));

        // Trả về lổi nếu có 
        if(!$meta_khachhang) {
            throw new \Exception("Lổi không thể thêm được thông tin của khách hàng", self::FAIL_PROSS);
        }
        return $meta_khachhang;
    }
    /**
     * gọi thông tin thêm của khách trong bảng meta
     * @param
     */
    public function goi_meta($id=null, $meta_key=null) {
        global $wpdb;
        
    }
}