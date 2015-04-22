<?php namespace Lti\Seo\Generators;

use Lti\Seo\Helpers\ICanHelpWithJSONLD;

class JSON_LD {
	/**
	 * @param $helper \Lti\Seo\Helpers\Wordpress_Helper
	 */
	protected $helper;

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

abstract class Thing {
	/**
	 * @var \Lti\Seo\Helpers\Wordpress_Helper
	 */
	protected $helper;
	protected $url;
	protected $name;
	protected $logo;
	protected $alternateName;
	protected $searchAction;
	protected $sameAs;

	protected function fetch_social_profiles() {
		$profiles = array();

		$profiles = $this->helper->get_social_urls();
		if ( ! empty( $profiles ) ) {

			$profiles_out = '"' . implode( '","', $profiles ) . '"';
			$profiles_out = rtrim( $profiles_out, ',' );

			return $profiles_out;
		}

		return null;
	}

	protected function addSearchAction() {

	}

	public function format() {
		$result = array();

		$result["@type"] = $this->get_type();
		$values          = get_object_vars( $this );
		unset( $values['helper'] );
		foreach ( $values as $key => $value ) {
			if ( ! is_null( $value ) && ! empty( $value ) ) {
				if ( $value instanceof Thing ) {
					$result[ $key ] = $value->format();
				} else {
					$result[ $key ] = $value;
				}
			}
		}

		return $result;
	}

}

class Action extends Thing {
	protected $target;
}

class SearchAction extends Action {

	protected $query;

	public function get_type() {
		return 'SearchAction';
	}
}

interface ICanSearch {
	public function get_query_type();
}

class WordpressSearchAction extends SearchAction implements ICanSearch {
	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->helper        = $helper;
		$this->target        = sprintf( "%s?s={search_term}", $this->helper->get_current_url() );
		$query_type          = $this->get_query_type();
		$this->{$query_type} = "required name=search_term";
	}

	public function get_query_type() {
		return "query-input";
	}
}

class Person extends Thing {
	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->helper = $helper;
		$this->sameAs = $this->fetch_social_profiles();
		$this->name   = $this->helper->get_thing_name();
		$this->url    = $this->helper->get_current_url();
	}

	public function get_type() {
		return 'Person';
	}
}

class Organization extends Thing {

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->helper        = $helper;
		$this->sameAs        = $this->fetch_social_profiles();
		$this->logo          = $this->helper->get_thing_logo();
		$this->name          = $this->helper->get_thing_name();
		$this->alternateName = $this->helper->get_thing_alternate_name();
		$this->url           = $this->helper->get_current_url();
	}

	public function get_type() {
		return 'Organization';
	}
}

class WebSite extends Thing {
	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->helper          = $helper;
		$this->url             = $this->helper->get_current_url();
		$class                 = $this->helper->get_search_action_type();
		$this->potentialAction = new $class( $this->helper );

	}

	public function get_type() {
		return 'WebSite';
	}
}