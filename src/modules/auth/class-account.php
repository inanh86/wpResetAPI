<?php namespace inanh86\Modules\Auth;

use inanh86\Controller\Baoloi;
use inanh86\Modules\Auth\Token;
use inanh86\DB\DB;
use WP_User_Query;

if(!defined('ABSPATH')) {
    exit;
}

/**
 * Danh sách các hành động cần thực thi của Auth
 * @see link
 * @version 1.0
 */
class Accounts {
    
    const KEY_ACCOUNT_TOKEN = 'api_key_account'; // Key encode Token
    const DANG_NHAP_THAT_BAI = 1500; // Đăng nhập thất bại
    const TAI_KHOAN_KHONG_TON_TAI = 1504; // Tải khoản không tồn tải
    const USER_FAIL_META = 1501; // gọi user name meta Lổi
    
    /**
     * Đăng nhập theo yêu cầu Của Client
     * @param string $user
     * @param string $pass
     */
    public static function danh_nhap($user,$pass) {
        $Client = self::goi_user($user, $pass);
        if ( $Client ) {
            return [
                // Client
                'status' => true,
                'username' => $Client->data->display_name,
                'token' => Token::encodeToken([
                    'cap' => self::goi_user_meta($Client->ID, 'api_capabilities'),
                    'permission' => self::quyen_truy_cap($Client->ID),
                ], self::KEY_ACCOUNT_TOKEN ),
            ];
        } else {
            throw new \Exception("Đăng nhập thất bại do tài khoản của bạn không đúng hoặc không tồn tại", self::DANG_NHAP_THAT_BAI);
        }
    }
    /**
     * Đăng ký tài khoản cho Client
     * @param string $user
     * @param string $pass 
     * @version 1.0
     * @see link 
     */
    public static function dang_ky( $user, $pass ) {

    }
    /**
     * Gọi danh sách username trong database
     * @param int $start trang bắt đầu 
     * @param int $limit giới hạn số user gọi ra
     * @see link 
     */
    public static function goi_danh_sach($start=1,$limit=null) {
        $users = new WP_User_Query(['paged'=>$start, 'order' => 'DESC',]);
        $data = []; 
        foreach( $users->get_results() as $i => $user ) {
            $data[$i]['id'] = (int) $user->data->ID;
            $data[$i]['ten'] = $user->data->display_name;
            $data[$i]['roles'] = $user->roles;
            $data[$i]['ngay_khoi_tao'] = $user->data->user_registered;
        }
        return $data;
    }
    /**
     * Truy cập vào tài khoản người dùng
     * @param string $user
     */
    public static function goi_user($user, $pass) {

        $user = sanitize_text_field($user);
        $pass = sanitize_text_field($pass);

        $Client = get_user_by('login', $user);
        if ( $Client && wp_check_password($pass, $Client->data->user_pass, $Client->ID) ) {
            return $Client;
        } else {
            throw new BaoLoi("Tài khoản của bạn không tồn tại", self::DANG_NHAP_THAT_BAI);
        }
    }
    /**
     * Truy cập meta Value Của Client
     * @param int $ID
     * @param string $key
     */
    public static function goi_user_meta($ID, $key, $single=true) {
        $ClientMeta = get_user_meta($ID, $key,$single);
        if (!$ClientMeta) {
            throw new Baoloi("Lổi không lấy đc thông tin của người dùng", self::USER_FAIL_META);
        }
        return $ClientMeta;
    }
    /**
     * Kiểm tra ID client 
     * @param int $id
     * @version 1.0
     * @see link
     */
    public static function goi_user_data($id) {
        $user_data = get_userdata($id);
        if($user_data) {
            return $user_data;
        } else {
            throw new BaoLoi("ID Tài khoản này không tồn tại, Vui lòng liên hệ Ad", self::TAI_KHOAN_KHONG_TON_TAI);
        }
    }
    /**
     * Thiết lập quyền truy cập cho từng Role
     * @param int $ID id client truyền vào
     */
    public static function quyen_truy_cap($ID, $type=null) {

        // Gọi user data
        $customer = static::goi_user_data($ID);

        if (!$customer) {
            return;
        }
        if ( array_intersect( ['administrator', 'api_shop_manager'], (array) $customer->roles ) ) {
            $permission = [
                'read' => true,
                'write' => true
            ];
        } else {
            $permission = [
                'read' => true,
                'write' => false
            ];
        }
        return $permission;
    }
}