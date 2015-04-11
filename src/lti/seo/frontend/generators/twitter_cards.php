<?php namespace Lti\Seo\Generators;

use Lti\Seo\Wordpress_Helper;


class Twitter_Card extends GenericTagMaker {
	protected $tags;

	public function __construct( Wordpress_Helper $helper, $type = "summary", $handle = null ) {
		$this->type   = $type;
		$this->handle = $handle;
		parent::__construct( $helper );
	}

	public function get_tags() {
		$img = $this->tags['twitter']['image'];
		unset( $this->tags['twitter']['image'] );

		$meta = "";
		foreach ( $this->tags as $tags => $tag ) {
			foreach ( $tag as $subtag => $property ) {
				$meta .= $this->generate_tag( $this->meta_tag_name_attribute, sprintf( '%s:%s', $tags, $subtag ),
					$property );
			}
		}

		if ( is_array( $img ) ) {
			foreach ( $img as $image ) {
				unset( $image->properties['type'] );
				$meta .= $this->generate_tag( $this->meta_tag_name_attribute, 'twitter:image:src', $image->url );
				foreach ( $image->properties as $key => $val ) {
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute, sprintf( 'twitter:image:%s', $key ),
						$val );
				}
			}
		}

		if ( ! empty( $meta ) ) {
			echo $meta;
		}

	}

}

class Frontpage_Twitter_Card extends Twitter_Card implements CanMakeHeaderTags {

	public function make_tags() {
		$twitter = array();

		$twitter['card'] = $this->type;
		if ( ! is_null( $this->handle ) ) {
			$twitter['site']    = $this->handle;
			$twitter['creator'] = $this->handle;
		}
		$twitter['title']       = esc_attr( $this->helper->get_title() );
		$twitter['description'] = esc_attr( $this->helper->get_site_description() );
		$twitter['image']       = $this->helper->get_social_images( "fallback" );

		return compact( 'twitter' );

	}
}

class Singular_Twitter_Card extends Frontpage_Twitter_Card {
}

