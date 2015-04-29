<?php namespace Lti\Seo\Test;

use Lti\Seo\Deactivator;
use Lti\Seo\Activator;

class GeneralTest extends LTI_SEO_UnitTestCase {

	/**
	 *
	 * @covers \Lti\Seo\Activator::activate
	 */
	public function testActivation() {
		require_once plugin_dir_path( __FILE__ ) . '../src/activator.php';
		Activator::activate();
		$stored_options = get_option( "lti_seo_options" );
		$this->assertInstanceOf( "Lti\\Seo\\Plugin\\Plugin_Settings", $stored_options );
	}

	/**
	 *
	 * @covers \Lti\Seo\Deactivator::deactivate
	 */
	public function testDeactivation() {
		require_once plugin_dir_path( __FILE__ ) . '../src/deactivator.php';
		Deactivator::deactivate();
	}
}

