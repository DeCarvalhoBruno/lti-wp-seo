<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\Frontpage_JSON_LD;
use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Test\Datatype\DOM;

class JSONLDTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testPerson() {

		$settings = new Plugin_Settings((object)array());
		$settings->set( 'jsonld_entity_type', 'person' );
		$personName = 'PersonTest';
		$settings->set( 'jsonld_type_name', $personName );
		$json_ld = new Frontpage_JSON_LD(new Wordpress_Helper($settings));
		ob_start();
		$json_ld->json_entity();
		$output = new DOM(ob_get_clean());

		$this->assertTrue($output->has('script[@type="application/ld+json"]'));
		$node = $output->get('script[@type="application/ld+json"]');
		$this->assertTrue($node instanceof \DOMNodeList && $node->length==1);

		$string = json_decode($node->item(0)->nodeValue);

		$this->assertEquals($string->{'@context'},'http://schema.org');
		$this->assertEquals($string->{'@type'},'Person');
		$this->assertEquals($string->url,home_url('/'));
		$this->assertEquals($string->name,$personName);

	}

	public function testWebsite(){
		$settings = new Plugin_Settings((object)array());
		$settings->set( 'jsonld_website_info', true );
		$json_ld = new Frontpage_JSON_LD(new Wordpress_Helper($settings));
		ob_start();
		$json_ld->website_tag();

		$output = new DOM(ob_get_clean());

		$this->assertTrue($output->has('script[@type="application/ld+json"]'));
		$node = $output->get('script[@type="application/ld+json"]');
		$this->assertTrue($node instanceof \DOMNodeList && $node->length==1);

		$string = json_decode($node->item(0)->nodeValue);

		$this->assertEquals($string->{'@context'},'http://schema.org');
		$this->assertEquals($string->{'@type'},'WebSite');
		$this->assertEquals($string->url,home_url('/'));

	}


}