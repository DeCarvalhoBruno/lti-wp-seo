<?php namespace Lti\Seo;

use Lti\Seo\LTI_SEO;

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
			$options = Settings::get_defaults();
			update_option("lti_seo_options", $options);
		}

		return $stored_options;
	}

}
