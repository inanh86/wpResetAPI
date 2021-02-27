<?php namespace inanh86\Auth;

use Exception as BaoLoi;
use inanh86\Auth\Permission as Auth;

if(!defined('ABSPATH')) {
    exit;
}

/**
 * Danh sách các hành động cần thực thi của Auth
 * @see link
 * @version 1.0
 */
class Accounts extends Auth {
    
    public const KEY_ACCOUNT = 'api_key_account';

    /**
     * Truy cập vào tài khoản người dùng
     * @param string $user
     */
    protected function goiUser($user, $pass) {
        $user = sanitize_text_field($user);
        $pass = sanitize_text_field($pass);
        $Client = get_user_by('login', $user);
        if ( $Client && wp_check_password($pass, $Client->data->user_pass, $Client->ID) ) {
            return $Client;
        } else {
            throw new BaoLoi("Tài khoản đăng nhập của bạn không tồn tại", Auth::TAIKHOANFAIL);
        }
    }
    /**
     * Truy cập meta Value Của Client
     * @param int $ID
     * @param string $key
     */
    protected function goiMetaUser($ID, $key) {
        $ClientMeta = get_user_meta($ID, $key, true);
        if ($ClientMeta) {
            return $ClientMeta;
        } else {
            throw new Baoloi("Lổi không lấy đc thông tin của người dùng", $this->loidangnhap);
        }
    }
    /**
     * Đăng nhập theo yêu cầu Của Client
     * @param string $user
     * @param string $pass
     */
    public function danh_nhap($user,$pass) {
        $Client = $this->goiUser($user, $pass);
        if ( $Client ) {
            return [
                // Client
                'name' => $Client->data->display_name,
                'token' => static::encodeToken([
                    'status' => true, // đã hoàn tất đăng nhập
                    'id' => $Client->ID,
                    'cap' => $this->goiMetaUser($Client->ID, 'api_capabilities'),
                    'permission' => static::setQuyentruycap($Client->ID),
                ], $this->key),
            ];
           
        } else {
            throw new Baoloi("Đăng nhập thất bại do tài khoản của bạn không đúng hoặc không tồn tại", $this->loidangnhap);
        }
    }
    /**
     * Đăng ký tài khoản cho Client
     * @param string $user
     * @param string $pass 
     */
    public function dang_ky( $user, $pass ) {

    }
}