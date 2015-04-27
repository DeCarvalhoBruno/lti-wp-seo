<?php namespace Lti\Seo\Helpers;

interface ICanHelp {}

interface ICanHelpWithJSONLD extends ICanHelp{
	public function get_social_urls();
	public function get_schema_org_name();
	public function get_schema_org_logo();
	public function get_schema_org_alternate_name();
	public function get_search_action_type();
	public function get_schema_org_website();
	public function get_current_url();
}

abstract class Helper {
	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	protected $settings;

	public function __construct( $settings ) {
		$this->settings = $settings;
	}

	public function get_settings(){
		return $this->settings;
	}
}
