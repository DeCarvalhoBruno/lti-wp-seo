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
		array( 'frontpage_description_text', 'Text', false ),

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
		return new Settings((object)$values);
	}

	public function value($value){
		if(isset($this->{$value})){
			return $this->{$value}->value;
		}
		return null;
	}
}

abstract class Fields {
	public function __construct( $value, $default ) {
		if ( $value ) {
			$this->value = $value;
		} else {
			$this->value = $default;
		}
	}
}

class Field_Checkbox extends Fields {
	public function __construct( $value, $default ) {
		$this->value = ( $value === true || (int) $value === 1 || $value === "true" || $value === 'on' ) ? true : false;
	}
}

class Field_Radio extends Fields {

	public function __construct( $value, $default ) {
		if ( $value ) {
			if ( isset( $default[ $value ] ) ) {
				$this->value = $default[ $value ];
			} else {
				$fallbackValue = array_flip( $default );
				if ( ! empty( $fallbackValue ) ) {
					$this->value = $fallbackValue["default"];
				} else {
					$this->value = key( $default );
				}
			}
		} else {
			$fallbackValue = array_flip( $default );
			if ( isset( $fallbackValue["default"] ) ) {
				$this->value = $fallbackValue["default"];
			} else {
				$this->value = key( $default );
			}
		}
	}
}

class Field_Text extends Fields {

}
