<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\JSON_LD;
use Lti\Seo\Helpers\LTI_SEO_Helper;
use Lti\Seo\Helpers\Wordpress_Helper_JSONLD;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Test\Datatype\DOM;

class JSONLDTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}

	public function testOrganization() {

		$settings = new Plugin_Settings((object)array());
		$settings->set( 'jsonld_entity_support', true );
		$settings->set( 'jsonld_entity_type', 'Organization' );
        $orgName = 'OrgTest';
		$settings->set( 'jsonld_org_name', $orgName );
		$main_helper = new LTI_SEO_Helper($settings);
		$main_helper->init();
		$helper = new Wordpress_Helper_JSONLD($main_helper);
		$json_ld = new JSON_LD($helper);
		ob_start();
		$json_ld->make_Organization();

		$output = new DOM(ob_get_clean());

		$this->assertTrue($output->has('script[@type="application/ld+json"]'));
		$node = $output->get('script[@type="application/ld+json"]');
		$this->assertTrue($node instanceof \DOMNodeList && $node->length==1);

		$string = json_decode($node->item(0)->nodeValue);

		$this->assertEquals($string->{'@context'},'http://schema.org');
		$this->assertEquals($string->{'@type'},'Organization');
		$this->assertEquals($string->name,$orgName);

	}

	public function testWebsite(){
		$settings = new Plugin_Settings((object)array());
		$settings->set( 'jsonld_website_info', true );
		$main_helper = new LTI_SEO_Helper($settings);
		$main_helper->init();
		$helper = new Wordpress_Helper_JSONLD($main_helper);
		$json_ld = new JSON_LD($helper);
		ob_start();
		$json_ld->make_WebSite();

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