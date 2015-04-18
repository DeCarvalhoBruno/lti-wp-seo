<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\Frontpage_Link_Rel;
use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;

class LinkRelTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testLinkRelDisabled(){
		$link_rel = new Frontpage_Link_Rel(new Wordpress_Helper(new Plugin_Settings((object)array())));
		ob_start();
		$link_rel->display_tags();
		$output = ob_get_clean();
		$this->assertEmpty($output);
	}


	public function testFrontpageLinkRel() {

		$settings = new Plugin_Settings((object)array());
		$settings->set( 'link_rel_canonical', true );
		$link_rel = new Frontpage_Link_Rel(new Wordpress_Helper($settings));
		ob_start();
		$link_rel->display_tags();

		$output = ob_get_clean();
		$this->assertTrue($this->hasContent($output,'//link[@rel="canonical"][@href]'));
	}


}