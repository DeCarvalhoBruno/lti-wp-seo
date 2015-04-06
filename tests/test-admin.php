<?php

use Lti\Seo\LTI_SEO;
use Lti\Seo\Activator;

class AdminTest extends WP_UnitTestCase {

    /**
     * @var LTI_SEO
     */
    private $instance;
    private $admin;

    function setUp(){
        $this->instance = LTI_SEO::getInstance();
    }

	function testInit() {
		$this->instance->run();
		$this->assertTrue(true);
//        $this->instance ->admin_init();
//        $admin = $this->instance->getAdmin();
//        $options = $admin->getOptions();
//		$this->assertTrue(isset($options->version));
	}

	function testActivation(){
		require_once plugin_dir_path( __FILE__ ) . '../src/lti/seo/activator.php';
		Activator::activate();
	}
}

