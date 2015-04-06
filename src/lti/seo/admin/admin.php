<?php namespace Lti\Seo;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    lti-seo
 * @subpackage lti-seo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    lti-seo
 * @subpackage lti-seo/admin
 * @author     Your Name <email@example.com>
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $options ) {

		$this->lti_seo = $plugin_name;
		$this->version = $version;
		$this->admin_dir_url = plugin_dir_url( __FILE__ );
		$this->admin_dir = dirname( __FILE__ );
		$this->options = $options;


	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in lti-seo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The lti-seo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->lti_seo, $this->admin_dir_url . 'css/admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in lti-seo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The lti-seo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->lti_seo, $this->admin_dir_url . 'js/admin.js', array( 'jquery' ), $this->version, false );

	}

	public function admin_menu(){
		add_options_page( ltint('LTI SEO Settings'), ltint('LTI SEO'), 'manage_options', 'lti-seo-options', [$this,'options_page'] );
	}

	function plugin_actions( $links, $file ) {
		if( $file == 'lti-wp-seo/lti-wp-seo.php' && function_exists( "admin_url" ) ) {
			array_unshift( $links, '<a href="' . admin_url( 'options-general.php?page=lti-seo-options' ) . '">' . ltint( 'Settings' ) . '</a>' );
		}
		return $links;
	}

	function options_page(){
		include $this->admin_dir.'/partials/admin-settings-panel.php';
	}

}
