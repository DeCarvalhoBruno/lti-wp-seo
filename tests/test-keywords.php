<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\Frontpage_Keyword;
use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;

class KeywordsTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testKeywordsDisabled(){
		$keywords = new Frontpage_Keyword(new Wordpress_Helper(new Plugin_Settings((object)array())));
		ob_start();
		$keywords->display_tags();
		$output = ob_get_clean();
		$this->assertEmpty($output);
	}

	public function testFrontpageKeywords() {

		$settings = new Plugin_Settings((object)array());
		$settings->set( 'frontpage_keyword', true );
		$testString = "these,are,test,keywords";
		$settings->set( 'frontpage_keyword_text', $testString );
		$keywords = new Frontpage_Keyword(new Wordpress_Helper($settings));
		ob_start();
		$keywords->display_tags();

		$output = ob_get_clean();
		$this->assertTrue($this->hasContent($output,sprintf('//meta[@name="keywords"][@content="%s"]',$testString)));
	}

}