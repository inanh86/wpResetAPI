<?php namespace inanh86\Api;
use \Firebase\JWT\JWT;

if(!defined('ABSPATH')) {
    exit;
}

class Permission {
    
    /**
     * Nhận giá chị $request truyền vào
     * @param object $request
     */
    public static function init($request, $key) {
        try {
            if( !empty($request['oauth_signature_token']) ) {
                
                $oauth_signature_token = self::decodeToken(sanitize_text_field($request['oauth_signature_token']), $key);

                return self::kiem_tra_quyen_truy_cap($oauth_signature_token, $request);

            } else {
                throw new \Exception("Không tìm thấy oauth_signature_token trong yêu cầu gữi lên của bạn!");
            }
        } catch (\Exception $e) {
            return new \WP_Error('api_permission_token_error', $e->getMessage(), ['status'=>401]);
        }
    }
    /**
     * Kiểm tra quyền truy cập của Client bằng Token Client gữi lên
     * @param array $oauth_signature
     * @param object $request
     */
    public static function kiem_tra_quyen_truy_cap($oauth_signature, $request) {
        
        return $request;
    }
    /**
     * encode Token gữi cho Client
     * @param array $payload
     * @param string $key
     */
    public static function encodeToken($payload, $key) {
        $token = JWT::encode($payload,$key);
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
     * @param string
     */
    public static function setQuyentruycap($customer_id) {

        $customer = new \WP_User($customer_id);

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