<?php namespace Lti\Seo;

use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Plugin\Postbox_Values;


class Frontend {

	private $plugin_name;

	private $version;

	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	private $settings;

	/**
	 * @var ICanHelp|\Lti\Seo\Helpers\Wordpress_Helper
	 */
	private $helper;

	public function __construct(
		$plugin_name,
		$version,
		Plugin_Settings $settings,
		ICanHelp $helper
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
		$this->helper->init();
		add_action( 'lti_seo_head', array( $this, 'frontpage_description' ) );
		$class_pattern = "Lti\\Seo\\Generators\\%s_%s";

		$keywords_class = sprintf( $class_pattern, $this->helper->page_type(), "Keyword" );
		if ( $this->settings->get( 'keyword_support' ) === true && class_exists( $keywords_class ) ) {
			$keywords = new $keywords_class( $this->helper, $this->settings );
			add_action( 'lti_seo_head', array( $keywords, 'display_tags' ) );
		}

		$og_class = sprintf( $class_pattern, $this->helper->page_type(), "Open_Graph" );

		if ( $this->settings->get( 'open_graph_support' ) === true && class_exists( $og_class ) ) {
			$og = new $og_class( $this->helper, $this->settings );
			add_action( 'lti_seo_head', array( $og, 'display_tags' ), 10 );
		}

		$twitter_class = sprintf( $class_pattern, $this->helper->page_post_format(), "Twitter_Card" );

		if ( $this->settings->get( 'twitter_card_support' ) === true && class_exists( $twitter_class ) ) {
			$twitter = new $twitter_class( $this->helper, $this->settings );
			add_action( 'lti_seo_head', array( $twitter, 'display_tags' ), 10 );
		}

		$robots_class = sprintf( $class_pattern, $this->helper->page_type(), "Robot" );
		if ( $this->settings->get( 'robots_support' ) === true && class_exists( $robots_class ) ) {
			$robots = new $robots_class( $this->helper, $this->settings );
			add_action( 'lti_seo_head', array( $robots, 'display_tags' ), 10 );
		}

		$jsonld_class = sprintf( $class_pattern, $this->helper->page_type(), "JSON_LD" );
		if ( class_exists( $jsonld_class ) ) {
			$this->settings->set( 'wp_home_url', home_url(), "Url" );
			$json_ld = new $jsonld_class( $this->settings );

			if ( $this->settings->get( 'jsonld_org_info' ) ) {
				add_action( 'lti_seo_json_ld', array( $json_ld, 'json_entity' ), 10 );
			}
			if ( $this->settings->get( 'jsonld_website_info' ) ) {
				add_action( 'lti_seo_json_ld', array( $json_ld, 'website_tag' ), 20 );
			}
			add_action( 'lti_seo_head', array( $json_ld, 'json_ld' ), 90 );
		}
	}

	public function frontpage_description() {
		if ( ( $this->settings->get( 'frontpage_description' ) === true ) && ! is_null( $this->settings->get( 'frontpage_description_text' ) ) ) {
			echo sprintf( '<meta name="description" content="%s"/>' . PHP_EOL,
				$this->helper->get_site_description() );
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

	public function user_contactmethods() {
		if ( ! isset( $contactmethods['lti_twitter_username'] ) ) {
			$contactmethods['lti_twitter_username'] = ltint( 'Twitter username (LTI)' );
		}
		if ( ! isset( $contactmethods['lti_facebook_url'] ) ) {
			$contactmethods['lti_facebook_url'] = ltint( 'Facebook profile URL (LTI)' );
		}
		if ( ! isset( $contactmethods['lti_gplus_url'] ) ) {
			$contactmethods['lti_gplus_url'] = ltint( 'Google+ profile URL (LTI)' );
		}

		return $contactmethods;
	}

}
