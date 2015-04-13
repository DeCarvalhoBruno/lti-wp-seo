<?php namespace Lti\Seo\Helpers;

interface ICanHelp {}

abstract class Helper {
	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	protected $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
	}
}
