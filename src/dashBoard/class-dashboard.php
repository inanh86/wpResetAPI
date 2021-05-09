<?php namespace inanh86\DashBoard;

if( !defined('ABSPATH')) {
    exit;
}

class Admin {

    public $menu = [];

    public function init() {
        $this->include();
        $this->register_class();
        $this->admin_hook_init();
    }
    public function include() {
        include_once dirname( __FILE__ )  . '/class-menu.php';
        include_once dirname( __FILE__ )  . '/class-panel.php';
        include_once dirname( __FILE__ )  . '/class-sanpham.php';
    }
    /**
     * Danh sách hook admin chạy ngay khi đăng nhập thành công
     */
    public function admin_hook_init() {
        add_action( 'wp_before_admin_bar_render', [$this, 'remove_logo_wp_admin'], 0 );
        add_filter( 'admin_footer_text', '__return_empty_string', 11 );
        add_filter( 'update_footer',    [$this, 'addVersion'], 11 );
    }
    public function remove_logo_wp_admin() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu( 'wp-logo' );
    }
    public function addVersion($text) {
        $text = 'Phiên bản hiện tại: ' . WpResetAPI()->version;
        return $text;
    }
    /**
     * Gọi các lớp 
     */
    protected function register_class() {
        $_classes = [
            '\inanh86\DashBoard\Panel',
            '\inanh86\DashBoard\Menu\Sanpham',
        ];
		foreach ( $_classes as $_class ) {
			$this->$_class = new $_class();
		}
    }
}