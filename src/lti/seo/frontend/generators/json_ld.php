<?php namespace Lti\Seo\Generators;


use Lti\Seo\Helpers\ICanHelp;

class Schema {
	protected $context = 'http://schema.org';
	protected $type;
	protected $value;

	public function __construct() {
		$this->value = array( "@context" => $this->context );
	}

	public function get() {
		return $this->value;
	}
}

interface canBeSearched {
}

class Schema_Type extends Schema {

	public function __construct() {
		parent::__construct();
		$this->value["@type"] = $this->get_type();
	}

	protected function get_type() {
		return $this->type;
	}
}

class WebSite extends Schema_Type {
	protected $type = "WebSite";

	/**
	 * @param $url
	 * @param canBeSearched|SearchAction $potentialAction
	 */
	public function __construct( $url, canBeSearched $potentialAction ) {
		parent::__construct();
		$this->value['url']             = $url;
		$this->value['potentialAction'] = $potentialAction->get();
	}
}

class Organization extends Schema_Type {
	protected $type = "Organization";

	public function __construct( $url, $name, $logo, $profiles ) {
		parent::__construct();
		$this->value['url']  = $url;
		$this->value['name'] = $name;
		if ( ! is_null( $logo ) ) {
			$this->value['logo'] = esc_url( $logo );
		}

		if ( ! empty( $profiles ) ) {
			$this->value['sameAs'] = $profiles;
		}

		$this->value = apply_filters( 'lti_seo_json_ld_organization', $this->value );
	}
}

class Person extends Schema_Type {
	protected $type = 'Person';

	public function __construct( $url, $name, $profiles ) {
		parent::__construct();
		$this->value['url']  = $url;
		$this->value['name'] = $name;

		if ( ! empty( $profiles ) ) {
			$this->value['sameAs'] = $profiles;
		}

		$this->value = apply_filters( 'lti_seo_json_ld_person', $this->value );
	}
}

class SearchAction extends Schema_Type implements canBeSearched {
	protected $type = "SearchAction";
}

class WordpressSearchAction extends SearchAction {
	public function __construct( $target, $query_input ) {
		$this->value = array( "@type" => $this->type, 'target' => $target, 'query-input' => $query_input );
	}
}

class JSON_LD {
}

class Frontpage_JSON_LD extends JSON_LD{

	private $profiles = array();

	/**
	 * @var \Lti\Seo\Helpers\ICanHelp|\Lti\Seo\Helpers\Wordpress_Helper
	 */
	private $helper;

	public function __construct(ICanHelp $helper ) {
		$this->helper = $helper;
	}

	protected function json_ld_output( $output ) {
		echo sprintf( '<script type="application/ld+json">%s</script>'.PHP_EOL, $output );
	}

	/**
	 * JSON LD output function that the functions for specific code can hook into
	 *
	 */
	public function json_ld() {
		do_action( 'lti_seo_json_ld' );
	}

	public function website_tag() {
		$home_url    = home_url('/');
		$website_tag = new WebSite(
			$home_url,
			new WordpressSearchAction( sprintf( "%s?s={search_term}", $home_url ), "required name=search_term" )
		);
		$this->json_ld_output( json_encode( $website_tag->get() ) );
	}

	public function organization_tag() {
		$home_url         = trailingslashit( $this->helper->get( 'wp_home_url' ) );
		$organization_tag = new Organization(
			$home_url,
			$this->helper->get( 'jsonld_type_name' ),
			$this->helper->get( 'jsonld_type_logo_url' ),
			$this->profiles
		);

		return json_encode( $organization_tag->get() );
	}

	public function person_tag() {
		$home_url   = home_url('/');
		$person_tag = new Person( $home_url, $this->helper->get( 'jsonld_type_name' ), $this->profiles );

		return json_encode( $person_tag->get() );
	}

	private function fetch_social_profiles() {
		$profiles        = array();
		$social_profiles = array(
			'account_facebook',
			'account_instagram',
			'account_linkedin',
			'account_gplus',
			'account_myspace',
			'account_youtube',
			'account_pinterest',
		);
		foreach ( $social_profiles as $profile ) {
			if ( ! is_null( $this->helper->get( $profile ) ) ) {
				$profiles[] = $this->helper->get( $profile );
			}
		}

		if ( ! empty( $profiles ) ) {
			$profiles_out   = '"' . implode( '","', $profiles ) . '"';
			$this->profiles = rtrim( $profiles_out, ',' );
		}
	}

	public function json_entity() {
		$this->fetch_social_profiles();

		switch ( $this->helper->get( 'jsonld_entity_type' ) ) {
			case 'organization':
				$output = $this->organization_tag();
				break;
			case 'person':
				$output = $this->person_tag();
				break;
		}
		if ( isset( $output ) ) {
			$this->json_ld_output( $output );
		}
	}
}
