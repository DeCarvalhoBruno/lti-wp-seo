<?php namespace Lti\Seo;


class Defaults {
	public $values = array(
		array( "version", 'Text', '1.0.0' ),
		array( 'sitewide_keywords', 'Text', "" ),
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

	public function __get( $name ) {
		if ( isset( $this->$name ) ) {
			return $this->$name;
		}

		return null;
	}

	public static function get_defaults() {
		return new static();
	}

	public function save( Array $values = array() ) {
		return new Settings((object)$values);
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
