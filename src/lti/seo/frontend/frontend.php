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


	public function wp_head() {
		do_action( 'lti_seo_head' );
	}

	public function head() {
		add_action( 'lti_seo_head', array( $this, 'frontpage_description' ), 10 );

		$class_pattern = "Lti\\Seo\\Generators\\%s_%s";
		$og_class = sprintf( $class_pattern, $this->helper->page_type(), "Open_Graph" );

		if ( $this->settings->get( 'open_graph_support' ) === true && class_exists( $og_class ) ) {
			$og = new $og_class( $this->helper );
			add_action( 'lti_seo_head', array( $og, 'get_tags' ), 10 );
		}

		$twitter_class = sprintf( $class_pattern, $this->helper->page_type(), "Twitter_Card" );

		if ( $this->settings->get( 'twitter_card_support' ) === true && class_exists( $twitter_class ) ) {
			$twitter = new $twitter_class( $this->helper, $this->settings->get( 'twitter_card_type' ), $this->helper->get_twitter_handle() );
			add_action( 'lti_seo_head', array( $twitter, 'get_tags' ), 10 );
		}
		
		$jsonld_class = sprintf( $class_pattern, $this->helper->page_type(), "JSON_LD" );
		if ( class_exists( $jsonld_class ) ) {
			$this->settings->set( 'wp_home_url', home_url(), "Url" );
			$json_ld = new $jsonld_class( $this->settings );

			if ( $this->settings->get( 'jsonld_org_info' ) ) {
			add_action( 'lti_seo_json_ld', array( $json_ld, 'json_entity' ), 10 );
			}
			if ( $this->settings->get( 'jsonld_website_info' ) ) {
				add_action( 'lti_seo_json_ld', array( $json_ld, 'internal_search' ), 20 );
			}
			add_action( 'lti_seo_head', array( $json_ld, 'json_ld' ), 90 );
		}
	}

	public function frontpage_description() {
		if ( ! is_null( $this->settings->get( 'frontpage_description' ) ) && ! is_null( $this->settings->get( 'frontpage_description_text' ) ) ) {
			echo sprintf( '<meta name="description" content="%s"/>' . PHP_EOL,
				$this->settings->get( 'frontpage_description_text' ) );
		}
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

}
