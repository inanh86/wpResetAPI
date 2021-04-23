<?php namespace inanh86\Routes;

use \inanh86\API\Servers;
use inanh86\Modules\Auth\Khachhang as Client;

if(!defined('ABSPATH')) {
    exit;
}

class Khachhang extends Servers {

    protected $base = '/khach-hang';

    public function dangky_route() {

        // Đăng ký khách hàng mới
        $router = $this->router($this->base.'/dang-ky', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'dang_ky'],
                'permission_callback' => '__return_true'
            ]
        ]);
        // Đang nhập 
        $router = $this->router($this->base.'/dang-nhap', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'dang_nhap'],
                'permission_callback' => '__return_true'
            ]
        ]);
        return $router;
    }
    /**
     * Tạo khách hàng mới
     */
    public function dang_ky($reuqest) {
        try {
            return $this->Resouce(Client::dang_ky([
                'id' => $reuqest['id'], 
                'pass' => $reuqest['pass'], 
                'name' => $reuqest['name'],
                'diachi' => $reuqest['diachi'],
            ]));
        } catch (\Exception $e) {
            return $this->Baoloi($e->getCode(), $e->getMessage());
        }
    }
    /**
     * Đăng nhập khách hàng
     */
    public function dang_nhap($reuqest) {
        try {
            $client = Client::dang_nhap($reuqest['id'],$reuqest['pass']);
            return $this->Resouce($client);
        } catch (\Exception $e) {
            return $this->Baoloi($e->getCode(), $e->getMessage());
        }
    }
}