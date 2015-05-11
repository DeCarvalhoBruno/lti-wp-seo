<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\Frontpage_Description;
use Lti\Seo\Generators\Singular_Description;
use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Plugin\Postbox_Values;
use Lti\Seo\Test\Datatype\DOM;

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

		$settings = new Plugin_Settings((object)array());
		$settings->set( 'frontpage_description', true );
		$frontpage_description = "This is a test frontpage description";
		$settings->set( 'frontpage_description_text', $frontpage_description );
		$description = new Frontpage_Description(new Wordpress_Helper($settings));
		ob_start();
		$description->display_tags();

		$out = new DOM( ob_get_clean() );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'name', 'description', 'content', $frontpage_description ) );

	}

	public function testGlobalDescription(){
		$settings = new Plugin_Settings((object)array());
		$settings->set( 'description_support', true );

		$test_description = "Test description for a post";
		$postID = $this->factory->post->create();
		$postbox_settings = array(
			'description' => $test_description
		);
		update_post_meta( $postID, 'lti_seo', new Postbox_Values( (object) $postbox_settings ) );
		$this->go_to(get_permalink($postID));

		$description = new Singular_Description(new Wordpress_Helper($settings));
		ob_start();
		$description->display_tags();

		$out = new DOM( ob_get_clean() );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'name', 'description', 'content', $test_description ) );
	}

	public function testDeactivatedDescription(){
		$settings = new Plugin_Settings((object)array());

		$test_description = "Test description for a post";
		$postID = $this->factory->post->create();
		$postbox_settings = array(
			'description' => $test_description
		);
		update_post_meta( $postID, 'lti_seo', new Postbox_Values( (object) $postbox_settings ) );
		$this->go_to(get_permalink($postID));

		$description = new Singular_Description(new Wordpress_Helper($settings));
		ob_start();
		$description->display_tags();

		$out = new DOM( ob_get_clean() );
		$this->assertTrue( $out->has_no_output() );
	}


}