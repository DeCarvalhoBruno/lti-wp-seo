<?php
// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

delete_option('lti_seo_options');
delete_post_meta_by_key( 'lti_seo' );

?>