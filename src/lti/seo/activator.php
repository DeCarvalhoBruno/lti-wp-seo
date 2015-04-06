<?php namespace Lti\Seo;

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    lti-seo
 * @subpackage lti-seo/includes
 */

class Activator {

	/**
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$stored_options = get_option("lti_seo_options");

		if (empty($stored_options)) {
//			$this->options = $settings->defaults;
//			update_option("lti_seo_options", $this->options);
		}
	}

}
