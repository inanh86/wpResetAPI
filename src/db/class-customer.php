<?php namespace inanh86\DB;

use inanh86\Controller\Baoloi;
use inanh86\DB\DB;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Truy cập database lấy thông tin khách hàng
 */
class Khachhang extends DB {

    private $results;
    private $user;
    private $pass;
    const LOGIN_FAIL = 1501;
    const DANG_KY_FAIL = 1504;

    public function __construct($user=null, $pass=null) {

        $this->user = sanitize_text_field($user);
        $this->pass = sanitize_text_field($pass);

        // Kiểm tra user + pass có tồn tại hay không
        if (!empty($this->user) && !empty($this->pass)) {
            $this->query();
        } else {
            throw new \Exception("Mật khẩu hoặc id của bạn không được bỏ trống", self::LOGIN_FAIL);
        }
    }
    /**
     * Xử lý gọi thông tin khách hàng
     */
    public function query() {
        $querys = $this->db->query();
    }
    /**
     * Đăng nhập Client
     * @return $results
     */
    public function dang_nhap() {
        return $this->results;
    }
    /**
     * Đăng ký tài khoản
     */
    public function dang_ky($data=[]) {
        $new_client = $this->db->query(
            $this->db->prepare(
                "INSERT INTO {$this->prefix}customer ()"
            )
        );
        if (!$new_client) {
            throw new \Exception("Lổi không thể khởi tạo tài khoản khách hàng ngay lúc này", self::DANG_KY_FAIL);
        }
        return $new_client;
    }
}