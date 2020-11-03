<?php namespace inanh86\Api;

use \inanh86\Api\Permission;
use WP_REST_Server;
use WP_REST_Response;

if(!defined('ABSPATH')) {
    exit;
}

class Resouce {

    public $namespace = 'vendor/v1';

    public $GET = WP_REST_Server::READABLE;
    
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'dangky_route']);
    }
    /**
     * Ghi đè lại routes mặt định của WPRESTAPI
     * @param string $base
     * @param array $route
     * @since 0.1
     */
    protected function router($base, $routes) {
        $router = register_rest_route($this->namespace, $base, $routes);
        return $router;
    }
    public function kientraquyentruycap($request) {
        $khach_hang = Permission::init($request);
        return $khach_hang;
    }
    protected function Resouce($data) {
        $resouce = new WP_REST_Response( $data, 200 );
        return $resouce;
    }
    protected function Error($data) {

    }
}