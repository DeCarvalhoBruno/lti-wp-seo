<?php namespace Lti\Seo\Plugin;

abstract class Fields {
	public function __construct( $value, $default = "" ) {
		if ( $value ) {
			$this->value = sanitize_text_field($value);
		} else {
			$this->value = $default;
		}
	}
}

class Field_Checkbox extends Fields {
	public function __construct( $value, $default = false ) {
		$this->value = ( $value === true || (int) $value === 1 || $value === "true" || $value === 'on' ) ? true : $default;
	}
}

class Field_Radio extends Fields {

	public function __construct( $value, $default = "" ) {
		if ( is_array( $default ) ) {
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
		} else {
			$this->value = null;
		}
	}
}

class Field_Text extends Fields {
}

class Field_String extends Fields {

}

class Field_Url extends Fields {
	public function __construct( $value, $default = "" ) {
		if ( $value && ! filter_var( $value, FILTER_VALIDATE_URL ) === false ) {
			$this->value = $value;
		} else {
			$this->value = $default;
		}
	}

}
