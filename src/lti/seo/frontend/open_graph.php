<?php namespace Lti\Seo;

class Open_Graph {

	private $tags;

	private $supported_tags = array(
		"og"      => array(
			'type',
			'site_name',
			'title',
			'url',
			'description',
			'locale',
			'image' => array(
				'width',
				'height',
				'type'
			)
		),
		"article" => array(
			"published_time",
			"modified_time",
			"author",
			"section",
			"tag"
		)
	);


	public function __construct( $page_type, Wordpress_Helper $helper ) {
		$this->helper = $helper;

		switch ( $page_type ) {
			case "frontpage":
				$this->tags = $this->frontpage_tags();
				break;
			default:
				break;
		}
	}

	public function get_meta() {
		$img = $this->tags['og']['image'];
		unset( $this->tags['og']['image'] );

		$meta = "";
		foreach ( $this->tags as $tags => $tag ) {
			foreach ( $tag as $subtag => $property ) {
				$meta .= $this->generate_tag( sprintf( '%s:%s', $tags, $subtag ), $property );
			}
		}

		if ( ! is_null( $img ) ) {
			$meta .= $this->generate_tag( 'og:image', $img->url );
			foreach ( $img->properties as $key => $val ) {
				$meta .= $this->generate_tag( sprintf( 'og:image:%s', $key ), $val );
			}
		}

		if ( ! empty( $meta ) ) {
			echo $meta;
		}

	}

	public function frontpage_tags() {
		$og = array();

		$og['type']        = 'website';
		$og['site_name']   = $this->helper->get_site_name();
		$og['title']       = $this->helper->get_title();
		$og['url']         = $this->helper->get_canonical_url();
		$og['description'] = $this->helper->get_site_description();
		$og['locale']      = $this->helper->get_locale();
		$og['image']       = $this->helper->get_open_graph_img();

		return array( 'og' => $og );
	}

	public function generate_tag( $property, $content ) {
		return sprintf( '<meta property="%s" content="%s" />' . PHP_EOL, $property, $content );
	}

}




