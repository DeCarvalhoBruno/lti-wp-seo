<?php namespace Lti\Seo\Test;


use Lti\Seo\Generators\Frontpage_Robot;
use Lti\Seo\Generators\Singular_Robot;
use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Plugin\Postbox_Values;

class RobotsTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testRobotsDisabled(){
		$robots = new Frontpage_Robot(new Wordpress_Helper(new Plugin_Settings((object)array())));
		ob_start();
		$robots->display_tags();
		$output = ob_get_clean();
		$this->assertEmpty($output);
	}


	public function testFrontpageRobots() {

		$settings = new Plugin_Settings((object)array());
		$settings->set( 'frontpage_robot', true );
		$settings->set( 'frontpage_robot_noindex', true );
		$settings->set( 'frontpage_robot_nofollow', true );
		$settings->set( 'frontpage_robot_noodp', true );
		$settings->set( 'frontpage_robot_noydir', true );
		$settings->set( 'frontpage_robot_noarchive', true );
		$settings->set( 'frontpage_robot_nosnippet', true );

		$robots = new Frontpage_Robot(new Wordpress_Helper($settings));
		ob_start();
		$robots->display_tags();
		$output = ob_get_clean();
		$this->assertTrue($this->hasContent($output,'//meta[@name="robots"][@content="noindex,nofollow,noodp,noydir,noarchive,nosnippet"]'));
	}

	public function testPostRobots() {
		$postID = $this->factory->post->create();

		$postbox_settings = array(
			'post_robot_noindex'  => true,
			'post_robot_nofollow' => true,
			'post_robot_noodp' => false,
			'post_robot_noydir' => false,
			'post_robot_noarchive' => true,
			'post_robot_nosnippet' => true,
		);
		update_post_meta( $postID, 'lti_seo', new Postbox_Values( (object) $postbox_settings ) );

		$this->go_to(get_permalink($postID));
		$helper = new Wordpress_Helper(new Plugin_Settings(new \stdClass()));
		$helper->init();
		$robots = new Singular_Robot($helper);
		ob_start();
		$robots->display_tags();
		$output = ob_get_clean();
		$this->assertTrue($this->hasContent($output,'//meta[@name="robots"][@content="noindex,nofollow,noarchive,nosnippet"]'));
	}


}