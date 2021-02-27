<?php namespace inanh86\Controller;
if(!defined('ABSPATH')) {
    exit;
}

use \WP_REST_Server as Maychu;
use inanh86\Api\Auth\Permission as Token;

class Servers {
    
    public $namespace = null;
    protected $db = null;
    protected $key = Token::KEY_ACCOUNT;

    /**
     * Method Đẩy lên từ Client
     * @param string 
     */
    protected $GET = Maychu::READABLE;
    protected $POST = Maychu::CREATABLE;
    protected $CREATE = Maychu::EDITABLE;
    protected $DELETE = Maychu::DELETABLE;

    protected $loimaychu = 500;
    protected $khongduoctruycap = 401;
    protected $loikhongtimthay = 404;
    
    public function __construct($namespace)
    {   
        global $wpdb;
        $this->db = $wpdb;
        $this->namespace = $namespace;
        $this->hook_init();
    }
    public function hook_init() {
        remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
        add_filter( 'rest_pre_serve_request', [$this, 'rest_headers'], 15);
        add_filter('nocache_headers', [$this, 'nocache_headers']);
        add_filter('rest_index', [$this, 'RestIndex']);
        add_action('rest_api_init', [$this, 'dangky_route']);
        $this->setDefaultRestAPI();
    }
    /**
     * Loại bỏ wp-json mặc định của Wp
     * @link https://developer.wordpress.org/reference/hooks/rest_index/
     * @since 1.0
     */
    private function setDefaultRestAPI() {
        add_filter('rest_endpoints', function($endpoints) {
            unset( $endpoints['/'.$this->namespace] );
            return $endpoints;
        });
    }
    /**
     * Custom Index Rest Api
     * @link https://developer.wordpress.org/reference/hooks/rest_endpoints/
     */
    public function RestIndex() {
        return $this->Resouce([
            'HomePage' => 'https://www.inanh86.com',
            'Author' => 'inảnh86.com',
            'LinkDocs' => 'https://www.inanh86.com/giup-do',
            'inanh86-RESTAPI' => '0.1',
            'WPCore' => 'Wordpress 3.7.1',
        ]);
    }
    /**
     * Ghi đè lại routes mặt định của WPRESTAPI
     * @param string $base
     * @param array $route
     * @since 0.1
     */
    protected function router($base=null, $routes) {
        $router = register_rest_route( $this->namespace, $base, $routes );
        return $router;
    }
    /**
     * kiểm tra quyền đọc của client
     * @param object $request
     * @since 1.0
     */
    public function permission_read($request) {
        $read = Token::Read($request['oauth_signature_token'], $this->key);
        return $read;
    }
    /**
     * Kiểm tra quyền ghi/xóa của client
     * @since 1.0
     */
    public function permission_write($request) {
        $write = Token::Write($request['oauth_signature_token'], $this->key);
        return $write;
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
    protected function Error($code, $content) {
        return new \WP_Error($code, __($content, 'inanh86-api'), ['status'=> 200]);
    }
    /**
     * Không lưu cache
     * @since 0.1
     */
    public function nocache_headers() {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        return [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, some-custom-thing',
            'Pragma'        => 'no-cache',
            'Expires'       => date('Y-m-d H:i:s G\M\T')
        ];
    }
    public function rest_headers($value) {
        header( 'Access-Control-Expose-Headers: X-Paged-Total, X-Paged-Current' );
		return $value;
    }
    /**
     * Đưa tổng số trang ra header khi client gọi
     * @param string $dbname
     * @param int $start
     * @param int $limit
     * @since 0.1
     */
    protected function paged_total_header($dbname, $start, $limit) {
        if (isset($start) && isset($limit)) {
            if (!empty($start)) {
                $start = $start !== 0 ? $start : 1;
                $paged = $limit * ($start - 1);
                $total = $this->db->get_var("SELECT COUNT(*) FROM {$this->db->prefix}{$dbname}");
                $page_total = ceil($total / $limit);
                header('X-Paged-Current:' . $start);
                header('X-Paged-Total:' . $page_total);
                return $paged;
            } else {
                throw new \Exception("Thiếu start trong yêu cầu lấy danh sách chuyên mục của bạn", 1401);
            }
        } else {
            throw new \Exception("Start và Limit không được bỏ trống", 1401);
        }
    }
    protected function resouceCode($code, $content) {
        $resouce = [
            'code' => $code,
            'content' => $content
        ];
        return $resouce;
    }
}