<?php

use Lti\Seo\LTI_SEO;
use Lti\Seo\Activator;
use Lti\Seo\Admin;

class AdminTest extends WP_UnitTestCase {

	/**
	 * @var LTI_SEO
	 */
	private $instance;
	/**
	 * @var Lti\Seo\Admin
	 */
	private $admin;

	public function setUp() {
		$this->instance = LTI_SEO::get_instance();
		$this->admin = new Admin( $this->instance->get_plugin_name(), $this->instance->get_version(), $this->instance->get_settings(),dirname( plugin_dir_path( __FILE__ ) ) );
	}

	public function testInit() {
		$this->instance->run();
		$this->assertTrue( true );
	}

	public function testActivation() {
		require_once plugin_dir_path( __FILE__ ) . '../src/lti/seo/activator.php';
		Activator::activate();
		$stored_options = get_option( "lti_seo_options" );
		$this->assertInstanceOf( "Lti\\Seo\\Settings", $stored_options );
	}

	public function testGetSupportedPostTypes(){
		$post_types = $this->admin->get_supported_post_types();
		$this->assertTrue( count($post_types)>=2 );
	}


}

