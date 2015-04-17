<?php namespace Lti\Seo\Generators;

class Open_Graph extends GenericMetaTag {

	protected $meta_tag_name_attribute = "property";

	public function display_tags() {
		$img = $this->tags['og']['image'];
		unset( $this->tags['og']['image'] );

		$meta = "";
		foreach ( $this->tags as $tags => $tag ) {
			foreach ( $tag as $subtag => $property ) {
				$meta .= $this->generate_tag( $this->meta_tag_name_attribute, sprintf( '%s:%s', $tags, $subtag ),
					$property );
			}
		}

		if ( is_array( $img ) ) {
			foreach ( $img as $image ) {
				$meta .= $this->generate_tag( $this->meta_tag_name_attribute, 'og:image', $image->url );
				foreach ( $image->properties as $key => $val ) {
					$meta .= $this->generate_tag( $this->meta_tag_name_attribute, sprintf( 'og:image:%s', $key ),
						$val );
				}
			}
		}

		if ( ! empty( $meta ) ) {
			echo $meta;
		}
	}

}

class Frontpage_Open_Graph extends Open_Graph implements ICanMakeHeaderTags {

	public function make_tags() {
		$og = array();

		$og['type']        = 'website';
		$og['site_name']   = esc_attr( $this->helper->get_site_name() );
		$og['title']       = esc_attr( $this->helper->get_title() );
		$og['url']         = esc_url_raw( $this->helper->get_shortlink() );
		$og['description'] = esc_attr( $this->helper->get_description() );
		$og['locale']      = esc_attr( get_bloginfo( 'language' ) );
		$og['image']       = $this->helper->get_social_images( $this->image_retrieval_mode, $this->number_images );

		return compact( 'og' );
	}


}

class Singular_Open_Graph extends Frontpage_Open_Graph implements ICanMakeHeaderTags {

	public function make_tags() {
		$ar = parent::make_tags();

		$article['published_time'] = esc_attr( lti_iso8601_date( $this->helper->get_post_info( 'post_date' ) ) );
		$article['modified_time']  = esc_attr( lti_iso8601_date( $this->helper->get_post_info( 'post_modified' ) ) );
		$article['author']         = $this->helper->get_author_social_url('facebook');
		$publisher                 = $this->helper->get( 'facebook_publisher' );

		if ( ! is_null( $publisher ) ) {
			$article['publisher'] = esc_url_raw( $publisher );
		}

		$author = $this->helper->get_author_social_url("facebook");
		if(!is_null($author)){
			$article['author'] = $author;
		}

		$ar['article'] = $article;

		return $ar;
	}

}

class Author_Open_Graph extends Singular_Open_Graph implements ICanMakeHeaderTags {

}