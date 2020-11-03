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
    public static function init($request) {
        try {
            if( !empty($request['oauth_signature_token']) ) {
                
                $oauth_signature_token = self::decodeToken(sanitize_text_field($request['oauth_signature_token']), 'example_key');
                return self::quyen_truy_cap($oauth_signature_token, $request);

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
    public static function quyen_truy_cap($oauth_signature, $request) {
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
}