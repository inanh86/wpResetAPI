<?php namespace inanh86\Api;

use \inanh86\Api\Permission; 

if(!defined('ABSPATH')) {
    exit;
}
/**
 * Lấy sản phẩm từ API 
 * @since 0.1
 */
class Danhmuc extends \inanh86\Api\Resouce {

    protected $base = '/danh-muc';
    protected $ErrorCode = 'api_danhmuc_error';

    public function dangky_route() {

         // gọi danh sách sản phẩm
        $router = $this->router($this->base.'/', [
            [
                'methods'             => $this->GET,
                'callback'            => [$this, 'danh_muc'],
                'permission_callback' => [$this, 'kiem_tra_token']
            ]
        ]);
        // gọi danh sách sản phẩm
        $router = $this->router($this->base.'/add', [
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'them_danh_muc'],
                'permission_callback' => [$this, 'kiem_tra_token']
            ]
        ]);
        return $router;
    }
    /**
     * Gọi danh sách chuyên mục
     * @since 1.0
     */
    public function danh_muc($request) {
        $single_cate = $this->goidanhsach($request);
        return $this->Resouce($single_cate);
    }
    /**
     * Thêm danh mục mới 
     * @since 1.0
     */
    public function them_danh_muc($request) {
        try {
            $danhmuc = sanitize_text_field($request['danhmuc']);
            $slug = sanitize_text_field( $request['slug'] );
            $motadanhmuc = sanitize_textarea_field( $request['mota'] );
            if ( isset($danhmuc) ) {
            $cate = $this->addnew($danhmuc, $slug, $motadanhmuc);
            if ($cate === true) {
                return $this->Resouce($cate);
            } else {
                throw new \Exception("Có lổi trong việc thêm danh mục sản phẩm");
            }
            } else {
                throw new \Exception("Lổi biến danh mục không tồn tại");
            }
        } catch (\Exception $e) {
            return $this->Error($this->ErrorCode, $e->getMessage(), '400');
        }
    }
    /**
     * Thêm mới danh mục vào database
     * @param string $name
     * @param string $desc
     * @since 1.0
     */
    private function addnew($name, $slug, $mota) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $Query = $wpdb->query( $wpdb->prepare(
            "INSERT INTO {$prefix}terms (name, slug) VALUES (%s, %s)", array($name, $slug)
        ));
        $Query = $this->addmeta_danhmuc($wpdb->insert_id, 'danhmuc_mota', $mota);
        if ($Query) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Thêm mô tả danh mục
     * @param string $id
     * @param stirng $key
     * @param string @value
     */
    private function addmeta_danhmuc($id, $key, $value) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $Query = $wpdb->query($wpdb->prepare(
            "INSERT INTO {$prefix}termmeta (term_id, meta_key, meta_value) VALUES (%d, %s, %s)", [$id, $key, $value]
        ));
        if ($Query) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Gọi danh sách sản phẩm
     */
    private function goidanhsach($data) {
        $single = $data['id'];
        $paged = $data['paged'];
        if ( isset($single) ) {
            
        } else {
            $Query = $this->db->get_results("SELECT * FROM {$this->db->prefix}terms ORDER BY term_id DESC");
            $data = [];
            foreach($Query as $q) {
                $qe = [];
                $qe['id'] = $q->term_id;
                $qe['tendanhmuc'] = $q->name;
                $qe['mota'] = $this->goimeta($q->term_id, 'danhmuc_mota');
                $data[] = $qe;
            }
            return $data;
        }
    }
    private function goimeta($id, $key) {
        $Query = $this->db->get_results($this->db->prepare(
            "SELECT * FROM {$this->db->prefix}termmeta WHERE term_id= %d", $id
        ));
        if ($Query) {
            foreach($Query as $q) {
                if ( $key === $q->meta_key) {
                    $query = $q->meta_value;
                } else {
                    $query = 'yêu cầu của bạn không tồn tại';
                }
                return $query;
            }
        } else {
            return 'Không có mô tả cho chuyên mục này';
        }
    }
}