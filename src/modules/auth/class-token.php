<?php namespace inanh86\Modules\Auth;

use \Firebase\JWT\JWT;

if(!defined('ABSPATH')) {
    exit;
}

/**
 * Modules Xử lý token cho client
 */
class Token {

    const TOKEN_CODE = 'inanh86_rest_api_token_error';
    const TOKENFAIL = 1002;
    const QUYENGHI = 1003;
    
    /**
     * Kiểm tra quyền đọc
     * @param object $request giá trị client truyền lên
     * @param string $key khóa servers
     * @since 1.0
     */
    public static function Read($token, $key) {

        $read = self::decodeToken($token, $key);
        if($read->permission->read === true ) {
            return $read;
        } else {
            throw new \Exception("Bạn không có quyền truy cập rồi :(");
        }

    }
    /**
     * Đọc token từ client kiểm tra xem có quyền ghi/xóa hay không
     * @param sting $token
     * @param string $key
     */
    public static function Write($token, $key) {

        $token = self::decodeToken($token, $key);
        if ($token->permission->write === true && $token->permission->read === true ) {
            return $token;
        } else {
            throw new \Exception("Bạn không có quyền truy chỉnh sửa nội dung này.");   
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
        if( empty($token) || $token === NULL ) {
            return new \WP_Error(self::TOKEN_CODE, 'Token của bạn không tồn tại.', ['status' => 400]);
        }
        return JWT::decode($token, $key, array('HS256'));
    }
    /**
     * Xử lý token và insert vào db
     * @param int $id khách hàng hoặc account 
     * @param array nội dung cần encode để trả về cho client
     */
    public static function khoi_tao_token($dbname=null, $id=null, $payload=null, $private_key=null) {
        global $wpdb;
        
        if( empty($token) ) {
            throw new \Exception("Lổi token không được phép bỏ trống", self::TOKENFAIL);
        }
        $token = self::encodeToken($payload, $private_key);
        $token = explode('.', $token);
        return $token;
    }
}