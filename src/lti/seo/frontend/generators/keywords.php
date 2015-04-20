<?php namespace Lti\Seo\Generators;

class Keyword extends GenericMetaTag {

	public function display_tags() {
		if ( ! is_null( $this->tags ) && ! empty( $this->tags ) ) {
			echo $this->generate_tag( 'name', 'keywords', esc_attr( $this->tags ) );
		}
	}


	public function make_tags() {
		return apply_filters( 'lti_seo_keywords', $this->tags );
	}
}

class Frontpage_Keyword extends Keyword implements ICanMakeHeaderTags {

	public function make_tags() {
		if ( $this->helper->get( 'frontpage_keyword' ) == true ) {
			$this->tags = explode( ",", trim( $this->helper->get( 'frontpage_keyword_text' ) ) );
			$this->tags = implode( ",", array_filter( $this->tags ) );
		}

		return parent::make_tags();
	}

}

class Singular_Keyword extends Keyword implements ICanMakeHeaderTags {

	public function make_tags() {
		if ( $this->helper->get( 'keyword_support' ) == true ) {
			$this->tags = explode( ",",
				str_replace( ', ', ',', trim( $this->helper->get_post_meta_key( 'keywords' ) ) ) );
			$this->tags = implode( ",", array_filter( $this->tags ) );
			if ( empty( $this->tags ) || is_null( $this->tags ) ) {
				$this->tags = array();
				if ( $this->helper->get( 'keyword_cat_based' ) === true ) {
					$this->tags = array_unique( $this->helper->extract_array_object_value( get_the_category( $this->post_id ),
						'cat_name' ) );
				}
				if ( $this->helper->get( 'keyword_tag_based' ) === true ) {
					$this->tags = array_unique( array_merge( $this->tags,
						$this->helper->extract_array_object_value( get_the_tags( $this->post_id ), 'name' ) ) );
				}
			}
			if ( is_array( $this->tags ) ) {
				$this->tags = implode( ',', array_filter( array_map( 'strtolower', $this->tags ) ) );
			}
		}

		return parent::make_tags();
	}
}