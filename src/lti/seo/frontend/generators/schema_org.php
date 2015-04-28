<?php namespace Lti\Seo\Generators;

use Lti\Seo\Helpers\ICanHelpWithJSONLD;

class Thing {
	/**
	 * @var \Lti\Seo\Helpers\Wordpress_Helper
	 */
	protected static $helper;
	protected $url;
	protected $name;
	protected $logo;
	protected $description;
	protected $alternateName;
	protected $searchAction;
	protected $sameAs;
	protected static $type;

	public function __construct( Array $properties ) {
		$this_class = new \ReflectionClass( $this );
		if ( ! empty( $properties ) ) {
			foreach ( $properties as $property => $value ) {
				if ( $this_class->hasProperty( $property ) ) {
					$this->{$property} = $value;
				}
			}
		}

	}

	public function get_type() {
		return static::$type;
	}

	public function set_type( $type ) {
		static::$type = $type;
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
	protected static $type = 'SearchAction';
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

	protected static $type = 'Person';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		static::$helper      = $helper;
		$this->sameAs        = $helper->get_author_social_info();
		$this->name          = $helper->get_schema_org_user( 'display_name' );
		$this->url           = $helper->get_schema_org_user( 'user_url' );
		$this->workLocation  = new Place( $helper );
		$this->jobTitle      = $helper->get_schema_org_user_meta( 'job_title' );
		$this->email         = $helper->get_schema_org_user_meta( 'public_email' );
	}

}

class Organization extends Thing {

	protected static $type = 'Organization';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		static::$helper      = $helper;
		$this->sameAs        = $helper->get_social_urls();
		$this->logo          = $helper->get_schema_org_setting( 'logo_url' );
		$this->name          = $helper->get_schema_org_setting( 'name' );
		$this->alternateName = $helper->get_schema_org_setting( 'alternate_name' );
		$this->url           = $helper->get_schema_org_setting( 'website_url' );
	}

}

abstract class CreativeWork extends Thing {
	protected $publisher;
	protected $datePublished;
	protected $dateModified;
	protected $copyrightYear;
	protected $headline;
	protected $keywords;
	protected $thumbnailUrl;
	protected $inLanguage;

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->url          = $helper->get_current_url();
		$this->headline     = $helper->get_schema_org_general( 'title' );
		$this->keywords     = $helper->get_schema_org_general( 'tags' );
		$this->thumbnailUrl = $helper->get_schema_org_general( 'thumbnail_url' );
		$this->inLanguage   = $helper->get_schema_org_general( 'language' );
	}

	protected function get_author_publisher() {
		if ( static::$helper->get( 'jsonld_entity' ) ) {
			$type = static::$helper->get( 'jsonld_entity_type' );
			switch ( $type ) {
				case "person":
					$this->author = new Person( static::$helper );
					break;
				case "organization":
					$this->publisher = new Organization( static::$helper );
					break;
			}
		}
	}
}

class WebSite extends CreativeWork {

	protected static $type = 'WebSite';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );
		static::$helper        = $helper;
		$class                 = $helper->get_search_action_type();
		$this->potentialAction = new $class( $helper );
		$this->get_author_publisher();

	}

	public function get_type() {
		return 'WebSite';
	}
}

class Blog extends CreativeWork {
	protected static $type = 'Blog';
	protected $blogPosting;

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );
		static::$helper        = $helper;
		$class                 = $helper->get_search_action_type();
		$this->potentialAction = new $class( $helper );
		$this->get_author_publisher();

	}
}

class WebPage extends CreativeWork {

	protected static $type = 'WebPage';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );
		$this->datePublished = $helper->date_conversion( $helper->get_schema_org_post( 'post_date' ) );
		$this->dateModified  = $helper->date_conversion( $helper->get_schema_org_post( 'post_modified' ) );
		$this->copyrightYear = $helper->date_get_year( $helper->get_schema_org_post( 'post_modified' ) );
	}
}

class Article extends CreativeWork {
	protected static $type = 'Article';
	protected $articleSection;
	protected $wordCount;

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		parent::__construct( $helper );
		$this->articleSection = $helper->get_schema_org_general( 'categories' );
		$this->wordCount      = $helper->get_schema_org_post_meta( 'word_count' );
		$user_website         = $helper->get_schema_org_user( 'user_url' );
		if ( ! empty( $user_website ) && ! is_null( $user_website ) ) {
			$thing = new Thing( array( 'url' => $helper->get_schema_org_user( 'user_url' ) ) );
			$thing->set_type( 'Person' );
			$this->author = $thing;
		}
	}
}

class Place extends Thing {
	protected $geo;
	protected static $type = 'Place';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->geo = new GeoCoordinates( $helper );
	}
}

class GeoCoordinates extends Thing {
	protected $longitude;
	protected $latitude;
	protected static $type = 'GeoCoordinates';

	/**
	 * @param \Lti\Seo\Helpers\Wordpress_Helper $helper
	 */
	public function __construct( ICanHelpWithJSONLD $helper ) {
		$this->longitude = $helper->get_schema_org_user_meta( 'work_longitude' );
		$this->latitude  = $helper->get_schema_org_user_meta( 'work_latitude' );
	}
}

class BlogPosting extends Article {
	protected static $type = 'BlogPosting';
}

class NewsArticle extends Article {
	protected static $type = 'NewsArticle';
}

class ScholarlyArticle extends Article {
	protected static $type = 'ScholarlyArticle';
}

class TechArticle extends Article {
	protected static $type = 'TechArticle';
}

class SearchResultsPage extends WebPage {
	protected static $type = 'SearchResultsPage';

	public function __construct( ICanHelpWithJSONLD $helper ) {
		Thing::__construct( array( 'url' => $helper->get_current_url() ) );
	}
}