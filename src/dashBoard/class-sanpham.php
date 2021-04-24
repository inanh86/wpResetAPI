<?php namespace inanh86\DashBoard\Menu;

if(!defined('ABSPATH')) {
    exit;
}

/**
 * Menu Sản phẩm admin
 * @see 
 */
class Sanpham extends \inanh86\DashBoard\Menu\Menu {

	/**
	 * Add Menu
	 */
	public function add_menu() {

		$menu =  $this->data_menu([
			'title' => 'Danh sách sản phẩm',
			'nameMenu' => 'Sản phẩm',
			'cap' => 'api_shop_manager',
			'slug' => 'san-pham',
			'page' => '',
			'icon' => 'dashicons-screenoptions',
			'position' => 3,
			'submenu' => [
				[
					'subTitle' => 'Quản lý tất cả sản phẩm',
					'nameSubmenu' => 'Tất cả sản phẩm',
					'cap' => 'api_shop_manager',
					'slug' => 'san-pham',
					'page' => [$this, 'danh_sach_san_pham'],
					'position' => 1
				],
				[
					'subTitle' => 'Thêm mới sản phẩm',
					'nameSubmenu' => 'thêm mới sản phẩm',
					'cap' => 'api_shop_manager',
					'slug' => 'san-pham-them-moi',
					'page' => [$this, 'them_moi_san_pham'],
					'position' => 2
				]	
			]
		], true );
		$menu = $this->data_menu([
			'title' => 'Danh sách sản phẩm',
			'nameMenu' => 'Kho hàng',
			'cap' => 'api_shop_manager',
			'slug' => 'kho-hang',
			'page' => '',
			'icon' => 'dashicons-screenoptions',
			'position' => 4,
			
		]);
		return $menu;
	}
	public function danh_sach_san_pham() {
		?>
			Hello World
		<?php
	}
	public function them_moi_san_pham() {
		?> Thêm mới sản phẩm <?php 
	}
}