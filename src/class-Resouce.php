<?php namespace inanh86\Api;

use \inanh86\Api\Permission;

if(!defined('ABSPATH')) {
    exit;
}

class Resouce {

    public $namespace = 'vendor/v1';

    /**
     * Method Đẩy lên từ Client
     * @param string 
     */
    protected $GET = \WP_REST_Server::READABLE;
    protected $POST = \WP_REST_Server::CREATABLE;

    protected $loimaychu = 500;
    protected $khongduoctruycap = 401;
    
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'dangky_route']);
        add_filter('nocache_headers', [$this, 'nocache_headers']);
    }
    /**
     * Ghi đè lại routes mặt định của WPRESTAPI
     * @param string $base
     * @param array $route
     * @since 0.1
     */
    protected function router($base, $routes) {
        $router = register_rest_route($this->namespace, $base, $routes);
        return $router;
    }
    /**
     * nhận tham số đầu vào và trả json về cho Client
     * @since 1.0
     * @param object $data
     * @param string $code
     */
    protected function Resouce($data, $code=null) {
        return new \WP_REST_Response( $data, ( isset($code) ) ? $code : $code = 200 );
    }
    /**
     * Thông báo lổi cho client nếu có 
     * @param string $code {filter code}
     * @param string $content {nội dung báo lổi}
     * @param string $status {trang thái trả về}
     */
    protected function Error($code, $content, $status) {
        return new \WP_Error($code, __($content, 'inanh86-api'), ['status'=>$status]);
    }
    /**
     * Không lưu cache
     */
    public function nocache_headers() {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        return array(
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, some-custom-thing',
            'Pragma'        => 'no-cache',
            'Expires'       => date('Y-m-d H:i:s G\M\T')
        );
    }
}