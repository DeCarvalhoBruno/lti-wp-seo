<?php namespace Lti\Seo\Generators;

use Lti\Seo\Helpers\ICanHelpWithJSONLD;

abstract class Thing {
	/**
	 * @var \Lti\Seo\Helpers\Wordpress_Helper
	 */
	protected static $helper;
	protected $url;
	protected $name;
	protected $logo;
	protected $alternateName;
	protected $searchAction;
	protected $sameAs;
	protected static $type;

	protected function fetch_social_profiles() {
		$profiles = array();

		$profiles = static::$helper->get_social_urls();
		if ( ! empty( $profiles ) ) {

			$profiles_out = '"' . implode( '","', array_filter($profiles) ) . '"';
			$profiles_out = rtrim( $profiles_out, ',' );

			return $profiles_out;
		}

		return null;
	}

	public function get_type() {
		return static::$type;
	}

	protected function addSearchAction() {

	}

	public function format() {
		$result = array();

		$result["@type"] = $this->get_type();
		$values          = get_object_vars( $this );
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
	protected static $type='SearchAction';
}

interface ICanSearch {
	public function get_query_type();
}

class WordpressSearchAction extends SearchAction implements ICanSearch {

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->target        = sprintf( "%s?s={search_term}", $helper->get_current_url() );
		$query_type          = $this->get_query_type();
		$this->{$query_type} = "required name=search_term";
	}

	public function get_query_type() {
		return "query-input";
	}
}

class Person extends Thing {

	protected static $type='Person';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		static::$helper = $helper;
		$this->sameAs = $this->fetch_social_profiles();
		$this->name   = $helper->get_schema_org_name();
		$this->alternateName = $helper->get_schema_org_alternate_name();
		$this->url    = $helper->get_schema_org_website();
		$this->workLocation    = new Place($helper);
		$this->jobTitle    = $helper->get_schema_org_job_title();
	}

}

class Place extends Thing {
	protected $geo;
	protected static $type='Place';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->geo = new GeoCoordinates($helper);
	}
}

class GeoCoordinates extends Thing {
	protected $longitude;
	protected $latitude;
	protected static $type='GeoCoordinates';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->longitude = $helper->get_schema_org_longitude();
		$this->latitude = $helper->get_schema_org_latitude();
	}
}

class Organization extends Thing {

	protected static $type='Organization';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		static::$helper = $helper;
		$this->sameAs        = $this->fetch_social_profiles();
		$this->logo          = $helper->get_schema_org_logo();
		$this->name          = $helper->get_schema_org_name();
		$this->alternateName = $helper->get_schema_org_alternate_name();
		$this->url           = $helper->get_schema_org_website();
	}

}

class WebSite extends CreativeWork {

	protected static $type='WebSite';
	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->url             = $helper->get_current_url();
		$class                 = $helper->get_search_action_type();
		$this->potentialAction = new $class( $helper );
		if ($helper->get( 'jsonld_entity' ) ) {
			$type = $helper->get( 'jsonld_entity_type' );
			switch ( $type ) {
				case "person":
					$this->author = new Person($helper);
					break;
				case "organization":
					$this->publisher = new Organization($helper);
					break;
			}
		}

	}

	public function get_type() {
		return 'WebSite';
	}
}

class CreativeWork extends Thing{
	protected $publisher;
}

class WebPage extends CreativeWork {

	protected static $type='WebPage';
	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->url             = $helper->get_current_url();
	}

}