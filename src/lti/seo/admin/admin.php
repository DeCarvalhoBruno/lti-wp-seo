<?php namespace Lti\Seo;

use Lti\Seo\Generators\Singular_Keyword;
use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Plugin\Postbox_Values;

class Admin {

	private $page_type = 'edit';
	private $message = '';
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
		wp_enqueue_style( $this->plugin_name, $this->plugin_dir_url . 'assets/dist/css/lti_seo_admin.css',
			array( 'thickbox' ), $this->version,
			'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, $this->plugin_dir_url . 'assets/dist/js/lti_seo_admin.js',
			array( 'jquery' ),
			$this->version,
			false );
		wp_localize_script( $this->plugin_name, 'lti_seo_i8n', array( 'use_img' => ltint( 'general.use_image' ) ) );
	}

	public function admin_menu() {
		$page = add_options_page( ltint( 'admin.menu_title' ), ltint( 'admin.menu_item' ), 'manage_options',
			'lti-seo-options',
			array( $this, 'options_page' ) );
		add_action( 'load-' . $page, array( $this, 'wp_help_menu' ) );
	}

	public function wp_help_menu() {
		include $this->admin_dir . '/partials/help_menu.php';
		$screen = get_current_screen();
		$menu   = new \Lti_Seo_Help_Menu();
		$screen->add_help_tab( array(
			'id'      => 'general_hlp_welcome',
			'title'   => ltint( 'general_hlp_welcome' ),
			'content' => $menu->welcome_tab()
		) );
		$screen->add_help_tab( array(
			'id'      => 'general_hlp_general',
			'title'   => ltint( 'general_hlp_general' ),
			'content' => $menu->general_tab()
		) );
		$screen->add_help_tab( array(
			'id'      => 'general_hlp_frontpage',
			'title'   => ltint( 'general_hlp_frontpage' ),
			'content' => $menu->frontpage_tab()
		) );
		$screen->add_help_tab( array(
			'id'      => 'general_hlp_social',
			'title'   => ltint( 'general_hlp_social' ),
			'content' => $menu->social_tab()
		) );

		$screen->set_help_sidebar(
			$menu->sidebar()
		);
	}

	public function plugin_actions( $links, $file ) {
		if ( $file == 'lti-wp-seo/lti-wp-seo.php' && function_exists( "admin_url" ) ) {
			array_unshift( $links,
				'<a href="' . admin_url( 'options-general.php?page=lti-seo-options' ) . '">' . ltint( 'general.settings' ) . '</a>' );
		}

		return $links;
	}

	public function options_page() {
		if ( isset( $_POST['lti_seo_update'] ) ) {
			if ( isset( $_POST['lti_seo_token'] ) ) {
				if ( wp_verify_nonce( $_POST['lti_seo_token'], 'lti_seo_options' ) !== false ) {
					$this->validate_input( $_POST );
					$this->page_type = "lti_update";
					$this->message   = ltint( 'opt.msg.updated' );

				} else {
					$this->page_type = "lti_error";
					$this->message   = ltint( "opt.msg.error_token" );
				}
			}
		} elseif ( isset( $_POST['lti_seo_reset'] ) ) {
			$this->settings = new Plugin_Settings();
			update_option( 'lti_seo_options', $this->settings );
			$this->helper->update_global_post_fields( array(), true );

			$this->page_type = "lti_reset";
			$this->message   = ltint( 'opt.msg.reset' );
		} else {
			$this->page_type = "lti_edit";
		}
		include $this->admin_dir . '/partials/options-page.php';
	}

	public function register_setting() {
		Activator::activate();
		//register_setting( 'general', 'lti_seo_options' );
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
	}

	public function add_meta_boxes() {
		$supported_post_types = $this->get_supported_post_types();

		foreach ( $supported_post_types as $supported_post_type ) {
			add_meta_box(
				'lti-seo-metadata-box',
				ltint( 'admin.meta_box' ),
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

		if ( $this->settings->get( 'keyword_support' ) == true ) {
			$keyword_text = $this->box_values->get( 'keywords' );
			if ( is_null( $keyword_text ) || empty( $keyword_text ) ) {
				$f = new Singular_Keyword( $this->helper, $post->ID );
				$this->box_values->set( 'keywords_suggestion', str_replace( ',', ', ', $f->get_tags() ) );
			}
		}
		$this->set_current_page( 'post-edit' );
		include $this->admin_dir . '/partials/postbox.php';
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

	public function get_settings() {
		return $this->settings;
	}

	public function get_page_type() {
		return $this->page_type;
	}

	public function get_message() {
		return $this->message;
	}
}
