<?php namespace inanh86\Api;

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
        $this->register_resources();
    }
    /**
     * Nhập toàn bộ các class 
     * @since 0.1
     */
    protected function include() {
        
        include_once( dirname( __FILE__ ) ) . '/class-Permission.php';
        include_once( dirname( __FILE__ ) ) . '/class-Resouce.php';
        //
        include_once( dirname( __FILE__ ) ) . '/class-Khachhang.php';
        include_once( dirname( __FILE__ ) ) . '/class-Sanpham.php';
        include_once( dirname( __FILE__ ) ) . '/class-Danhmuc.php';

    }
    /**
     * Gọi tất cả các lớp đã đc đăng ký
     * @since 0.1
     */
    protected function register_resources() {
        $api_classes = apply_filters( 'api_new_class',
			array(
				'\inanh86\Api\SanPham',
                '\inanh86\Api\Khachhang',
                '\inanh86\Api\Danhmuc'
			)
		);
		foreach ( $api_classes as $api_class ) {
			$this->$api_class = new $api_class($this->namespace);
		}
    }
}