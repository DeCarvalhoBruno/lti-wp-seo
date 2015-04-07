<?php namespace Lti\Seo;

class Admin {

	private $plugin_name;
	private $version;
	private $settings;
	private $unsupported_post_types = array( 'attachment' );

	public function __construct( $plugin_name, $version, Settings $settings ) {

		$this->lti_seo       = $plugin_name;
		$this->version       = $version;
		$this->admin_dir_url = plugin_dir_url( __FILE__ );
		$this->admin_dir     = dirname( __FILE__ );
		$this->settings      = $settings;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->lti_seo, $this->admin_dir_url . 'css/lti_seo.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->lti_seo, $this->admin_dir_url . 'js/admin.js', array( 'jquery' ), $this->version,
			false );

	}

	public function admin_menu() {
		add_options_page( ltint( 'LTI SEO Settings' ), ltint( 'LTI SEO' ), 'manage_options', 'lti-seo-options',
			[ $this, 'options_page' ] );
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
			} else {
				//error message;
			}
		}
		print_r( $_POST );
		include $this->admin_dir . '/partials/options-page.php';
	}

	public function register_setting() {
		register_setting( 'general', 'lti_seo_options' );
	}

	public function validate_input( $data ) {
		unset( $data['_wpnonce'], $data['option_page'], $data['_wp_http_referer'] );
		$this->settings = $this->settings->save( $data );
		update_option( 'lti_seo_options', $this->settings );
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


}
