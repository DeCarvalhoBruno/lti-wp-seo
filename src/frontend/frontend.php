<?php namespace Lti\Seo;

use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Plugin_Settings;

class Frontend {

	private $plugin_name;

	private $version;

	private $class_pattern = "Lti\\Seo\\Generators\\%s_%s";

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


	public function head() {
		$this->helper->init();

		$this->hook_functionality( 'Link_Rel' );

		if ( $this->settings->get( 'description_support' ) == true || $this->settings->get( 'frontpage_description' ) == true ) {
			$this->hook_functionality( 'Description' );
		}

		if ( $this->settings->get( 'keyword_support' ) == true || $this->settings->get( 'frontpage_keyword' ) == true ) {
			$this->hook_functionality( 'Keyword' );
		}

		if ( $this->settings->get( 'open_graph_support' ) == true ) {
			$this->hook_functionality( 'Open_Graph', 'page_post_format' );
		}

		if ( $this->settings->get( 'twitter_card_support' ) == true ) {
			$this->hook_functionality( 'Twitter_Card', 'page_post_format' );
		}

		//echo $this->helper->page_type();

		$this->hook_functionality( 'Robot' );

		$class = sprintf( $this->class_pattern, call_user_func( array( $this->helper, 'page_type' ) ), 'JSON_LD' );
		if ( class_exists( $class ) ) {
			$json_ld = new $class( $this->helper );

			add_action( 'lti_seo_head', array( $json_ld, 'json_ld' ) );
		}
			$seo_comment = apply_filters( 'lti_seo_header_comment', 'SEO' );
			if ( ! empty( $seo_comment ) ) {
				echo sprintf( "<!-- %s -->" . PHP_EOL, $seo_comment );
			}
			do_action( 'lti_seo_head' );
			if ( ! empty( $seo_comment ) ) {
				echo sprintf( "<!-- END %s -->" . PHP_EOL . PHP_EOL, $seo_comment );
			}
	}

	private function hook_functionality( $type, $format = 'page_type' ) {
		$class = sprintf( $this->class_pattern, call_user_func( array( $this->helper, $format ) ), $type );
		if ( class_exists( $class ) ) {
			$og = new $class( $this->helper );
			add_action( 'lti_seo_head', array( $og, 'display_tags' ) );
		}
	}

}
