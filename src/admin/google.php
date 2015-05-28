<?php namespace Lti\Seo;

use Lti\Google\Google_Helper;

class Admin_Google {
	public $can_send_curl_requests;
	public $error;

	/**
	 * @var \Lti\Google\Google_Helper
	 */
	public $helper;

	public function __construct( Admin $admin ) {
		$this->admin                  = $admin;
		$this->can_send_curl_requests = function_exists( 'curl_version' );
		if ( $this->can_send_curl_requests === true ) {
			$this->helper = new Google_Helper( array(
				'https://www.googleapis.com/auth/webmasters',
				'https://www.googleapis.com/auth/siteverification',
			),  LTI_SEO_NAME );

			$access_token = $this->admin->get_setting( 'google_access_token' );
			if ( ! is_null( $access_token ) && ! empty( $access_token ) ) {
				$this->helper->set_access_token( $access_token );

				if ( $this->helper->assess_token_validity() !== true ) {
					$this->admin->remove_setting( 'google_access_token' );
				}
			}
		}
	}

	public function get_site_info() {
		$this->helper->init_site_service( 'http://caprica.linguisticteam.org' );
		$obj       = new \stdClass();
		$obj->site = $this->helper->get_site_service();
		try {
			$obj->site->request_site_info();
			$obj->is_listed = true;
		} catch ( \Google_Service_Exception $e ) {
			$obj->is_listed = false;
		}

		return $obj;
	}

	public function google_auth( $post_variables ) {
		try {
			$this->admin->set_setting( 'google_access_token',
				$this->helper->authenticate( $post_variables['google_auth_token'] ) );
			$this->message = ltint( 'msg.google_logged_in' );
		} catch ( \Google_Auth_Exception $e ) {
			$this->error = array(
				'error'           => ltint( 'err.google_auth_failure' ),
				'google_response' => $e->getMessage()
			);
			$this->admin->remove_setting( 'google_access_token' );
		}
	}

	public function google_logout() {
		$this->admin->remove_setting( 'google_access_token' );
		$this->message = ltint( 'google.msg.logout' );
		$this->helper->revoke_token();
	}

	public function google_add() {
		try {
			$this->helper->get_site_service()->add_site();
		} catch ( \Google_Auth_Exception $e ) {
			$this->error = array(
				'error'           => ltint( 'err.google_add_failure' ),
				'google_response' => $e->getMessage()
			);

			return null;
		}
		$this->google_verify();
		$this->message = ltint( 'msg.google_added' );

	}

	public function google_verify() {
		try {
			$site_service     = $this->helper->get_site_service();
			$activation_token = $site_service->get_verification_token();
			$this->admin->set_setting( 'google_meta_activation', $activation_token );
			$site_service->verify_site();
		} catch ( \Google_Service_Exception $e ) {
			$this->error = array(
				'error'           => ltint( 'err.google_verify_failure' ),
				'google_response' => $e->getMessage()
			);
		}
	}
}
