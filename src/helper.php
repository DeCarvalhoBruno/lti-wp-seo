<?php

/**
 * Lti + i8n = ltint
 *
 * @param $text
 * @param string $domain
 *
 * @return string|void
 */
function ltint( $text, $domain = 'lti-wp-seo' ) {
	return __( $text, $domain );
}

function ltint_po($value){
	return $value;
}

function ltiopt( $value ) {
	$admin = \Lti\Seo\LTI_SEO::get_instance()->get_admin();
	return esc_attr($admin->get_form_values()->get( $value ));
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

function ltipagetype() {
	$admin = \Lti\Seo\LTI_SEO::get_instance()->get_admin();
	return $admin->get_page_type();
}

function ltimessage() {
	$admin = \Lti\Seo\LTI_SEO::get_instance()->get_admin();
	return $admin->get_message();
}

function lti_iso8601_date( $date ) {
	return mysql2date( 'c', $date );
}

function lti_mysql_date_year($date){
	return mysql2date( 'Y', $date );
}