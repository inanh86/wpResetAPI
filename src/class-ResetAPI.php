<?php namespace inanh86\Controller;

defined("ABSPATH") || exit;

final class WpResetAPI {
    /**
	 * Point Of Sale version.
	 *
	 * @var string
	 */
    public $version = '0.1';
    /**
	 * The single instance of the class.
	 *
	 * @since 0.1
	 */
    protected static $_instance = null;

    /**
	 * Main WpResetAPI Instance.
	 *
	 * Ensures only one instance of  WpResetAPI is loaded or can be loaded.
	 *
	 * @since 0.1
	 * @return WpResetAPI - Main instance.
	 */
    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function __construct() {
        
		$this->define_constants();
		$this->includes();
        $this->init_hooks();
    }
    /**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
    }
    private function define_constants() {
        $this->define( 'API_ABSPATH', dirname( API_PLUGIN_FILE ) . '/' );
    }
    public function init_hooks() {
        register_activation_hook( API_PLUGIN_FILE, array( '\inanh86\install\API_Install', 'install' ) );
    }
    private function includes() {

		// File cài đặt khi tiến hành active plugins
		include_once API_ABSPATH . 'src/class-Install.php';

		// Khởi chạy API
		include_once API_ABSPATH . 'src/class-Api.php';
		$this->API = new \inanh86\Api\Root();
		$this->API->init();
    }
}