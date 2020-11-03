<?php 
namespace inanh86\Api;

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
        include_once( dirname( __FILE__ ) ). '/class-Sanpham.php';
    }
    protected function register_resources() {
        $api_classes = apply_filters( 'api_classes',
			array(
				'\inanh86\Api\SanPham',
				#'\Khachhang'
			)
		);
		foreach ( $api_classes as $api_class ) {
			$this->$api_class = new $api_class();
		}
    }
}