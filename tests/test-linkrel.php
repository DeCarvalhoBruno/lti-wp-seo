<?php

class LinkRelTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testLinkRelDisabled(){
		$link_rel = new \Lti\Seo\Generators\Frontpage_Link_Rel(new \Lti\Seo\Helpers\Wordpress_Helper(new \Lti\Seo\Plugin\Plugin_Settings((object)array())));
		ob_start();
		$link_rel->display_tags();
		$output = ob_get_clean();
		$this->assertEmpty($output);
	}


	public function testFrontpageLinkRel() {

		$settings = new \Lti\Seo\Plugin\Plugin_Settings((object)array());
		$settings->set( 'link_rel_canonical', true );
		$link_rel = new \Lti\Seo\Generators\Frontpage_Link_Rel(new \Lti\Seo\Helpers\Wordpress_Helper($settings));
		ob_start();
		$link_rel->display_tags();

		$output = ob_get_clean();
		$this->assertTrue($this->hasContent($output,'//link[@rel="canonical"][@href]'));
	}


}