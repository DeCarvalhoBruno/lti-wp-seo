<?php namespace Lti\Seo;

use Lti\Seo\Generators\Singular_Keyword;
use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Plugin\Postbox_Values;

class Admin {

	private $plugin_name;
	private $version;
	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	private $settings;
	/**
	 * @var \Lti\Seo\Plugin\Postbox_Values
	 */
	private $box_values;
	private $unsupported_post_types = array( 'attachment' );
	private $current_page = "options-general";
	/**
	 * @var \Lti\Seo\Helpers\Wordpress_Helper
	 */
	private $helper;

	public function __construct(
		$plugin_name,
		$version,
		Plugin_Settings $settings,
		$plugin_path,
		ICanHelp $helper
	) {

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->admin_dir_url  = plugin_dir_url( __FILE__ );
		$this->admin_dir      = dirname( __FILE__ );
		$this->plugin_dir     = $plugin_path;
		$this->plugin_dir_url = plugin_dir_url( $plugin_path . '/index.php' );
		$this->settings       = $settings;
		$this->helper         = $helper;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, $this->plugin_dir_url . 'assets/css/lti_seo_admin.css',
			array( 'thickbox' ), $this->version,
			'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, $this->plugin_dir_url . 'assets/js/lti_seo_admin.js', array( 'jquery' ),
			$this->version,
			false );
		wp_localize_script( $this->plugin_name, 'lti_seo_i8n', array( 'use_img' => ltint( 'Use image' ) ) );
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
				print_r( $_POST );
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

		$oldSettings    = $this->settings;
		$this->settings = $this->settings->save( $data );

		if ( $this->settings != $oldSettings ) {
			$changed = $this->settings->compare( $oldSettings );
			if ( ! empty( $changed ) ) {
				$this->helper->update_global_post_fields( $changed );
			}
		}

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
		$this->box_values = get_post_meta( $post->ID, "lti_seo", true );

		if ( empty( $this->box_values ) ) {
			$this->box_values = new Postbox_Values( array() );
		}

		if(is_null($this->box_values->get('keywords'))){
			$f = new Singular_Keyword($this->helper, $this->settings, $post->ID);
			$this->box_values->set('keywords_suggestion', $f->get_tags());
		}

		if(is_null($this->box_values->get('description'))){
			$this->box_values->set('description_suggestion', $this->settings->get('frontpage_description_text'));
		}

		$this->set_current_page( 'post-edit' );
		include $this->admin_dir . '/partials/posts-box.php';
	}

	public function get_supported_post_types() {
		$post_types = get_post_types( array( 'public' => true, 'show_ui' => true ) );

		return array_diff( $post_types, $this->unsupported_post_types );
	}

	/**
	 * @return \Lti\Seo\Plugin\Plugin_Settings
	 */
	public function get_form_values() {
		switch ( $this->current_page ) {
			case "post-edit":
				return $this->box_values;
		}

		return $this->settings;
	}


	/**
	 * @param int $post_ID
	 * @param \WP_Post $post
	 * @param int $update
	 */
	public function save_post( $post_ID, $post, $update ) {
		if ( isset( $_POST['lti_seo'] ) ) {
			update_post_meta( $post_ID, 'lti_seo', new Postbox_Values( (object) $_POST['lti_seo'] ) );
		}

	}

	public function set_current_page( $page ) {
		$this->current_page = $page;
	}

	public function get_settings(){
		return $this->settings;
	}
}
