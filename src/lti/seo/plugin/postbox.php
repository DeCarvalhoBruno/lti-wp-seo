<?php namespace Lti\Seo\Plugin;

class Postbox_Fields {
	public $values = array(
		array('description','Text'),
		array('keywords','Text')
	);
}

class Postbox_Values {
	public function __construct( $form ) {

		if ( is_object( $form ) ) {
			$postbox = new Postbox_Fields();

			foreach ( $postbox->values as $value ) {
				$storedValue = null;
				if ( isset( $form->{$value[0]} ) ) {
					$storedValue = $form->{$value[0]};
				}
				$className = __NAMESPACE__."\\Field_" . $value[1];

				$this->{$value[0]} = new $className( $storedValue );
			}
		}
	}

	public function get( $value ) {
		if ( isset( $this->{$value} ) && ! empty( $this->{$value}->value ) && ! is_null( $this->{$value}->value ) ) {
			return $this->{$value}->value;
		}

		return null;
	}

	public function set( $key, $value, $type = "Text" ) {
		$className    = __NAMESPACE__ . $type;
		$this->{$key} = new $className( $value );
	}
}
