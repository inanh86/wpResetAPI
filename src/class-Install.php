<?php namespace inanh86\install;

use WP_Roles;

if (!defined('ABSPATH')) {
	exit;
}
class API_Install {
    public static function install() {
		self::create_tables();
		self::create_options();
		self::remove_roles();
		self::create_roles();
    }
    public static function create_tables() {
        global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$wpdb->hide_errors();
		$collate = '';
		if ($wpdb->has_cap('collation')) {
			$collate = $wpdb->get_charset_collate();
		}
		/*
		 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
		 * As of WP 4.2, however, they moved to utf8mb4, which uses 4 bytes per character. This means that an index which
		 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
		 */
        $max_index_length = 191;

        // Khởi tạo bảng order
		$create_order = "
			CREATE TABLE IF NOT EXISTS {$wpdb->prefix}order (
			order_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			order_date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			order_date_completed datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			order_date_update datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			order_cashier varchar(255) DEFAULT NULL,
			order_coupon_lines varchar(255) DEFAULT NULL,
			order_line_item longtext NOT NULL,
			order_customer_note varchar(255) NOT NULL,
			order_status varchar(200) DEFAULT NULL,
			PRIMARY KEY (order_id)
			) $collate; 
		";
		dbDelta($create_order);

		// Khởi tạo bảng order_meta
		$create_order_meta = "
			CREATE TABLE IF NOT EXISTS {$wpdb->prefix}order_meta (
			order_meta_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			order_id BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
			meta_key varchar(255) DEFAULT NULL,
            meta_value longtext NOT NULL,
			PRIMARY KEY (order_meta_id)
			) $collate; 
		";
		dbDelta($create_order_meta);
    }
    public static function create_options()
	{
		// Add default settings if not exists for normal installation
		$settings = get_option('api_settings');
		if (empty($settings)) {
			$settings['installed_on'] = date("Y/m/d");
			$settings['version'] = '0.1';
			update_option('api_settings', $settings, "no");
		}
    }
    public static function create_roles()
	{
		global $wp_roles;

		if (!class_exists('WP_Roles')) {
			return;
		}

		if (!isset($wp_roles)) {
			$wp_roles = new WP_Roles(); // @codingStandardsIgnoreLine
		}
		// Khách hàng.
		add_role(
			'api_shop_customer',
			'Khách hàng',
			array(
				'read' => true,
				'level_0' => true
			)
		);
		// Quản lý cửa hàng
		add_role(
			'api_shop_manager',
			'Quản lý cửa hàng',
			array(
				'read' => true,
				'level_9' => true
			)
		);
		// Nhân viên cửa hàng
		add_role(
			'api_shop_staff',
			'Nhân viên',
			array(
				'read' => true,
				'level_2' => true,
			)
		);
    }
    /**
	 * Remove WooCommerce roles.
	 */
	public static function remove_roles()
	{
		global $wp_roles;

		if (!class_exists('WP_Roles')) {
			return;
		}

		if (!isset($wp_roles)) {
			$wp_roles = new WP_Roles(); // @codingStandardsIgnoreLine
		}

		/* remove the unnecessary roles */
		$wp_roles->remove_role('subscriber');
		$wp_roles->remove_role('editor');
		$wp_roles->remove_role('author');
		$wp_roles->remove_role('contributor');
	}
}
