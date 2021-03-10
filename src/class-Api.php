<?php namespace inanh86\Controller;

if (!defined('ABSPATH')) {
    exit;
}

class Root {

    // namespace endpoint
    public $namespace = 'vendor/v1'; 

    /**
     * Run chương trình
     * @since 0.1
     */
    public function init() {
        $this->include();
        $this->handle_v1_rest_api_request();
        $this->register_resources();
    }
    /**
     * Nhập toàn bộ các module 
     * @since 0.1
     */
    protected function include() {
        
        // Resouce API
        include_once dirname( __FILE__ )  . '/class-servers.php';
       
        // Auth User
        include_once dirname( __FILE__ )  . '/modules/auth/class-token.php';
        
        // import all modules
        include_once dirname( __FILE__ )  . '/modules/auth/class-account.php';
    }
    /**
     * Gọi danh sách Endpoint
     * @see link 
     * @version 1
     */
    protected function handle_v1_rest_api_request() {
        // All API Requset Endpoint
        include_once dirname( __FILE__ )  . '/routes/class-taikhoan.php';
        include_once dirname( __FILE__ )  . '/routes/class-danhmuc.php'; 
    }
    /**
     * Gọi tất cả các lớp đã đc đăng ký
     * @since 0.1
     */
    protected function register_resources() {
        $api_classes = apply_filters( 'api_new_class',
			array(
                '\inanh86\Routes\Taikhoan',
                '\inanh86\Routes\Danhmuc'
			)
		);
		foreach ( $api_classes as $api_class ) {
			$this->$api_class = new $api_class($this->namespace);
		}
    }
}