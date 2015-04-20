<?php namespace Lti\Seo\Plugin;

class Defaults {
	public $values;

	public function __construct() {
		$this->values = array(
			new def( "version", 'Text', LTI_SEO_VERSION ),
			new def( 'link_rel_support', 'Checkbox' ),
			new def( 'link_rel_canonical', 'Checkbox' ),
			new def( 'link_rel_author', 'Checkbox' ),
			new def( 'link_rel_publisher', 'Checkbox' ),
			new def( 'keyword_support', 'Checkbox'),
			new def( 'keyword_tag_based', 'Checkbox' ),
			new def( 'keyword_cat_based', 'Checkbox' ),
			new def( 'robot_support', 'Checkbox' ),
			new def( 'post_robot_noindex', 'Checkbox', false, true ),
			new def( 'post_robot_nofollow', 'Checkbox', false, true ),
			new def( 'post_robot_noodp', 'Checkbox', false, true ),
			new def( 'post_robot_noydir', 'Checkbox', false, true ),
			new def( 'post_robot_noarchive', 'Checkbox', false, true ),
			new def( 'post_robot_nosnippet', 'Checkbox', false, true ),
			new def( 'robot_noindex', 'Checkbox' ),
			new def( 'robot_nofollow', 'Checkbox' ),
			new def( 'robot_noodp', 'Checkbox' ),
			new def( 'robot_noydir', 'Checkbox' ),
			new def( 'robot_noarchive', 'Checkbox' ),
			new def( 'robot_nosnippet', 'Checkbox' ),
			new def( 'robot_date_based', 'Checkbox' ),
			new def( 'robot_cat_based', 'Checkbox' ),
			new def( 'robot_tag_based', 'Checkbox' ),
			new def( 'robot_tax_based', 'Checkbox' ),
			new def( 'robot_author_based', 'Checkbox' ),
			new def( 'robot_search_based', 'Checkbox' ),
			new def( 'robot_notfound_based', 'Checkbox' ),
			new def( 'description_support', 'Checkbox' ),
			new def( 'open_graph_support', 'Checkbox' ),
			new def( 'facebook_publisher', 'Url' ),
			new def( 'frontpage_description', 'Checkbox' ),
			new def( 'frontpage_description_text', 'Text' ),
			new def( 'frontpage_robot', 'Checkbox' ),
			new def( 'frontpage_robot_noindex', 'Checkbox' ),
			new def( 'frontpage_robot_nofollow', 'Checkbox' ),
			new def( 'frontpage_robot_noodp', 'Checkbox' ),
			new def( 'frontpage_robot_noydir', 'Checkbox' ),
			new def( 'frontpage_robot_noarchive', 'Checkbox' ),
			new def( 'frontpage_robot_nosnippet', 'Checkbox' ),
			new def( 'frontpage_keyword', 'Checkbox' ),
			new def( 'frontpage_keyword_text', 'Text' ),
			new def( 'frontpage_social_img_url', 'Url' ),
			new def( 'frontpage_social_img_id', 'Text' ),
			new def( 'jsonld_website_info', 'Checkbox' ),
			new def( 'jsonld_org_info', 'Checkbox'),
			new def(
					'jsonld_entity_type',
					'Radio',
					array( 'default' => 'person', 'choice' => array( 'person', 'organization' ) )
			),
			new def( 'jsonld_type_name', 'Text' ),
			new def( 'jsonld_type_logo_url', 'Url' ),
			new def( 'jsonld_type_logo_id', 'Text' ),
			new def( 'twitter_card_support', 'Checkbox' ),
			new def( 'twitter_publisher', 'Text' ),
			new def(
				'twitter_card_type',
				'Radio',
				array( 'default' => 'summary', 'choice' => array( 'summary', 'summary_large_image' ) )
			),
			new def( 'gplus_publisher', 'Url' ),
			new def( 'account_facebook', 'Url' ),
			new def( 'account_twitter', 'Url' ),
			new def( 'account_gplus', 'Url' ),
			new def( 'account_instagram', 'Url' ),
			new def( 'account_youtube', 'Url' ),
			new def( 'account_linkedin', 'Url' ),
			new def( 'account_myspace', 'Url' ),
		);
	}
}

class def {

	public $name;
	public $type;
	public $default_value;
	public $impacts_user_settings;

	public function __construct( $name, $type, $default_value = null, $impacts_user_settings = false ) {
		$this->name                  = $name;
		$this->type                  = __NAMESPACE__ . "\\Field_" . $type;
		$this->default_value         = $default_value;
		$this->impacts_user_settings = $impacts_user_settings;
	}
}

class Plugin_Settings {
	public function __construct( \stdClass $settings = null ) {

		$defaults = new Defaults();

		/**
		 * @var def $value
		 */
		foreach ( $defaults->values as $value ) {
			$storedValue = null;
			if ( isset( $settings->{$value->name} ) ) {
				$storedValue = $settings->{$value->name};
			}
			$className = $value->type;

			if ( ! is_null( $value->default_value ) ) {
				$this->{$value->name} = new $className( $storedValue, $value->default_value,
					$value->impacts_user_settings );
			} else {
				$this->{$value->name} = new $className( $storedValue, null, $value->impacts_user_settings );
			}
		}
	}

	public static function get_defaults() {
		return new self();
	}

	public function save( Array $values = array() ) {
		return new Plugin_Settings( (object) $values );
	}

	public function get( $value ) {
		if ( isset( $this->{$value} ) && ! empty( $this->{$value}->value ) && ! is_null( $this->{$value}->value ) ) {
			return $this->{$value}->value;
		}

		return null;
	}

	public function set( $key, $value, $type = "Text" ) {
		$className    = __NAMESPACE__ . "\\Field_" . $type;
		$this->{$key} = new $className( $value );
	}

	public function compare( $values ) {
		$changed       = array();
		$currentValues = get_object_vars( $this );
		$oldValues     = get_object_vars( $values );

		foreach ( $currentValues as $key => $value ) {
			if ( $value->isTracked ) {
				if ( isset( $oldValues[ $key ] ) && $oldValues[ $key ]->value != $value->value ) {
					$changed[ $key ] = $value->value;
				}
			}
		}

		return $changed;

	}
}

