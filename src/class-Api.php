<?php namespace inanh86\Api;

if (!defined('ABSPATH')) {
    exit;
}
class Root {
    
    public function init() {
        $this->include();
        $this->register_resources();
    }
    protected function include() {

        include_once( dirname( __FILE__ ) ) . '/class-Permission.php';
        include_once( dirname( __FILE__ ) ) . '/class-Resouce.php';
        include_once( dirname( __FILE__ ) ) . '/class-Khachhang.php';
        include_once( dirname( __FILE__ ) ) . '/class-Sanpham.php';

    }
    /**
     * Gọi tất cả các lớp đã đc đăng ký
     * @since 0.1
     */
    protected function register_resources() {
        $api_classes = apply_filters( 'api_new_class',
			array(
				'\inanh86\Api\SanPham',
				'\inanh86\Api\Khachhang'
			)
		);
		foreach ( $api_classes as $api_class ) {
			$this->$api_class = new $api_class();
		}
    }
}