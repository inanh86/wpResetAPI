<?php namespace inanh86\Routes;

use inanh86\Controller\Servers;
use inanh86\Modules\Auth\Accounts as Client;

if(!defined('ABSPATH')) {
    exit;
}

class Taikhoan extends Servers {

    protected $base = '/tai-khoan';
    
    /**
     * Đăng ký endpoint /tai-khoan
     */
    public function dangky_route() {
        // Đăng nhập vào hệ thống
        $router = $this->router($this->base.'/dang-nhap', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'login'],
                'permission_callback' => '__return_true'
            ]
        ]);
        // Thêm tài khoản vào hệ thống
        $router = $this->router($this->base.'/dang-ky', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'dang_ky'],
                'permission_callback' => '__return_true'
            ]
        ]);
        // Gọi danh sách tài khoản
        $router = $this->router($this->base.'/', [
            [
                'methods'             => $this->GET,
                'callback'            => [$this, 'danh_sach'],
                'permission_callback' => [$this, 'permission_read']
            ]
        ]);
        return $router;
    }
    /**
     * Gọi danh sách user name
     */
    public function danh_sach($requset) {
        try {
           return $this->Resouce(Client::goi_danh_sach($requset['start'], $requset['limit']));
        } catch (\Exception $e) {
            return new $this->Baoloi($e->getCode(), $e->getMessage());
        }
    }
    /**
     * Đang nhập hệ thống
     */
    public function login($requset) {
        try {
            return $this->Resouce(Client::danh_nhap($requset['taikhoan'], $requset['matkhau']));
        } catch(\Exception $e) {
            return $this->Baoloi($e->getCode(), $e->getMessage());
        }
    }
    /**
     * Đăng ký thành viên
     */
    public function dang_ky($requset) {
         try {
            return $this->Resouce(Client::dang_ky($requset['taikhoan'], $requset['matkhau']));
        } catch(\Exception $e) {
            return $this->Baoloi($e->getCode(), $e->getMessage());
        }
    }
}