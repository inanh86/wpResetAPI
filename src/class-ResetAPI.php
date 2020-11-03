<?php 
namespace inanh86;

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
        $this->init_hooks();
		$this->includes();
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
        register_activation_hook( API_PLUGIN_FILE, array( 'API_Install', 'install' ) );
    }
    private function includes() {
        
    }
    
}