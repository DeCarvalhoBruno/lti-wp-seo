<?php namespace Lti\Seo;

class Settings {

	private $version = "1.0.0";
	private $site_description = "";

	public function __construct(Settings $settings = null ) {
		if ( $settings ) {
			foreach ( $settings as $setting => $value ) {
				$this->{$setting} = $value;
			}
		}
	}

	public function __get( $name ) {
		if ( isset( $this->$name ) ) {
			return $this->$name;
		}
		return null;
	}

	public function __set( $name, $value ) {
		if ( ! isset( $this->$name ) ) {
			throw new \Exception( "Trying to set unrecognized value " . $name );
		}
	}

	public static function get_defaults() {
		return new static();
	}
}

