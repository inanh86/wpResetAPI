<?php namespace inanh86\Api;
if(!defined('ABSPATH')) {
    exit;
}
class Resouce {
    public function __construct()
    {
        register_rest_route( $namespace:string, $route:string, $args:array, $override:boolean )
    }
}