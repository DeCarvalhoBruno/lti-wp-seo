<?php namespace Lti\Seo;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @author     Your Name <email@example.com>
 */
class LTI_SEO {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      \Lti\SEO\Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $LTI_SEO;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	public static $instance;

	/**
	 * @var \Lti\Seo\Settings
	 */
	private $settings;

	public $plugin_path;
	/**
	 * @var \Lti\Seo\Admin
	 */
	public $plugin_admin;
	public $plugin_frontend;
	private $helper;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 */
	public function __construct() {
		require_once plugin_dir_path( __FILE__ ) . 'settings.php';
		$this->name        = 'lti-wp-seo';
		$this->plugin_path = dirname( dirname( dirname( plugin_dir_path( __FILE__ ) ) ) );

		$this->settings = get_option( "lti_seo_options" );

		if ( $this->settings === false ) {
			$this->settings = new Settings( null );
		}
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	public function get_settings() {
		return $this->settings;
	}

	public function get_helper() {
		return $this->helper;
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - lti-seo_Loader. Orchestrates the hooks of the plugin.
	 * - lti-seo_i18n. Defines internationalization functionality.
	 * - lti-seo_Admin. Defines all hooks for the admin area.
	 * - lti-seo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'helpers.php';
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( ( __FILE__ ) ) . 'loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( ( __FILE__ ) ) . 'i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'frontend/frontend.php';

		require_once plugin_dir_path( __FILE__ ) . 'frontend/json_ld.php';
		require_once plugin_dir_path( __FILE__ ) . 'wordpress_helper.php';
		require_once plugin_dir_path( __FILE__ ) . 'frontend/open_graph.php';
		$this->loader = new Loader();
		$this->helper = new Wordpress_Helper($this->settings);


	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the lti-seo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {
		$this->plugin_admin = new Admin( $this->name, $this->version, $this->settings, $this->plugin_path );

		$this->loader->add_action( 'admin_init', $this->plugin_admin, 'register_setting' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $this->plugin_admin, 'admin_menu' );
		$this->loader->add_filter( 'plugin_action_links', $this->plugin_admin, 'plugin_actions', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $this->plugin_admin, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $this->plugin_admin, 'save_post', 10, 3 );
	}

	/**
	 * @return \Lti\Seo\Admin
	 */
	public function get_admin() {
		return $this->plugin_admin;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->plugin_frontend = new Frontend( $this->name, $this->version, $this->settings, $this->helper);

		$this->loader->add_action( 'wp_head', $this->plugin_frontend, 'front_page_head', 0 );

		if ( $this->settings->get('canonical_urls') === true ) {
			$this->loader->add_action( 'wp_head', $this->plugin_frontend, 'rel_canonical' );
		}
		$this->loader->add_action( 'wp_head', $this->plugin_frontend, 'wp_head', 10 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    \Lti\Seo\Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public static function activate() {
		require_once plugin_dir_path( __FILE__ ) . 'activator.php';
		Activator::activate();
	}

	public static function deactivate() {
		require_once plugin_dir_path( __FILE__ ) . 'deactivator.php';
		Deactivator::deactivate();
	}


}
