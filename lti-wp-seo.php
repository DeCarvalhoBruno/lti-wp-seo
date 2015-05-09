<?php namespace Lti\Seo;

	/**
	 * The plugin bootstrap file
	 *
	 * @wordpress-plugin
	 * Plugin Name:       LTI Search Engine Optimization
	 * Plugin URI:        http://dev.linguisticteam.org/lti-seo-help/
	 * Description:       Search Engine Optimization
	 * Version:           0.5.0
	 * Author:            Bruno De Carvalho
	 * Author URI:        http://dev.linguisticteam.org/author/brunodc/
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
define( 'LTI_SEO_MAIN_CLASS_DIR', plugin_dir_path( __FILE__ ) . 'src/' );
define( 'LTI_SEO_VERSION', '0.5.0' );
define( 'LTI_SEO_NAME', 'lti-wp-seo' );

require_once( plugin_dir_path( __FILE__ ) . 'src/lti-seo.php' );

register_activation_hook( __FILE__, array( 'Lti\Seo\LTI_SEO', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Lti\Seo\LTI_SEO', 'deactivate' ) );

$plugin = LTI_SEO::get_instance();
$plugin->run();
