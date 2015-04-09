<?php namespace Lti\Seo;

class Admin {

	private $plugin_name;
	private $version;
	private $settings;
	private $unsupported_post_types = array( 'attachment' );

	public function __construct( $plugin_name, $version, Settings $settings, $plugin_path ) {

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->admin_dir_url  = plugin_dir_url( __FILE__ );
		$this->admin_dir      = dirname( __FILE__ );
		$this->plugin_dir     = $plugin_path;
		$this->plugin_dir_url = plugin_dir_url( $plugin_path . '/index.php' );
		$this->settings       = $settings;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, $this->plugin_dir_url . 'assets/css/lti_seo_admin.css', array('thickbox'), $this->version,
			'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, $this->plugin_dir_url . 'assets/js/lti_seo_admin.js', array( 'jquery'), $this->version,
			false );
		wp_localize_script( $this->plugin_name, 'lti_seo_i8n', array('use_img'=>ltint('Use image')) );
	}

	public function admin_menu() {
		add_options_page( ltint( 'LTI SEO Settings' ), ltint( 'LTI SEO' ), 'manage_options', 'lti-seo-options',
			array( $this, 'options_page' ) );
	}

	public function plugin_actions( $links, $file ) {
		if ( $file == 'lti-wp-seo/lti-wp-seo.php' && function_exists( "admin_url" ) ) {
			array_unshift( $links,
				'<a href="' . admin_url( 'options-general.php?page=lti-seo-options' ) . '">' . ltint( 'Settings' ) . '</a>' );
		}

		return $links;
	}

	public function options_page() {
		if ( isset( $_POST['lti_seo_token'] ) ) {
			if ( wp_verify_nonce( $_POST['lti_seo_token'], 'lti_seo_options' ) !== false ) {
				$this->validate_input( $_POST );
				print_r($_POST);
			} else {
				//error message;
			}
		}
		include $this->admin_dir . '/partials/options-page.php';
	}

	public function register_setting() {
		register_setting( 'general', 'lti_seo_options' );
	}

	public function validate_input( $data ) {
		unset( $data['_wpnonce'], $data['option_page'], $data['_wp_http_referer'] );
		$this->settings = $this->settings->save( $data );
		update_option( 'lti_seo_options', $this->settings );
//		echo "after save<pre>";
//		print_r($this->settings);
//		echo "</pre>";
		
	}

	public function add_meta_boxes() {
		$supported_post_types = $this->get_supported_post_types();

		foreach ( $supported_post_types as $supported_post_type ) {
			add_meta_box(
				'lti-seo-metadata-box',
				ltint( 'LTI SEO' ),
				array( $this, 'metadata_box' ),
				$supported_post_type,
				'advanced',
				'high'
			);
		}
	}

	public function metadata_box( \WP_Post $post ) {
		include $this->admin_dir . '/partials/posts-box.php';
	}

	public function get_supported_post_types() {
		$post_types = get_post_types( array( 'public' => true, 'show_ui' => true ) );

		return array_diff( $post_types, $this->unsupported_post_types );
	}

	/**
	 * @return \Lti\Seo\Settings
	 */
	public function get_settings(){
		return $this->settings;
	}

	/**
	 * @param int $post_ID
	 * @param \WP_Post $post
	 * @param int $update
	 */
	public function save_post( $post_ID, $post, $update){

//		$f = new \stdClass();
//		update_post_meta($post_ID,'lti_seo_meta',$f);

	}
}
