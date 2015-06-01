<?php namespace Lti\Seo\Test;

use Lti\Seo\Admin_Google;
use Lti\Seo\Helpers\LTI_SEO_Helper;
use Lti\Seo\Plugin\Plugin_Settings;

class GoogleTest extends LTI_SEO_UnitTestCase {

	public function setUp() {
        parent::setUp();
        $this->admin = $this->instance->get_admin();
	}

	public function testAdminGoogle() {

		$settings = new Plugin_Settings( (object) array() );

        $google = new Admin_Google( $this->admin, new LTI_SEO_Helper( $settings ) );
		$this->assertInstanceOf('Lti\Google\Google_Helper',$google->get_helper());

    }


}