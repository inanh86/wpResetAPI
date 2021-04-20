<?php namespace inanh86\DashBroad;

if( !defined('ABSPATH')) {
    exit;
}

class Admin {
    public function init() {
        $this->include();
        $this->register_class();
    }
    public function include() {
        //include_once dirname( __FILE__ )  . '/class-menu.php';
        include_once dirname( __FILE__ )  . '/class-panel.php';
    }
    public function register_class() {
        $_classes = [
            '\inanh86\DashBroad\Panel',
        ];
		foreach ( $_classes as $_class ) {
			$this->$_class = new $_class();
		}
    }
}