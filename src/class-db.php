<?php namespace inanh86\DB;

use \Exception as Baoloi; 

/**
 * Custom lại cách insert Mysql Của Wordpress
 * @see link
 * @version 1.0
 */
class DB {
    
    const INSERTFAIL = 400; // mã lổi không insert được Database
    
    /**
     * Insert Database
     * @use $wpdb
     * @see link https://developer.wordpress.org/reference/classes/wpdb/
     * @version 4+
     */
    public static function insert($query) {
        global $wpdb;
        $insert = $wpdb->query($wpdb->prepare($query));
        if($insert) {
            return $insert;
        } else {
            throw new Baoloi('Lổi không insert được Database', self::INSERTFAIL);
        }
    }
}