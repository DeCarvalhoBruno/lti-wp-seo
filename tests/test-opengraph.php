<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\Frontpage_Open_Graph;
use Lti\Seo\Generators\Singular_Open_Graph;
use Lti\Seo\Helpers\LTI_SEO_Helper;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Test\Datatype\DOM;

class OpenGraphTest extends LTI_SEO_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}


	public function testFrontpage() {

		$settings   = new Plugin_Settings( (object) array() );
		$open_graph = new Frontpage_Open_Graph( new LTI_SEO_Helper( $settings ) );
		ob_start();
		$open_graph->display_tags();
		$out = new DOM( ob_get_clean() );

		$this->assertTrue( $out->hasTagWithContent( 'meta', 'property', 'og:type', 'content', 'website' ) );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'property', 'og:site_name', 'content',
			get_bloginfo( 'name' ) ) );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'property', 'og:url', 'content', home_url( '/' ) ) );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'property', 'og:description', 'content',
			get_bloginfo( 'description' ) ) );
		$this->assertTrue( $out->hasTag( 'meta', 'property', 'og:locale', 'content' ) );
	}

	public function testSingular() {


		$postID = $this->factory->post->create();
		$test_description = "Test description for a post";
		$this->set_post_box_values( $postID, array(
			'description' => $test_description
		) );
		$this->go_to( get_permalink( $postID ) );

		$settings   = new Plugin_Settings( (object) array() );
		$settings->set( 'description_support', true );
		$open_graph = new Singular_Open_Graph( new LTI_SEO_Helper( $settings ) );
		ob_start();
		$open_graph->display_tags();
		$out = new DOM( ob_get_clean() );

		$this->assertTrue( $out->hasTagWithContent( 'meta', 'property', 'og:type', 'content', 'article' ) );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'property', 'og:site_name', 'content',
			get_bloginfo( 'name' ) ) );
		$this->assertTrue( $out->hasTag( 'meta', 'property', 'og:title', 'content' ) );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'property', 'og:url', 'content',
			get_permalink( $postID ) ) );
		$this->assertTrue( $out->hasTag( 'meta', 'property', 'og:locale', 'content' ) );
		$this->assertTrue( $out->hasTagWithContent( 'meta', 'property', 'og:description', 'content',
			$test_description ) );
		$this->assertTrue( $out->hasTag( 'meta', 'property', 'article:published_time', 'content' ) );
		$this->assertTrue( $out->hasTag( 'meta', 'property', 'article:modified_time', 'content' ) );
	}

}