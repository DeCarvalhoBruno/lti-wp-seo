<?php namespace Lti\Seo\Helpers;

interface ICanHelp {}

interface ICanHelpWithJSONLD extends ICanHelp{
	public function get_social_urls();
	public function get_schema_org_user($setting);
	public function get_schema_org_user_meta($setting);
	public function get_schema_org_setting($setting);
	public function get_schema_org_post($setting);
	public function get_schema_org_post_meta($setting);
	public function get_schema_org_general($setting);
	public function get_search_action_type();
	public function get_current_url();
	public function date_conversion($value);
	public function date_get_year($value);
	public function get_thumbnail_url();
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
