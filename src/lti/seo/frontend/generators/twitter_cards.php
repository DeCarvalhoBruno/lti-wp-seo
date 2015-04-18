<?php namespace Lti\Seo\Generators;

use Lti\Seo\Helpers\ICanHelp;
use Lti\Seo\Plugin\Plugin_Settings;


class Twitter_Card extends GenericMetaTag {

	protected $type = "summary";
	protected $tags;

	public function __construct( ICanHelp $helper, $type=null) {
		//$type = $this->helper->get( 'twitter_card_type' );
		if ( ! is_null( $type ) ) {
			$this->type = $type;
		}else{
			$this->type = $helper->get('twitter_card_type');
		}
		parent::__construct( $helper );

	}

	public function display_tags() {
		$img = $this->tags['twitter']['image'];
		unset( $this->tags['twitter']['image'] );

		$meta = "";
		foreach ( $this->tags as $tags => $tag ) {
			foreach ( $tag as $subtag => $property ) {
				$meta .= $this->generate_tag( $this->meta_tag_name_attribute, sprintf( '%s:%s', $tags, $subtag ),
					$property );
			}
		}

		$meta .= $this->process_images( $img );

		if ( ! empty( $meta ) ) {
			echo $meta;
		}

	}

	protected function process_images( $img ) {
		$meta = "";
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

		return $meta;
	}

}

class Frontpage_Twitter_Card extends Twitter_Card implements ICanMakeHeaderTags {

	protected $image_retrieval_mode = "fallback";

	protected $number_images = - 1;

	public function make_tags() {
		$twitter         = array();
		$handle          = $this->helper->get( 'twitter_publisher' );
		$twitter['card'] = $this->type;

		if ( ! is_null( $handle ) ) {
			$twitter['site'] = $handle;
		}
		$twitter['title']       = esc_attr( $this->helper->get_title() );
		$twitter['url']         = esc_url_raw( home_url('/') );
		$twitter['description'] = esc_attr( $this->helper->get_description() );
		$twitter['image']       = $this->helper->get_social_images( $this->image_retrieval_mode, $this->number_images );

		return compact( 'twitter' );

	}
}

class Singular_Twitter_Card extends Frontpage_Twitter_Card {
	public function make_tags() {
		$ar                       = parent::make_tags();
		$ar['twitter']['creator'] = $this->helper->get_author_social_url( "twitter" );

		return $ar;
	}
}

class Attachment_Twitter_Card extends Frontpage_Twitter_Card {

	protected $type = "photo";

	public function __construct( ICanHelp $helper) {
		parent::__construct( $helper, $this->type );
	}

}

class Author_Gallery_Twitter_Card extends Singular_Twitter_Card {

}

class Singular_Gallery_Twitter_Card extends Singular_Twitter_Card {

	protected $image_retrieval_mode = "all";

	protected $number_images = 4;

	protected $type = "gallery";

	public function __construct( ICanHelp $helper ) {
		parent::__construct( $helper, $this->type );
	}

	protected function process_images( $img ) {
		$meta = "";
		$i    = 0;
		if ( is_array( $img ) ) {
			foreach ( $img as $image ) {
				unset( $image->properties['type'] );
				unset( $image->properties['width'] );
				unset( $image->properties['height'] );
				$meta .= $this->generate_tag( $this->meta_tag_name_attribute, 'twitter:image' . $i ++, $image->url );
				foreach ( $image->properties as $key => $val ) {
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute, sprintf( 'twitter:image:%s', $key ),
						$val );
				}
			}
		}

		return $meta;
	}
}

