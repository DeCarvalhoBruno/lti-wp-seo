<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\Frontpage_Description;
use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;

class DescriptionTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testDescriptionDisabled(){
		$description = new Frontpage_Description(new Wordpress_Helper(new Plugin_Settings((object)array())));
		ob_start();
		$description->display_tags();
		$output = ob_get_clean();
		$this->assertEmpty($output);
	}


	public function testFrontpageDescription() {

		$settings = new \Lti\Seo\Plugin\Plugin_Settings((object)array());
		$settings->set( 'frontpage_description', true );
		$settings->set( 'frontpage_description_text', "This is a test frontpage description" );
		$description = new \Lti\Seo\Generators\Frontpage_Description(new \Lti\Seo\Helpers\Wordpress_Helper($settings));
		ob_start();
		$description->display_tags();

		$output = ob_get_clean();
		$this->assertTrue($this->hasContent($output,'//meta[@name="description"][@content="This is a test frontpage description"]'));
	}


}