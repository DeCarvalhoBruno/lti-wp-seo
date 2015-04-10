<?php namespace Lti\Seo;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */
class Frontend {

	private $plugin_name;

	private $version;

	public function __construct(
		$plugin_name,
		$version,
		Settings $settings,
		Wordpress_Helper $helper
	) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->settings    = $settings;
		$this->helper      = $helper;
	}


	public function rel_canonical() {
		if ( is_singular() ) {
			return;
		}
		$canonical = $this->helper->get_canonical_url();
		if ( ! empty( $canonical ) ) {
			echo sprintf( '<link rel="canonical" href="%s" />' . PHP_EOL, $canonical );
		}
	}

	public function wp_head() {
		do_action( 'lti_seo_head' );
	}

	public function front_page_head() {
		if ( ! $this->helper->is_front_page() ) {
			return;
		}
		add_action( 'lti_seo_head', array( $this, 'frontpage_description' ), 10 );

		if($this->settings->get( 'open_graph_support' )===true){
			$og = new Open_Graph('frontpage', $this->helper);
			add_action( 'lti_seo_head', array( $og, 'get_meta' ), 10 );
		}

		$this->settings->set( 'wp_home_url', $this->helper->home_url(), "Url" );
		$json_ld = new JSON_LD( $this->settings );
		add_action( 'lti_seo_json_ld', array( $json_ld, 'json_entity' ), 10 );
		if ( $this->settings->get( 'jsonld_website_info' ) ) {
			add_action( 'lti_seo_json_ld', array( $json_ld, 'internal_search' ), 20 );
		}
		add_action( 'lti_seo_head', array( $json_ld, 'json_ld' ), 90 );

	}


	public function frontpage_description() {
		if ( ! is_null( $this->settings->get( 'frontpage_description' ) ) && ! is_null( $this->settings->get( 'frontpage_description_text' ) ) ) {
			echo sprintf( '<meta name="description" content="%s"/>' . PHP_EOL,
				$this->settings->get( 'frontpage_description_text' ) );
		}
	}


}
