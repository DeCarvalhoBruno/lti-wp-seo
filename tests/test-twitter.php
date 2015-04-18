<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\Frontpage_Twitter_Card;
use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Test\Datatype\DOM;

class TwitterTest extends LTI_SEO_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testFrontpageTwitter() {

		$settings = new Plugin_Settings( (object) array() );
		$twitter  = new Frontpage_Twitter_Card( new \Lti\Seo\Helpers\Wordpress_Helper( $settings ) );
		ob_start();
		$twitter->display_tags();
		$out = new DOM( ob_get_clean() );

		$this->assertTrue( $out->hasTagWithContent( 'meta', 'name', 'twitter:card', 'content', 'summary' ) );
		$this->assertTrue( $out->hasTag( 'meta', 'name', 'twitter:title', 'content' ) );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'name', 'twitter:url', 'content', home_url( '/' ) ) );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'name', 'twitter:description', 'content',
			get_bloginfo( 'description' ) ) );


		/*
		 <meta name="twitter:card" content="summary" />
		<meta name="twitter:title" content="Test Blog | Just another WordPress site" />
		<meta name="twitter:url" content="" />
		<meta name="twitter:description" content="Just another WordPress site" />
		*/
	}

	public function testLargeImageSetting(){
		$settings = new Plugin_Settings( (object) array() );
		$settings->set( 'twitter_card_type', 'summary_large_image' );
		$twitter  = new Frontpage_Twitter_Card( new Wordpress_Helper( $settings ) );
		ob_start();
		$twitter->display_tags();
		$out = new DOM( ob_get_clean() );

		$this->assertTrue( $out->hasTagWithContent( 'meta', 'name', 'twitter:card', 'content', 'summary_large_image' ) );
	}


}