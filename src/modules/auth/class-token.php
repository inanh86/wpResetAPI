<?php namespace inanh86\Modules\Auth;

use \Firebase\JWT\JWT;
use inanh86\Controller\Baoloi;

if(!defined('ABSPATH')) {
    exit;
}

/**
 * Modules Xử lý token cho client
 */
class Token {

    const TOKEN_COKE = 'inanh86_rest_api_token_error';
    const TOKENFAIL = 1002;
    const QUYENGHI = 1003;
    
    /**
     * Kiểm tra quyền đọc
     * @param object $request giá trị client truyền lên
     * @param string $key khóa servers
     * @since 1.0
     */
    public static function Read($token, $key) {
        try {
            if ( isset($token) && !empty($token) ) {
                $token = self::decodeToken($token, $key);
                return $token;
            } else {
                throw new \Exception("Token không tồn tài hoặc đã hết hạn!", self::TOKENFAIL);
            }
        } catch (\Exception $e) {
            return new Baoloi(self::TOKEN_COKE, $e->getMessage());
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
            return new Baoloi(self::QUYENGHI, $e->getMessage());
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
}