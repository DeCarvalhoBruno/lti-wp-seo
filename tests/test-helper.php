<?php namespace Lti\Seo\Test;

use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;

class HelperTest extends LTI_SEO_UnitTestCase {
	/**
	 * @var \Lti\Seo\Helpers\Wordpress_Helper
	 */
	private $helper;

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
		$this->helper = new Wordpress_Helper(new Plugin_Settings((object)array()));
	}

	public function testInit(){
		$this->assertInstanceOf("Lti\\Seo\\Plugin\\Plugin_Settings",$this->helper->get_settings());
	}

	public function testGetValue(){
		$this->assertEquals(LTI_SEO_VERSION,$this->helper->get('version'));
	}






}