<?php namespace inanh86\DashBroad;

if(!defined('ABSPATH')) {
    exit;
}

/**
 * tùy chỉnh lại dashbroad
 * @see https://developer.wordpress.org/apis/handbook/dashboard-widgets/
 */
class Panel {
    public function __construct() {
        add_action( 'wp_dashboard_setup', [$this, 'init_hook'] );
    }   
    public function init_hook() {
        $this->remove_hook();
        $this->add_hook();
    }
    /**
     * Xóa toàn bộ witget mặc định của wordpress
     * @see https://developer.wordpress.org/apis/handbook/dashboard-widgets/#removing-default-dashboard-widgets
     */
    protected function remove_hook() {
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'health_check_status', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
    }
    protected function add_hook() {

    }
}
return new Panel;