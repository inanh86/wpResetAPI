<?php namespace inanh86\Api;
use \inanh86\Api\Permission; 

if(!defined('ABSPATH')) {
    exit;
}
class KhachHang extends \inanh86\Api\Resouce {

    protected $base = '/khach-hang';
    protected $code_error = 'api_customer_error';
    protected $key_encode = 'api_client_login';

    public function dangky_route() {

        // đăng nhập khách hàng
        $router = $this->router($this->base.'/dang-nhap', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'dangnhap'],
                'permission_callback' => '__return_true'
            ]
        ]);
        return $router;
    }
    /**
     * Trả ra kết quả đăng nhập cho khách hàng
     * @param string $request['username']
     * @param string $request['password']
     * @since 0.1
     */
    public function dangnhap($request) {

        if(!empty($request['username']) && !empty($request['password'])) {
            $khach_hang = $this->kiem_tra($request['username'], $request['password']);
        } else {
            $khach_hang = $this->Error($this->code_error, 'Lổi không tìm thấy tên đăng nhập hoặc mật khẩu của bạn', $this->khongduoctruycap);
        }
        return $khach_hang;

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
                $khach_hang = [
                    'login_status'  => true,
                    'customer'      => Permission::quyentruycap($user->ID),
                    'token'         => Permission::encodeToken([
                        'cap'       => $this->lay_thong_tin($user->ID, 'api_capabilities'),
                        'permission'=> Permission::quyentruycap($user->ID),
                    ], $this->key_encode),
                ];
                return $this->Resouce($khach_hang);
            } else {
                $khach_hang = [
                    'login_status' => false,
                    'customer' => null,
                    'content' => 'Tài khoản hoặc mật khẩu của bạn không đúng rồi!'
                ];
                return $this->Resouce($khach_hang, $this->khongduoctruycap);
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
}