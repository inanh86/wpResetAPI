<?php namespace inanh86\DB;

use inanh86\Controller\Baoloi;

class DB {
    
    public $db;
    public $prefix;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->prefix = $wpdb->prefix;
    }
}