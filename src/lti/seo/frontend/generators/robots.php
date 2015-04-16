<?php namespace Lti\Seo\Generators;

class Robot extends GenericMetaTag {
	protected $setting;
	protected $prefix;

	public function display_tags() {
		if ( ! is_null( $this->tags ) && ! empty( $this->tags ) ) {
			echo $this->generate_tag( 'name', 'robots', $this->tags );
		}
	}

	public function get_robot() {
		return $this->get_robot_setting( $this->setting, $this->prefix );
	}

	protected function get_robot_setting( $setting, $settings_prefix = "" ) {
		$robots = array();
		if ( $this->settings->get( $setting ) === true ) {
			if ( $this->settings->get( $settings_prefix . 'robot_noindex' ) === true ) {
				$robots[] = 'noindex';
			}
			if ( $this->settings->get( $settings_prefix . 'robot_nofollow' ) === true ) {
				$robots[] = 'nofollow';
			}
			if ( $this->settings->get( $settings_prefix . 'robot_noodp' ) === true ) {
				$robots[] = 'noodp';
			}
			if ( $this->settings->get( $settings_prefix . 'robot_noydir' ) === true ) {
				$robots[] = 'noydir';
			}
			if ( $this->settings->get( $settings_prefix . 'robot_noarchive' ) === true ) {
				$robots[] = 'noarchive';
			}
			if ( $this->settings->get( $settings_prefix . 'robot_nosnippet' ) === true ) {
				$robots[] = 'nosnippet';
			}
		}

		return $robots;
	}

	public function make_tags() {
		$tags = $this->get_robot();
		$tags = apply_filters( 'lti_seo_robots', $tags );

		if ( is_array( $tags ) ) {
			return implode( ',', $tags );
		}

		return null;
	}
}

class Frontpage_Robot extends Robot implements ICanMakeHeaderTags {
	protected $setting = 'frontpage_robot';
	protected $prefix = 'frontpage_';



}

class Singular_Robot extends Robot {
	protected $setting = 'robot_support';
	protected $prefix = 'post_';
}

class Archive_Robot extends Robot {
	protected $setting = 'robot_date_based';
}

class Catagax_Robot extends Frontpage_Robot {
	public function get_robot() {
		if ( is_category() ) {
			return $this->get_robot_setting( 'robot_cat_based' );
		} else if ( is_tag() ) {
			return $this->get_robot_setting( 'robot_tag_based' );
		}

		return null;
	}
}

class Search_Robot extends Robot {
	protected $setting = 'robot_search_based';
}

class NotFound_Robot extends Robot {
	protected $setting = 'robot_notfound_based';

}

class Author_Robot extends Robot {
	protected $setting = 'robot_author_based';
}