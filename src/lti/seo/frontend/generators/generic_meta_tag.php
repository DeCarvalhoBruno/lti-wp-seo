<?php namespace Lti\Seo\Generators;

use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Plugin_Settings;


interface ICanMakeHeaderTags {
	public function display_tags();

	public function make_tags();

	public function generate_tag( $name, $property, $content );
}

abstract class GenericMetaTag {

	protected $tags;

	protected $post_id;

	protected $meta_tag_name_attribute = "name";

	protected $image_retrieval_mode = "fallback";

	protected $number_images = -1;

	/**
	 * @var ICanHelp|\Lti\Seo\Helpers\Wordpress_Helper
	 */
	protected $helper;

	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	protected $settings;

	/**
	 * @param ICanHelp $helper
	 * @param Plugin_Settings $settings
	 */
	public function __construct( ICanHelp $helper, Plugin_Settings $settings, $post_id = null ) {
		$this->helper = $helper;
		$this->settings = $settings;
		$this->tags   = $this->make_tags();
		$this->post_id = $post_id;
	}

	public function generate_tag( $name, $property, $content ) {
		return sprintf( '<meta %s="%s" content="%s" />' . PHP_EOL,$name, $property, $content );
	}

	public function get_tags(){
		return $this->tags;
	}


}