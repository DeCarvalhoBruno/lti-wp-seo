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
 * @since             1.0.0
 * @package           lti-seo
 *
 * @wordpress-plugin
 * Plugin Name:       LTI Search Engine Optimization
 * Plugin URI:
 * Description:       Search Engine Optimization
 * Version:           1.0.0
 * Author:            LTI
 * Author URI:        https://github.com/DeCarvalhoBruno
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lti-seo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


function activate_lti_seo() {
	require_once plugin_dir_path( __FILE__ ) . 'src/lti/seo/activator.php';
	Activator::activate();
}

function deactivate_lti_seo() {
	require_once plugin_dir_path( __FILE__ ) . 'src/lti/seo/deactivator.php';
	Deactivator::deactivate();
}

add_action('activate_lti-wp-seo', 'activate_lti_seo');
add_action('deactivate_lti-wp-seo', 'deactivate_lti_seo');


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'src/lti/seo/lti-seo.php';

$plugin = LTI_SEO::getInstance();
$plugin->run();
