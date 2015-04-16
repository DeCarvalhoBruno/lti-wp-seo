<?php

use Lti\Seo\LTI_SEO;
use Lti\Seo\Admin;
use Lti\Seo\Frontend;

class PostTest extends WP_UnitTestCase {

	/**
	 * @var LTI_SEO
	 */
	private $instance;
	/**
	 * @var Lti\Seo\Admin
	 */
	private $admin;
	/**
	 * @var \Lti\Seo\Frontend
	 */
	private $frontend;
	/**
	 * @var \WP_UnitTest_Factory_For_Post
	 */
	private $post;

	private $postID;

	public function setUp() {
		$this->instance = LTI_SEO::get_instance();
		$this->admin    = new Admin( $this->instance->get_plugin_name(), $this->instance->get_version(),
			$this->instance->get_settings(), dirname( plugin_dir_path( __FILE__ ) ), $this->instance->get_helper() );
		$this->frontend = new Frontend( $this->instance->get_plugin_name(), $this->instance->get_version(),
			$this->instance->get_settings(), $this->instance->get_helper() );
		$this->post = new WP_UnitTest_Factory_For_Post();
		$this->postID =  $this->post->create_object(array("post_title"=>'test title', "post_content"=>"content"));
	}

	public function testPost(){
		$f = $this->post->get_object_by_id($this->postID);
		//$this->go_to("http://example.org/?p=".$this->postID);
		//print_r(array_keys($GLOBALS));
		//print_r($_REQUEST);
	}



}

