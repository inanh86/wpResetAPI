<?php namespace inanh86\Api\Auth;

if(!defined('ABSPATH')) {
    exit;
}

use Exception as Baoloi;

/**
 * Gọi danh sách routers ra cho Client
 * @param {} 
 */
class AppMap  {
    
    protected $loiMap = 1003;
    protected $CapRoles = '';

    public function __construct($cap) {
        $this->CapRoles = $cap;
    }
    /**
     * Kiểm tra Cap Của Client
     * @param array $cap Roles của Client
     */
    protected function kiemtraCap($cap) {
        if($cap['administrator'] === true) {
            return [
                'mapName' => 'admin',
            ];
        } else {
            throw new Baoloi("Lổi Map Client rồi nhé.", $this->loiMap);
        }
    }
    /**
     * Gọi danh sách Map ra cho Client
     * @param array $Client trả về client + roles
     */
    public function goiMap() {
        $checkCap = $this->kiemtraCap($this->CapRoles);
        if (!$checkCap) {
            return $checkCap;
        }
        return $this->readMap($checkCap['mapName']);
    }
    /**
     * Gọi máp ra và gữi về cho Client
     * @param string $name tên map truyền vào
     */
    protected function readMap($name) {
        if (isset($name)) {
            $fileName = docFile('/json/map/'.$name.'.json');
            return $fileName;
        } else {
            throw new Baoloi("Lổi không tải được máp bạn yêu cầu.", $this->loiMap);
        }
    }
}