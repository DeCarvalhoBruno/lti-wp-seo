<?php namespace Lti\Seo\Generators;

use Lti\Seo\Wordpress_Helper;

/**
 * Interface CanMakeHeaderTags
 *
 *
 * @package Lti\Seo\Generators
 */
interface CanMakeHeaderTags {
	public function get_tags();

	public function make_tags();

	public function generate_tag( $name, $property, $content );
}

abstract class GenericTagMaker {

	protected $tags;

	protected $meta_tag_name_attribute = "name";

	public function __construct( Wordpress_Helper $helper ) {
		$this->helper = $helper;
		$this->tags   = $this->make_tags();
	}

	public function generate_tag( $name, $property, $content ) {
		return sprintf( '<meta %s="%s" content="%s" />' . PHP_EOL,$name, $property, $content );
	}


}