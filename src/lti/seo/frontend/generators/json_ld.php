<?php namespace Lti\Seo\Generators;

/**
 * Class JSON_LD
 *
 * Outputs JSON LD schema code
 *
 */
class Frontpage_JSON_LD {

	/**
	 * @var \Lti\Seo\Settings
	 */
	public $options = array();

	/**
	 * Holds the social profiles for the entity
	 * @var array
	 */
	private $profiles = array();

	/**
	 * @param \Lti\Seo\Settings $settings
	 */
	public function __construct( $settings ) {
		$this->options = $settings;
	}

	/**
	 * JSON LD output function that the functions for specific code can hook into
	 *
	 */
	public function json_ld() {
		do_action( 'lti_seo_json_ld' );
	}

	/**
	 * Outputs the JSON LD code in a valid JSON+LD wrapper
	 *
	 *
	 * @param string $output
	 */
	protected function json_ld_output( $output ) {
		echo "<script type='application/ld+json'>";
		echo preg_replace( '/[\r\n\t]/', '', $output );
		echo '</script>' . "\n";
	}

	/**
	 * Returns JSON+LD schema for Organization
	 *
	 * @return string
	 */
	private function organization() {
		$output = '';
		if ( ! is_null( $this->options->get( 'jsonld_type_name' ) ) ) {
			$output = '{ "@context": "http://schema.org",
			"@type": "Organization",
			"name": "' .  $this->options->get( 'jsonld_type_name' ) . '",
			"url": "' . $this->options->get( 'wp_home_url') . '",';
			if ( ! is_null( $this->options->get( 'jsonld_type_logo_url' ) ) ) {
				$output .= '"logo": "' . esc_url( $this->options->get( 'jsonld_type_logo_url' ) ) . '"';
			}
			if ( ! empty( $this->profiles ) ) {
				$output .= '",sameAs": [' . $this->profiles . ']';
			}
			$output .= "}";
			/**
			 * Filter: 'lti_seo_json_ld_organization' - Allows filtering of the JSON+LD organization output
			 *
			 * @api string $output The organization output
			 */
			$output = apply_filters( 'lti_seo_json_ld_organization', $output );
		}

		return $output;
	}

	/**
	 * Returns JSON+LD schema for Person
	 *
	 * @return string
	 */
	private function person() {
		$output = '';
		if ( ! is_null( $this->options->get( 'jsonld_type_name' ) ) ) {
			$output = '{ "@context": "http://schema.org",
			"@type": "Person",
			"name": "' . $this->options->get( 'jsonld_type_name' ) . '",
			"url": "' . $this->options->get( 'wp_home_url') . '"';

			if ( ! empty( $this->profiles ) ) {
				$output .= '",sameAs": [' . $this->profiles . ']';
			}
			$output .= "}";

			/**
			 * Filter: 'lti_seo_json_ld_person' - Allows filtering of the JSON+LD person output
			 *
			 * @api string $output The person output
			 */
			$output = apply_filters( 'lti_seo_json_ld_person', $output );
		}

		return $output;
	}

	/**
	 * Outputs code to allow Google to recognize social profiles for use in the Knowledge graph
	 *
	 */
	public function json_entity() {
		$this->fetch_social_profiles();

		switch ( $this->options->get( 'jsonld_entity_type' ) ) {
			case 'organization':
				$output = $this->organization();
				break;
			case 'person':
				$output = $this->person();
				break;
		}
		if ( isset( $output ) ) {
			$this->json_ld_output( $output );
		}
	}

	/**
	 * Retrieve the social profiles to display in the organization output.
	 *
	 *
	 * @link  https://developers.google.com/webmasters/structured-data/customize/social-profiles
	 */
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
			if ( ! is_null( $this->options->get( $profile ) ) ) {
				$profiles[] = $this->options->get( $profile );
			}
		}

		if ( ! empty( $profiles ) ) {
			$profiles_out = '"' . implode( '","', $profiles ) . '"';
			$this->profiles = rtrim( $profiles_out, ',' );
		}
	}

	/**
	 * Outputs code to allow recognition of the internal search engine
	 *
	 *
	 * @link  https://developers.google.com/webmasters/structured-data/slsb-overview
	 */
	public function internal_search() {
		/**
		 * Filter: 'disable_lti_seo_json_ld_search' - Allow disabling of the json+ld output
		 *
		 * @api bool $display_search Whether or not to display json+ld search on the frontend
		 */
		if ( apply_filters( 'disable_lti_seo_json_ld_search', false ) === true ) {
			return;
		}
		$home_url = trailingslashit( $this->options->get( 'wp_home_url') );

		/**
		 * Filter: 'lti_seo_json_ld_search_url' - Allows filtering of the search URL for WP SEO
		 *
		 * @api string $search_url The search URL for this site with a `{search_term}` variable.
		 */
		$search_url = apply_filters( 'lti_seo_json_ld_search_url', $home_url . '?s={search_term}' );

		/**
		 * Filter: 'lti_seo_json_ld_search_output' - Allows filtering of the entire output of the function
		 *
		 * @api string $output The output of the function.
		 */
		$output = apply_filters( 'lti_seo_json_ld_search_output', sprintf('{ "@context": "http://schema.org",
			"@type": "WebSite",
			"url": "%s",
			"potentialAction": {
				"@type": "SearchAction",
				"target": "%s",
				"query-input": "required name=search_term"
				}
			}',$home_url, $search_url));

		$this->json_ld_output( $output );
	}

}
