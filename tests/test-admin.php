<?php

class AdminTest extends LTI_SEO_UnitTestCase {


	public function setUp() {
		parent::setUp();
	}

	public function testHim(){

	}

	/**
	 *
	 * @covers \Lti\Seo\Admin::get_supported_post_types
	 */
	public function testGetSupportedPostTypes() {
		$post_types = $this->instance->get_admin()->get_supported_post_types();
		$this->assertTrue( count( $post_types ) >= 2 );
	}
}