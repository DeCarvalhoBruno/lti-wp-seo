<?php namespace Lti\Seo\Generators;

use Lti\Seo\Helpers\ICanHelpWithJSONLD;

class JSON_LD {
	/**
	 * @param $helper \Lti\Seo\Helpers\Wordpress_Helper
	 */
	protected $helper;

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->maker  = new JSON_LD_Maker( $helper );
		$this->helper = $helper;
	}

	protected function json_ld_output( $output ) {
		if ( ! empty( $output ) ) {
			echo sprintf( '<script type="application/ld+json">%s</script>' . PHP_EOL, $output );
		}
	}

	public function json_ld() {
		do_action( 'lti_seo_json_ld' );
	}

	public function make_person() {
		$this->json_ld_output( $this->maker->make( 'Person' ) );
	}

	public function make_organization() {
		$this->json_ld_output( $this->maker->make( 'Organization' ) );
	}

	public function make_website() {
		$this->json_ld_output( $this->maker->make( 'WebSite' ) );
	}

}

class Frontpage_JSON_LD extends JSON_LD {

	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );

		if ( $this->helper->get( 'jsonld_website' ) ) {
			add_action( 'lti_seo_json_ld', array( $this, 'make_website' ) );
		} else {
			if ( $this->helper->get( 'jsonld_entity' ) ) {
				$type = $this->helper->get( 'jsonld_entity_type' );
				switch ( $type ) {
					case "person":
						add_action( 'lti_seo_json_ld', array( $this, 'make_person' ) );
						break;
					case "organization":
						add_action( 'lti_seo_json_ld', array( $this, 'make_organization' ) );
						break;
				}
			}
		}
	}

}

class Page_JSON_LD extends JSON_LD {

}


class JSON_LD_Maker {
	/**
	 * @var \Lti\Seo\Helpers\Wordpress_Helper
	 */
	private $helper;

	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->helper = $helper;
		$this->types  = new \ReflectionClass( $this );
	}

	public function make( $type ) {
		$type = __NAMESPACE__ . "\\" . $type;
		if ( class_exists( $type ) ) {
			//echo $class;
			$object = new $type( $this->helper );
			$result = $object->format();
			if ( is_array( $result ) && ! empty( $result ) ) {
				return json_encode( array_merge( array( '@context' => 'http://schema.org' ), $result ) );
			}
		}

		return null;
	}
}

