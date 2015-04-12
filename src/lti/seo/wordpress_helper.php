<?php namespace Lti\Seo;

class Wordpress_Helper {

	private $is_home_posts_page;
	private $is_home_static_page;
	private $is_posts_page;
	private $site_name;
	private $page_title;
	private $current_url;
	private $page_description;
	private $page_type;
	/**
	 * @var \WP_Post
	 */
	private $post_info;
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

	public function get_post_info( $key ) {
		$post = get_queried_object();
		if ( ! is_null( $post ) ) {
			return $post->{$key};
		}

		return null;
	}

	public function get_author_url() {
		$url = esc_url_raw( get_author_posts_url( get_the_author_meta( 'ID',
			$this->get_post_info( 'post_author' ) ) ) );

		return $url;
	}

	public function page_type() {
		if ( is_null( $this->page_type ) ) {
			if ( is_front_page() ) {
				$this->page_type = "Frontpage";
			} elseif ( is_singular() ) {
				$this->page_type = 'Singular';
			} elseif ( is_category() || is_tag() || is_tax() ) {
				$this->page_type = "Catagax";
			} elseif ( is_author() ) {
				$this->page_type = "Author";
			} elseif ( is_attachment() ) {
				$this->page_type = "Attachment";
			}
		}

		return $this->page_type;
	}

	public function page_post_format() {
		$post_type = get_post_format();
		if ( $post_type !== false&&$this->page_type()!="Frontpage" ) {
			return sprintf( "%s_%s", $this->page_type(), ucfirst( $post_type ) );
		}

		return $this->page_type();
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
			if ( is_singular() ) {
				$link = get_permalink();
			} elseif ( is_search() ) {
				$link = get_search_link();
			} elseif ( is_front_page() ) {
				$link = home_url();
			} elseif ( $this->is_posts_page() ) {
				$link = get_permalink( get_option( 'page_for_posts' ) );
			} elseif ( $this->page_type() == 'Catagax' ) {
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
			} elseif ( is_attachment() ) {
				$link = wp_get_attachment_url( $this->get_post_info( 'ID' ) );
			}
			$this->current_url = $link;
		}

		return $this->current_url;
	}

	public function get_twitter_handle() {
		if ( $this->page_type() == "Frontpage" ) {
			return $this->options->get( 'twitter_handle' );
		} else {
			//get author twitter info from profile
			return null;
		}
	}

	public function get_social_images( $mode = "", $number = - 1 ) {
		/**
		 * Allow filtering of the open graph image size
		 *
		 * @api string $index The index as returned by get_intermediate_image_sizes()
		 * @see get_intermediate_image_sizes()
		 *
		 */
		$image_size = apply_filters( 'lti_seo_image_size_index', 'full' );
		$data       = array();

		$image_data = $this->get_img( get_post_thumbnail_id( ), $image_size );

		if ( ! is_null( $image_data ) ) {
			$data[] = $image_data;
		}

		switch ( $mode ) {
			case "fallback":
				if ( empty( $data ) ) {
					$img_id     = $this->options->get( 'frontpage_social_img_id' );
					$image_data = $this->get_img( $img_id, $image_size );
					if ( ! is_null( $image_data ) ) {
						$data[] = $image_data;
					}
				}
				break;
			case "with_main_image":
				$img_id     = $this->options->get( 'frontpage_social_img_id' );
				$image_data = $this->get_img( $img_id, $image_size );
				if ( ! is_null( $image_data ) ) {
					$data[] = $image_data;
				}
				break;
			case "all":
				if ( $number == - 1 ) {
					$nb_img = $number;
				} else {
					$nb_img = $number - count( $data );
				}
				$img = array();
				if ( $this->page_post_format() == "Singular_Gallery" ) {
					$tmp = get_post_gallery_images();
					if ( ! empty( $tmp ) ) {
						$tmp = array_slice($tmp,0,4);

						$i=0;
						foreach ( $tmp as $gallery_img ) {
							$image_data             = new \stdClass();
							$image_data->url        = $gallery_img;
							$image_data->properties = array();
							$img[]                  = $image_data;
						}
					}
				} else {
					$img = $this->get_attached_post_images( $nb_img );
				}
				if ( ! empty( $img ) ) {
					$data = array_merge( $data, $img );

				}
				break;
		}

		if ( ! empty( $data ) ) {
			return $data;
		}

		return null;
	}

	public function get_attached_post_images( $limit = - 1 ) {
		$args = array(
			'numberposts'    => $limit,
			'post_parent'    => get_queried_object_id(),
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'order'          => 'ASC',
			'post_mime_type' => 'image',
			'orderby'        => 'menu_order ID'
		);

		$images = get_children( $args );

		if ( ! empty( $images ) ) {
			$output = array();
			foreach ( $images as $image ) {
				//if($image->)
				$output[] = $this->get_img( $image->ID );
			}

			return $output;
		}

		return null;
	}

	private function get_img( $img_id, $image_size = 'full' ) {
		if ( ! is_null( $img_id ) ) {
			$image_data = new \stdClass();
			$tmp        = wp_get_attachment_image_src( $img_id, $image_size );
			if ( $tmp !== false ) {
				$image_data->url                  = esc_url_raw( $tmp[0] );
				$image_data->properties           = array();
				$image_data->properties['width']  = $tmp[1];
				$image_data->properties['height'] = $tmp[2];
				$image_data->properties['type']   = esc_attr( get_post_mime_type( $img_id ) );

				return $image_data;
			}
		}

		return null;
	}

}
