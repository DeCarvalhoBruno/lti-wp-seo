<?php

class KeywordsTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testKeywordsDisabled(){
		$keywords = new \Lti\Seo\Generators\Frontpage_Keyword(new \Lti\Seo\Helpers\Wordpress_Helper(new \Lti\Seo\Plugin\Plugin_Settings((object)array())));
		ob_start();
		$keywords->display_tags();
		$output = ob_get_clean();
		$this->assertEmpty($output);
	}


	public function testFrontpageKeywords() {

		$settings = new \Lti\Seo\Plugin\Plugin_Settings((object)array());
		$settings->set( 'frontpage_keyword', true );
		$testString = "these,are,test,keywords";
		$settings->set( 'frontpage_keyword_text', $testString );
		$keywords = new \Lti\Seo\Generators\Frontpage_Keyword(new \Lti\Seo\Helpers\Wordpress_Helper($settings));
		ob_start();
		$keywords->display_tags();

		$output = ob_get_clean();
		$this->assertTrue($this->hasContent($output,sprintf('//meta[@name="keywords"][@content="%s"]',$testString)));
	}


}