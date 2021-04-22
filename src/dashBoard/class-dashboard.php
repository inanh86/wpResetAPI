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
        include_once dirname( __FILE__ )  . '/class-panel.php';
        include_once dirname( __FILE__ )  . '/class-sanpham.php';
    }
    /**
     * Danh sách hook admin chạy ngay khi đăng nhập thành công
     */
    public function admin_hook_init() {
        add_action('admin_menu', [ $this, 'add_menu'] );
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
        ];
		foreach ( $_classes as $_class ) {
			$this->$_class = new $_class();
		}
    }
    /**
     * Danh sách menu
     * @see https://developer.wordpress.org/reference/functions/add_menu_page/
     */
    public function listMenu() {
        
        $this->menu = [
            [
                'title' => 'Danh sách sản phẩm',
                'nameMenu' => 'Sản phẩm',
                'cap' => 'manage_options',
                'slug' => 'san-pham',
                'page' => '',
                'icon' => 'dashicons-screenoptions',
                'position' => 3,
                'subMenu' => [
                    'subTitle' => 'Quản lý tất cả sản phẩm',
                    'nameSubmenu' => 'Tất cả sản phẩm',
                    'cap' => 'manage_options',
                    'slug' => 'san-pham',
                    'page' => [$this, 'sanpham']
                ]
            ],
            [
                'title' => 'Danh sách đơn hàng',
                'nameMenu' => 'Đơn hàng',
                'cap' => 'manage_options',
                'slug' => 'khohang',
                'page' => 'my_custom_menu_page',
                'icon' => 'dashicons-cart',
                'position' => 2,
            ]
        ];
        return $this->menu;
    }
    public function add_menu() {
        foreach( $this->listMenu() as $menu ) {
            add_menu_page($menu['title'], $menu['nameMenu'],$menu['cap'],$menu['slug'],$menu['page'],$menu['icon'],$menu['position']);
            if( $menu['subMenu'] !== NULL ) {
                add_submenu_page($menu['slug'], $menu['subMenu']['subTitle'], $menu['subMenu']['nameSubmenu'], $menu['subMenu']['cap'], $menu['subMenu']['slug'], $menu['subMenu']['page']);
            }
        }
    }
    public function sanpham() {
        $this->sanpham = new \inanh86\DashBoard\Sanpham();
        $this->sanpham->prepare_items();
        $this->sanpham->display();
    }
}