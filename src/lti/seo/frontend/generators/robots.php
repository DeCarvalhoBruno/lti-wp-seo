<?php namespace Lti\Seo\Generators;

class Robot extends GenericMetaTag {

	public function display_tags() {
		if ( !is_null( $this->tags ) && ! empty( $this->tags ) ) {
			echo $this->generate_tag( 'name', 'robots', $this->tags );
		}
	}
}

class Frontpage_Robot extends Robot implements ICanMakeHeaderTags {

	public function make_tags() {
		$tags = $this->helper->get_robots();
		$tags = apply_filters( 'lti_seo_robots', $tags );
		
		if ( is_array( $tags ) ) {
			return implode( ',', $tags );
		}
		return null;
	}

}

class Singular_Robot extends Frontpage_Robot {

}

class Archive_Robot extends Frontpage_Robot {

}

class Catagax_Robot extends Frontpage_Robot {

}

class Search_Robot extends Frontpage_Robot {

}

class NotFound_Robot extends Frontpage_Robot {

}

class Author_Robot extends Frontpage_Robot {

}