<?php namespace Lti\Seo;

	/**
	 * The plugin bootstrap file
	 *
	 * This file is read by WordPress to generate the plugin information in the plugin
	 * admin area. This file also includes all of the dependencies used by the plugin,
	 * registers the activation and deactivation functions, and defines a function
	 * that starts the plugin.
	 *
	 * @link              http://example.com
	 *
	 * @wordpress-plugin
	 * Plugin Name:       LTI Search Engine Optimization
	 * Plugin URI:
	 * Description:       Search Engine Optimization
	 * Version:           0.5.0
	 * Author:            LTI
	 * Author URI:        https://github.com/DeCarvalhoBruno
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       lti-wp-seo
	 * Domain Path:       /languages/
	 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LTI_SEO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LTI_SEO_MAIN_CLASS_DIR', plugin_dir_path( __FILE__ ).'src/lti/seo/' );
define( 'LTI_SEO_VERSION', '0.5.0');
define( 'LTI_SEO_NAME', 'lti-wp-seo');

require_once( plugin_dir_path( __FILE__ ) . 'src/lti/seo/lti-seo.php' );

register_activation_hook( __FILE__, array( 'Lti\Seo\LTI_SEO', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Lti\Seo\LTI_SEO', 'deactivate' ) );

$plugin = LTI_SEO::get_instance();
$plugin->run();
