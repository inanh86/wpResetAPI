<?php namespace inanh86\Auth;

use \Firebase\JWT\JWT;
use WP_User as User;
use Exception as Baoloi; 

if(!defined('ABSPATH')) {
    exit;
}

/**
 * Kiểm tra quyền truy cập của Client
 * @since 1.0
 */
class Permission {

    // Setup Mã lổi Khi thao tác 
    public const TAIKHOANFAIL = 1001;
    public const TOKENFAIL = 1002;
    public const QUYENGHI = 1003;
   
    /**
     * Kiểm tra quyền đọc
     * @param object $request <- giá trị client truyền lên
     * @param string $key <- khóa servers
     * @since 1.0
     */
    public static function Read($request, $key) {
        try {
            $token = $request;
            if ( isset($token) && !empty($token)) {
                $token = self::decodeToken($token, $key);
                return $token;
            } else {
                throw new \Exception("Token không tồn tài hoặc đã hết hạn!");
            }
        } catch (\Exception $e) {
            return new \WP_Error(self::TOKENFAIL, $e->getMessage(), ['status'=>200]);
        }
    }
    /**
     * Đọc token từ client kiểm tra xem có quyền ghi/xóa hay không
     * @param sting $token
     * @param string $key
     */
    public static function Write($token, $key) {
         try {
            $token = self::decodeToken($token, $key);
            if ($token->permission->write === true && $token->permission->read === true ) {
                return $token;
            } else {
                throw new \Exception("Bạn không có quyền truy chỉnh sửa nội dung này.");   
            }
            
        } catch (\Exception $e) {
            return new \WP_Error(self::QUYENGHI, $e->getMessage(), ['status'=>200]);
        }
    }
    /**
     * Encode Token gữi ghi vào db private key và gữi public key cho client
     * @param array $payload nội dung cần encode
     * @param string $key khóa tại máy chủ
     */
    public static function encodeToken($payload, $key) {
        $token = JWT::encode($payload, $key);
        return $token;
    }
    /**
     * Decode Token đc client gữi lên
     * @param string $token
     * @param string $key
     * @since 1.0
     */
    public static function decodeToken($token, $key) {
        $decode = JWT::decode($token, $key, array('HS256'));
        return $decode;
    }
    /**
     * Thiết lập quyền truy cập cho từng Role
     * @param int $ID id client truyền vào
     */
    public static function setQuyentruycap($ID) {

        $customer = self::UserName($ID);

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