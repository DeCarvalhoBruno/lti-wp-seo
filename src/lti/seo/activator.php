<?php namespace Lti\Seo;

use Lti\Seo\Plugin\Plugin_Settings;

/**
 * Fired during plugin activation

 */

class Activator {

	/**
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$stored_options = get_option("lti_seo_options");
		if (empty($stored_options)) {
			$options = Plugin_Settings::get_defaults();
			update_option("lti_seo_options", $options);
		}
	}

}
