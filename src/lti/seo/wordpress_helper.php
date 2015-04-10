<?php namespace Lti\Seo;

class Wordpress_Helper {

	private $is_home_posts_page;

	private $is_home_static_page;

	private $is_posts_page;

	private $is_front_page;

	private $is_singular;

	private $home_url;

	private $site_name;

	private $page_title;

	private $current_url;

	private $page_description;

	private $locale;

	/**
	 * @var \Lti\Seo\Settings
	 */
	private $options;

	public function __construct( $options ) {
		$this->options = $options;
	}

	public function get_site_name() {
		if ( is_null( $this->site_name ) ) {
			$this->site_name = esc_attr( get_bloginfo( 'name' ) );
		}

		return $this->site_name;
	}

	public function get_site_description() {
		if ( is_null( $this->page_description ) ) {
			if ( $this->options->get( 'frontpage_description' ) ) {
				$this->page_description = $this->options->get( 'frontpage_description' );
			} else {
				$this->page_description = get_bloginfo( 'description' );
			}
		}

		return $this->page_description;
	}

	public function get_title() {
		if ( is_null( $this->page_title ) ) {
			$this->page_title = wp_title( '|', false, 'right' );
		}

		return $this->page_title;
	}


	public function is_singular() {
		if ( is_null( $this->is_singular ) ) {
			$this->is_singular = is_singular();
		}

		return $this->is_singular;
	}

	public function get_locale() {
		if ( is_null( $this->locale ) ) {
			$this->locale = get_bloginfo( 'language' );
		}

		return $this->locale;
	}

	/**
	 * Is this the front page?
	 * @return bool
	 */
	public function is_front_page() {
		if ( is_null( $this->is_front_page ) ) {
			$this->is_front_page = is_front_page();
		}

		return $this->is_front_page;
	}

	/**
	 * Is this the homepage AND does it show posts?
	 *
	 * @return bool
	 */
	public function is_home_posts_page() {
		if ( is_null( $this->is_home_posts_page ) ) {
			$this->is_home_posts_page = ( is_home() && 'posts' == get_option( 'show_on_front' ) );
		}

		return $this->is_home_posts_page;

	}

	/**
	 * Is this a static page being used as the frontpage?
	 *
	 * @return bool
	 */
	public function is_home_static_page() {
		if ( is_null( $this->is_home_static_page ) ) {
			$this->is_home_static_page = ( is_front_page() && 'page' == get_option( 'show_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
		}

		return $this->is_home_static_page;

	}

	/**
	 * Is this the show posts page?
	 *
	 * @return bool
	 */
	public function is_posts_page() {
		if ( is_null( $this->is_posts_page ) ) {
			$this->is_posts_page = ( is_home() && 'page' == get_option( 'show_on_front' ) );
		}

		return $this->is_posts_page;
	}

	public function get_canonical_url() {
		if ( is_null( $this->current_url ) ) {
			$link = "";
			if ( is_search() ) {
				$link = get_search_link();
			} elseif ( is_front_page() ) {
				$link = home_url();
			} elseif ( $this->is_posts_page() ) {
				$link = get_permalink( get_option( 'page_for_posts' ) );
			} elseif ( is_tax() || is_tag() || is_category() ) {
				$term = get_queried_object();
				$link = get_term_link( $term, $term->taxonomy );
			} elseif ( is_post_type_archive() ) {
				$post_type = get_query_var( 'post_type' );
				if ( is_array( $post_type ) ) {
					$post_type = reset( $post_type );
				}
				$link = get_post_type_archive_link( $post_type );
			} elseif ( is_author() ) {
				$link = get_author_posts_url( get_query_var( 'author' ), get_query_var( 'author_name' ) );
			} elseif ( is_archive() ) {
				if ( is_date() ) {
					if ( is_day() ) {
						$link = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ),
							get_query_var( 'day' ) );
					} elseif ( is_month() ) {
						$link = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
					} elseif ( is_year() ) {
						$link = get_year_link( get_query_var( 'year' ) );
					}
				}
			}
			$this->current_url = $link;
		}

		return $this->current_url;
	}

	public function home_url() {
		if ( is_null( $this->home_url ) ) {
			$this->home_url = home_url();
		}

		return $this->home_url;
	}

	public function get_open_graph_img(){
		if($this->is_front_page()){
			if($this->options->get( 'open_graph_support' )===true){
				/**
				 * Allow filtering of the open graph image size
				 *
				 * @api string $index The index as returned by get_intermediate_image_sizes()
				 * @see get_intermediate_image_sizes()
				 *
				 */
				$image_size = apply_filters( 'lti_seo_image_size_index', 'full' );

				$img_id = $this->options->get( 'og_frontpage_img_id' );
				if(!is_null($img_id)){
					$image_data = new \stdClass();
					$tmp= wp_get_attachment_image_src($img_id, $image_size);
					if($tmp!==false){
						$image_data->url = $tmp[0];
						$image_data->properties['width'] = $tmp[1];
						$image_data->properties['height'] = $tmp[2];
						$image_data->properties['type'] = get_post_mime_type($img_id);
						return $image_data;
					}
				}
			}
		}
		return null;
	}

}
