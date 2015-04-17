<?php namespace Lti\Seo\Generators;

class Description extends GenericMetaTag {

	public function display_tags() {
		if ( ! is_null( $this->tags ) && ! empty( $this->tags ) ) {
;			echo $this->generate_tag( 'name', 'description', $this->tags );
		}
	}

	public function make_tags() {
		$tag = $this->helper->get_description();

		return apply_filters( 'lti_seo_description', $tag );
	}
}

class Frontpage_Description extends Description implements ICanMakeHeaderTags {
	public function make_tags() {
		if ( $this->helper->get( 'frontpage_description' ) == true ) {
			return parent::make_tags();
		}

		return null;
	}
}

class Singular_Description extends Description {


}