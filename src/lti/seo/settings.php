<?php namespace Lti\Seo;


class Defaults {
	public $values = array(
		array( "version", 'Text', '1.0.0' ),
		array( 'global_keywords', 'Text', "" ),
		array( 'canonical_urls', 'Checkbox', false ),
		array( 'keyword_cat', 'Checkbox', false ),
		array( 'keyword_tag', 'Checkbox', false ),
		array( 'meta_description', 'Checkbox', false ),
		array( 'open_graph', 'Checkbox', false ),
		array( 'frontpage_description', 'Checkbox', false ),
		array( 'frontpage_description_text', 'Text', "" ),
		array( 'jsonld_website_info', 'Checkbox', false ),
		array( 'jsonld_org_info', 'Checkbox', false ),
		array(
			'jsonld_entity_type',
			'Radio',
			array( 'default' => 'person', 'choice' => array( 'person', 'organization' ) )
		),
		array( 'jsonld_type_name', 'Text', "" ),
		array( 'jsonld_type_logo_url', 'Url', "" ),
		array( 'account_facebook', 'Url', "" ),
		array( 'account_twitter', 'Url', "" ),
		array( 'account_gplus', 'Url', "" ),
		array( 'account_instagram', 'Url', "" ),
		array( 'account_youtube', 'Url', "" ),
		array( 'account_linkedin', 'Url', "" ),
		array( 'account_myspace', 'Url', "" ),
		//array( 'checkbox_type', 'Checkbox', false ),
		//array( 'opt_type', 'Radio', array( '1', '2' => 'default', '3' ) )
	);
}

class Settings {
	public function __construct( $settings = array() ) {

		$defaults = new Defaults();

		foreach ( $defaults->values as $value ) {
			$storedValue = null;
			if ( isset( $settings->{$value[0]} ) && ! is_object( $settings->{$value[0]} ) ) {
				$storedValue = $settings->{$value[0]};
			}
			$className         = "Lti\\Seo\\Field_" . $value[1];
			$this->{$value[0]} = new $className( $storedValue, $value[2] );
		}
	}

	public static function get_defaults() {
		return new static();
	}

	public function save( Array $values = array() ) {
		return new Settings( (object) $values );
	}

	public function value( $value ) {
		if ( isset( $this->{$value} ) && ! empty( $this->{$value}->value ) && ! is_null( $this->{$value}->value ) ) {
			return $this->{$value}->value;
		}

		return null;
	}
}

abstract class Fields {
	public function __construct( $value, $default ) {
		if ( $value ) {
			$this->value = wp_check_invalid_utf8( $value );
		} else {
			$this->value = $default;
		}
	}
}

class Field_Checkbox extends Fields {
	public function __construct( $value, $default ) {
		$this->value = ( $value === true || (int) $value === 1 || $value === "true" || $value === 'on' ) ? true : $default;
	}
}

class Field_Radio extends Fields {

	public function __construct( $value, $default ) {
		$defaults = array_flip( $default['choice'] );
		if ( $value ) {
			if ( isset( $defaults[ $value ] ) ) {
				$this->value = $value;
			} else {
				$this->value = $default['default'];
			}
		} else {
			$this->value = $default['default'];
		}
	}
}

class Field_Text extends Fields {

}

class Field_Url extends Fields {
	public function __construct( $value, $default ) {
		if ( $value && ! filter_var( $value, FILTER_VALIDATE_URL ) === false ) {
			$this->value = $value;
		} else {
			$this->value = $default;
		}
	}

}
