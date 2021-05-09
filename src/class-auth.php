<?php namespace inanh86\System;

use \Firebase\JWT\JWT as TOKEN;

defined('ABSPATH') || exit;

/**
 * Thiết lập Auth
 * @see https://dev.inanh86.com/docs/auth
 */
class Auth {

    protected $token = "";
    protected $key = "";
    protected $reuslt;

    public function check_token($token=NULL,$key=NULL) {

    }
    public function is_token() {

    }
    /**
     * Kiểm tra role của Client đang login
     * @return $role
     */
    public static function get_role() {
        $role = wp_get_current_user();
        var_dump($role);
    }
}