<?php

/**
 * Lti + i8n = ltint
 *
 * @param $text
 * @param string $domain
 *
 * @return string|void
 */
function ltint( $text, $domain = 'lti-seo' ) {
	return __( $text, $domain );
}

function ltiopt( $value ) {
	$admin = \Lti\Seo\LTI_SEO::get_instance()->get_admin();
	return $admin->get_form_values()->get( $value );
}

function ltichk( $value ) {
	$val = ltiopt( $value );
	if ( $val == true ) {
		return 'checked="checked"';
	} else {
		return null;
	}
}

function ltirad( $key, $currentValue ) {
	$storedValue = ltiopt( $key );
	if ( $storedValue == $currentValue ) {
		return 'checked="checked"';
	} else {
		return null;
	}
}

function lti_iso8601_date( $mysqldate ) {
	return mysql2date( 'c', $mysqldate );
}