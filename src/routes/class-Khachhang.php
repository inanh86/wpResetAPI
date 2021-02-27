<?php namespace inanh86\Routes;

if(!defined('ABSPATH')) {
    exit;
}

class KhachHang extends \inanh86\Api\Resouce {

    protected $base = '/khach-hang';
    protected $code_error = 'api_customer_error';

    public function dangky_route() {

        // Danh Sách Khách Hàng
        $router = $this->router($this->base.'/', [
            [
                'methods'             => $this->GET,
                'callback'            => [$this, 'danh_sach_khach_hang'],
                'permission_callback' => [$this, 'kiem_tra_token']
            ]
        ]);
        // đăng nhập khách hàng
        $router = $this->router($this->base.'/dang-nhap', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'dang_nhap'],
                'permission_callback' => '__return_true'
            ]
        ]);
        // Sửa thông tin profile khách hàng
        $router = $this->router($this->base.'/edit', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'customer_edit'],
                'permission_callback' => [$this, 'kiem_tra_token']
            ]
        ]);
        return $router;
    }
    /**
     * Lấy danh sách khách hàng
     * @since 0.1
     */
    public function danh_sach_khach_hang($request) {
        var_dump($request);
    }
    /**
     * Trả ra kết quả đăng nhập cho khách hàng
     * @param string $request['username']
     * @param string $request['password']
     * @since 0.1
     */
    public function dang_nhap($request) {
        if(!empty($request['username']) && !empty($request['password'])) {
            $Client = new \inanh86\Api\auth\Dangnhap($request['username'],$request['password']));
            return $this->Resouce([
                'Client' => $Client->goiProfile(),
                'AppMap' => $Client->goiMap(),
            ]);
        } else {
            $khach_hang = $this->Error($this->code_error, 'không tìm thấy tên đăng nhập hoặc mật khẩu của bạn', $this->khongduoctruycap);
        }
        return $khach_hang;
    }
    public function customer_edit($request) {
        
        return $this->Resouce($request);
    }
    /**
     * Kiểm tra đăng nhập
     * nhận 2 giá trị $user,$pass
     * @param string $user
     * @param string $pass
     * @since 0.1
     */
    private function kiem_tra($user, $pass) {
        try {
            $user = get_user_by( 'login', $user );
            if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID ) ) {
                return $this->Resouce([
                    'id_khach_hang'          => $user->ID,
                    'ten_khach_hang'         => $user->data->display_name,
                    'trang_thai'             => true,
                    'oauth_signature_token'  => $this->encode_token(
                        [
                            'id'    => $user->ID,
                            'cap'   => $this->lay_thong_tin($user->ID, 'api_capabilities'),
                            'permission' => Permission::setQuyentruycap($user->ID)
                        ]
                    ),
                    'App_Map' => new \inanh86\AppMenu\Menu($user->ID)
                ]);
            } else {
                return $this->Resouce([
                    'trang_thai' => false,
                    'ten_khach_hang' => 'khách hàng',
                ], $this->khongduoctruycap);
            }
        } catch(\Exception $e) {
            return $this->Error($this->code_error, $e->getMessage(), $this->loimaychu);
        }
    }
    /**
     * Lấy thông tin khách hàng
     * @param string $id 
     * @param string $key
     */
    private function lay_thong_tin($id, $key) {
        $thong_tin = get_user_meta($id, $key, true);
        return $thong_tin;
    }
    /**
     * Xử lý lấy danh sách khách hàng
     * @param string 
     */
    private function danh_sach() {

    }
}