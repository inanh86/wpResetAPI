<?php namespace inanh86\DashBoard\Menu;

defined('ABSPATH') || exit;

class Menu {

    protected $role = '';

    public function __construct() {
        add_action( 'admin_menu', [$this, 'add_menu'] );
    }
    /**
     * Khởi tạo ménu
     */
    public function data_menu($data=[],$sub=null) {
        add_menu_page($data['title'], $data['nameMenu'],$data['cap'],$data['slug'],$data['page'],$data['icon'],$data['position']);
        if($sub===null) {
            return;
        }
        foreach($data['submenu'] as $s) {
            add_submenu_page($s['slug'], $s['subTitle'], $s['nameSubmenu'], $s['cap'], $s['slug'], $s['page'], $s['position']);
        }
    }
    /**
     * Kiểm tra quyền truy cập Menu
     */
    public function is_cap() {
        $auth = new \inanh86\System\Auth();
    }
}