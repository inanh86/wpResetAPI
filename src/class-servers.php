<?php namespace inanh86\API;

use \WP_REST_Server as Maychu;
use inanh86\Modules\Auth\Token;
use inanh86\Modules\Auth\Accounts as Taikhoan;

if(!defined('ABSPATH')) {
    exit;
}
/**
 * Cấu hình lại Server Request Của Wordpress
 * @see link 
 * @version 1.0
 */
class Servers {
    
    public $namespace = null;
    protected $db = null;

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
     */
    protected function router($base=null, $routes) {
        register_rest_route( $this->namespace, $base, $routes );
    }
    /**
     * kiểm tra quyền đọc của client
     * @param object $request
     */
    public function permission_read($request) {
        try {
            return Token::Read($request['oauth_signature_token'], Taikhoan::KEY_ACCOUNT_TOKEN);
        } catch (\Throwable $th) {
            return $this->Baoloi(Token::TOKEN_CODE, $th->getMessage());
        }
    }
    /**
     * Kiểm tra quyền ghi/xóa của client
     */
    public function permission_write($request) {
        try {
            return Token::Write($request['oauth_signature_token'], Taikhoan::KEY_ACCOUNT_TOKEN);
        } catch (\Throwable $th) {
            return $this->Baoloi(Token::TOKEN_CODE, $th->getMessage() );
        }
    }
    /**
     * Nhận tham số đầu vào và trả json về cho Client
     * @param object $data
     * @param string $code
     */
    protected function Resouce($data, $code=null) {
        return new \WP_REST_Response( $data, ( isset($code) ) ? $code : $code = 200 );
    }
    /**
     * Nhận lổi vào trả ra về cho client
     * @param string $messege thông tin lổi báo
     * @param int $code mã lổi
     */
    protected function Baoloi($code=null,$messege) {
        return new \WP_Error($code, $messege, ['status' => 200]);
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