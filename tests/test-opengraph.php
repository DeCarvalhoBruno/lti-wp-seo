<?php namespace Lti\Seo\Test;

use Lti\Seo\Generators\Frontpage_Open_Graph;
use Lti\Seo\Helpers\Wordpress_Helper;
use Lti\Seo\Plugin\Plugin_Settings;
use Lti\Seo\Test\Datatype\DOM;

class OpenGraphTest extends LTI_SEO_UnitTestCase {

	public function setUp(){
		parent::setUp();
		$this->go_to( home_url( '/' ) );
	}


	public function testFrontpageOpenGraph() {

		$settings = new Plugin_Settings((object)array());
		$open_graph = new Frontpage_Open_Graph(new Wordpress_Helper($settings));
		ob_start();
		$open_graph->display_tags();
		$out = new DOM(ob_get_clean());

		//$out->get();

		$this->assertTrue($out->hasTagWithContent('meta','property','og:type','content','website'));
		$this->assertTrue($out->hasTagWithContent('meta','property','og:site_name','content',get_bloginfo( 'name' )));
		$this->assertTrue($out->hasTag('meta','property','og:title','content'));
		$this->assertTrue($out->hasTagWithContent('meta','property','og:url','content',home_url('/')));
		$this->assertTrue($out->hasTagWithContent('meta','property','og:description','content',get_bloginfo( 'description' )));
		$this->assertTrue($out->hasTag('meta','property','og:locale','content'));
	}

/*
<meta property="og:type" content="website" />
<meta property="og:site_name" content="Test Blog" />
<meta property="og:title" content="Test Blog | Just another WordPress site" />
<meta property="og:url" content="" />
<meta property="og:description" content="Just another WordPress site" />
<meta property="og:locale" content="en-US" />
*/

}