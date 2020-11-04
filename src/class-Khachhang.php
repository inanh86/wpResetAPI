<?php namespace inanh86\Api;

if(!defined('ABSPATH')) {
    exit;
}
class KhachHang extends \inanh86\Api\Resouce {

    protected $base = '/khach-hang';

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
        $khachhang = $this->kiem_tra($request['username'], $request['password']);
        return $khachhang;
    }
    /**
     * Kiểm tra đăng nhập
     * nhận 2 giá trị $user,$pass
     * @param string $user
     * @param string $pass
     * @since 0.1
     */
    protected function kiem_tra($user, $pass) {

        try {
            $user = get_user_by( 'login', $user );
            if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID ) ) {
                $khach_hang = [
                    'tendangnhap' => $user->data->display_name
                ];
            } else {
                $khach_hang = [
                    'tendangnhap' => false,
                ];
            }
            return $this->Resouce($khach_hang);

        } catch(\Exception $e) {
            return $this->Error('api_customer_error', $e->getMessage(), 500);
        }

    }
}