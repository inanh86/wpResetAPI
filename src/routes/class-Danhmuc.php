<?php namespace inanh86\Routes;

if(!defined('ABSPATH')) {
    exit;
}
/**
 * Lấy sản phẩm từ API 
 * @since 0.1
 */
class Danhmuc extends \inanh86\API\Servers {

    protected $base = '/danh-muc';
    protected $ErrorCode = 'api_danhmuc_error';

    public function dangky_route() {

        // gọi danh sách
        $router = $this->router($this->base.'/', [
            [
                'methods'             => $this->GET,
                'callback'            => [$this, 'danhmuc'],
                'permission_callback' => [$this, 'permission_read'],
            ],
            [
                'methods'             => $this->POST,
                'callback'            => [$this, 'danhmuc_add'],
                'permission_callback' => [$this, 'permission_write'],
            ]
        ]);
        // gọi theo id
        $router = $this->router($this->base.'/(?P<id>[\d]+)', [
            [
                'methods'             => $this->GET,
                'callback'            => [$this, 'danhmuc'],
                'permission_callback' => [$this, 'permission_read']
            ],
            [
                'methods'             => $this->CREATE,
                'callback'            => [$this, 'danhmuc_edit'],
                'permission_callback' => [$this, 'permission_write']
            ],
            [
                'methods'             => $this->DELETE,
                'callback'            => [$this, 'danhmuc_delete'],
                'permission_callback' => [$this, 'permission_write']
            ]
        ]);

        return $router;
    }
    /**
     * Gọi danh sách chuyên mục
     * @since 1.0
     */
    public function danhmuc($request) {
        try {
            $cate = $this->goi($request);
            if ($cate) {
                return $this->Resouce($cate);
            } else {
                throw new \Exception("Có lổi trong việc truy cập db danh mục sản phẩm.", 1400);
            }
        } catch(\Exception $e) {
            return $this->Error($e->getCode(), $e->getMessage());
        }
    }
    /**
     * Thêm danh mục mới theo yêu cầu của client
     * @param object $request
     * @since 1.0
     */
    public function danhmuc_add($request) {
        try {
            $danhmuc = sanitize_text_field($request['danhmuc']);
            $slug = sanitize_text_field($request['slug']);
            $motadanhmuc = sanitize_textarea_field( $request['mota'] );
            if ( isset($danhmuc) ) {
                $cate = $this->addnew($danhmuc, $slug, $motadanhmuc);
                if ($cate['code'] === 1400) {
                    return $this->Resouce($cate);
                } else {
                    throw new \Exception("Có lổi trong việc thêm danh mục.");
                }
            } else {
                throw new \Exception("Lổi biến danh mục không tồn tại.");
            }
        } catch (\Exception $e) {
            return $this->Error($this->ErrorCode, $e->getMessage());
        }
    }
    /**
     * Sửa danh mục sử dùng ID
     * @since 0.1
     */
    public function danhmuc_edit($request) {
        try {
            if (isset($request['id'])) {
                $cate = $this->edit($request['id'], $request);
                return $this->Resouce($cate);
            } else {
                throw new \Exception("Danh mục không tồn tại!", 1402);
            }
        } catch(\Exception $e) {
            return $this->Error($this->ErrorCode, $e->getMessage());
        }
    }
    /**
     * Xóa cái gì đó
     * @since 1.0
     */
    public function danhmuc_delete($request) {
        try {
            if (isset($request['id'])) {
                return $this->Resouce($this->xoa($request['id']));
            } else {
                throw new \Exception('Lổi không tìm thấy id bạn yêu cầu');
            }
        } catch (\Exception $e) {
            $this->Error($this->ErrorCode, $e->getMessage(), 200);
        }
    }
    ////////////////////////////////////////////////
    /**
     * Thêm mới danh mục vào database
     * @param string $name
     * @param string $desc
     * @since 1.0
     */
    private function addnew($name, $slug, $mota) {
        
        $add = $this->db->query( $this->db->prepare(
            "INSERT INTO {$this->db->prefix}terms (name, slug) VALUES (%s, %s)", array($name, $slug)
        ));
        $add = $this->addmeta($this->db->insert_id, 'danhmuc_mota', $mota);
        if ($add) {
            return $this->resouceCode(1400,'Thêm danh mục sản phẩm thành công.');
        } else {
            return $this->resouceCode(1401,'Thêm danh mục thất bại vui lòng kiểm tra lại.');;
        }
    }
    /**
     * Thêm mô tả danh mục
     * @param string $id
     * @param stirng $key
     * @param string @value
     */
    private function addmeta($id, $key, $value) {
        $addmeta = $this->db->query($this->db->prepare(
            "INSERT INTO {$this->db->prefix}termmeta (term_id, meta_key, meta_value) VALUES (%d, %s, %s)", 
            [$id, $key, $value]
        ));
        if ($addmeta) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Gọi danh sách
     * @param object $data
     * @since 1.0
     */
    private function goi($data) {
        $id = sanitize_text_field($data['id']);
        // Gọi danh sách theo id
        if ( !empty($id) ) {
            $goimotdanhmuc = $this->db->get_row($this->db->prepare(
                "SELECT * FROM {$this->db->prefix}terms WHERE term_id = %d", $id
            ));
            if ($goimotdanhmuc) {
                return [
                    'id' => $goimotdanhmuc->term_id,
                    'tendanhmuc' => $goimotdanhmuc->name,
                    'slug' => $goimotdanhmuc->slug,
                    'mota' => $this->goimeta($goimotdanhmuc->term_id, 'danhmuc_mota'),
                ];
            } else {
                return false;
            }
        } else {
            // gọi tất cả 
            $limit = sanitize_text_field($data['limit']);
            // Gọi số trang 
            $paged = $this->paged_total_header('terms',sanitize_text_field($data['start']), $limit);
            // Chạy Query lấy DB
            $get = $this->db->get_results( $this->db->prepare(
                "SELECT * FROM {$this->db->prefix}terms ORDER BY term_id DESC LIMIT %d , %d ",
               [$paged, $limit]
            ));
            if ($get) {
                $data = [];
                foreach($get as $q) {
                    $qe = [];
                    $qe['id'] = $q->term_id;
                    $qe['tendanhmuc'] = $q->name;
                    $qe['mota'] = $this->goimeta($q->term_id, 'danhmuc_mota');
                    $qe['slug'] = $q->slug;
                    $data[] = $qe;
                }
                return $data;
            } else {
                throw new \Exception("Lổi không tìm thấy danh mục bạn yêu cầu", 1401);
            }
        }
    }
    /**
     * Gọi meta
     * @param int $id
     * @param string $key
     * @since 1.0
     */
    private function goimeta($id, $key) {
        $goimeta = $this->db->get_results($this->db->prepare(
            "SELECT * FROM {$this->db->prefix}termmeta WHERE term_id= %d", $id
        ));
        if ($goimeta) {
            foreach($goimeta as $q) {
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
    /**
     * Xóa 1 danh mục bất kỳ
     * @param string $id
     */
    private function xoa($id) {
        $delete = $this->db->query($this->db->prepare(
            "DELETE FROM {$this->db->prefix}terms WHERE term_id = %d", $id
        ));
        if ($delete) {
            $delete = $this->db->query($this->db->prepare(
                "DELETE FROM {$this->db->prefix}termmeta WHERE term_id = %d", $id
            ));
            $delete = $this->resouceCode(1400,'xóa chuyên mục thành công.');
        } else {
            $delete = $this->resouceCode(1401,'Lổi không xóa được chuyên mục bạn yêu cầu.');
        }
        return $delete;
    }
    /**
     * Sửa danh mục theo ID đc gữi lên từ client
     * @param int id <= lấy id của danh mục client gữi lên
     * @param object $data <= Truyền vào đối tượng 
     */
    private function edit($id,$data) {
        $id = sanitize_text_field($id);
        $ten = sanitize_text_field($data['tendanhmuc']);
        $slug = sanitize_text_field($data['slug']);
        $mota = sanitize_text_field($data['mota']);
        // kiểm tra $id có tồn tại hay không?
        if (isset($id) && !empty($ten)) {
            // Cập nhật lại bảng chính 
            $edit = $this->db->query($this->db->prepare(
                " UPDATE  {$this->db->prefix}terms SET name = %s, slug = %s WHERE term_id = %d",
                [$ten, $slug, $id]
            ));
            if (!empty($mota)) {
                // Update lun meta
                $edit = $this->db->query($this->db->prepare(
                    " UPDATE  {$this->db->prefix}termmeta SET meta_value = %s WHERE term_id = %d AND meta_key = %s",
                    [$mota, $id, 'danhmuc_mota']
                ));
            }
            $edit = [
                'code' => 200,
                'content' => 'Sửa chuyên mục thành công.'
            ];
        } else {
            //Trả ra kết quả nếu $id không tồn tại
            $edit = [
                'code' => 1402,
                'content' => 'Lổi không sửa được chuyên mục.'
            ];
        }
        return $edit;
    }
}