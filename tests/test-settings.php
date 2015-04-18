<?php namespace Lti\Seo\Test;

use Lti\Seo\Plugin\Plugin_Settings;

class SettingsTest extends LTI_SEO_UnitTestCase {

	public function testSettings() {
		$settings = $this->instance->get_settings();
		$this->assertInstanceOf( "Lti\\Seo\\Plugin\\Plugin_Settings", $this->instance->get_settings() );

		foreach ( $settings as $setting ) {
			$this->assertInstanceOf( "Lti\\Seo\\Plugin\\Fields", $setting );
		}
	}

	/**
	 *
	 * @covers \Lti\Seo\Plugin\Plugin_Settings::get
	 * @covers \Lti\Seo\Plugin\Plugin_Settings::set
	 */
	public function testGetSet(){
		$settings = $this->instance->get_settings();
		$settings->set('key','test_value','Text');
		$this->assertEquals('test_value',$settings->get('key'));
	}

	/**
	 *
	 * @covers \Lti\Seo\Plugin\Plugin_Settings::set
	 */
	public function testCheckbox(){
		$settings = $this->instance->get_settings();
		$settings->set('chk1',true,'Checkbox');
		$settings->set('chk2',false,'Checkbox');
		$settings->set('chk3',"string",'Checkbox');
		$settings->set('chk4',"true",'Checkbox');
		$settings->set('chk5',"false",'Checkbox');

		$this->assertEquals(true,$settings->get('chk1'));
		$this->assertEquals(false,$settings->get('chk2'));
		$this->assertEquals(false,$settings->get('chk3'));
		$this->assertEquals(true,$settings->get('chk4'));
		$this->assertEquals(false,$settings->get('chk5'));
	}

	/**
	 *
	 * @covers \Lti\Seo\Plugin\Plugin_Settings::get_defaults
	 * @covers \Lti\Seo\Plugin\Plugin_Settings::__construct
	 *
	 */
	public function testDefaults(){
		$test = new Plugin_Settings();
		$this->assertInstanceOf( "Lti\\Seo\\Plugin\\Plugin_Settings", $test );

		$test2 = Plugin_Settings::get_defaults();
		$this->assertInstanceOf( "Lti\\Seo\\Plugin\\Plugin_Settings", $test2 );
	}

	/**
	 *
	 * @covers \Lti\Seo\Plugin\Plugin_Settings::compare
	 */
	public function testCompare(){
		$settings = $this->instance->get_settings();

		$test = new Plugin_Settings();
		$test->set('post_robot_nofollow',true);

		$this->assertEquals(array("post_robot_nofollow"=>false),$settings->compare($test));
	}

	/**
	 *
	 * @covers \Lti\Seo\Plugin\Plugin_Settings::save
	 */
	public function testSave(){
		$settings = $this->instance->get_settings();

		$new_settings = array(
			'keyword_support' => 'on',
			'frontpage_description' => 'on',
			'frontpage_description_text' => 'My description text'
		);

		$settings = $settings->save($new_settings);

		$this->assertEquals(true,$settings->get('keyword_support'));
		$this->assertEquals(true,$settings->get('frontpage_description'));
		$this->assertEquals('My description text',$settings->get('frontpage_description_text'));
	}

}

