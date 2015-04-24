<?php namespace Lti\Seo\Helpers;

use Lti\Seo\Plugin\Fields;
use Lti\Seo\Plugin\Postbox_Values;

class Wordpress_Helper extends Helper implements ICanHelp, ICanHelpWithJSONLD {

	private $is_home_posts_page;
	private $is_home_static_page;
	private $is_posts_page;
	private $site_name;
	private $page_title;
	private $current_url;
	private $page_description;
	private $page_type;
	private $post_id;
	private $shortlink;
	private $post_meta;

	/**
	 * @var \Lti\Seo\Plugin\Plugin_Settings
	 */
	protected $settings;

	public function __construct( $settings ) {
		parent::__construct( $settings );
	}

	public function get( $value ) {
		return $this->settings->get( $value );
	}

	public function init() {
		$this->page_type();
		$this->get_canonical_url();
	}

	public function get_site_name() {
		if ( is_null( $this->site_name ) ) {
			$this->site_name = esc_attr( get_bloginfo( 'name' ) );
		}

		return $this->site_name;
	}

	public function get_title() {
		if ( is_null( $this->page_title ) ) {
			$this->page_title = wp_title( '|', false, 'right' );
		}

		return $this->page_title;
	}

	public function get_post_info( $key ) {
		$field = get_post_field( $key, $this->post_id, 'raw' );
		if ( ! empty( $field ) ) {
			return $field;
		}

		return null;
	}

	public function get_post_meta() {
		if ( is_null( $this->post_meta ) ) {
			$box_values      = get_post_meta( $this->post_id, "lti_seo", true );
			$this->post_meta = $box_values;
		}

		return $this->post_meta;
	}

	public function get_post_meta_key( $key ) {
		if ( is_null( $this->post_meta ) ) {
			$this->get_post_meta();
		}

		if ( ! empty( $this->post_meta ) && $this->post_meta instanceof Postbox_Values ) {
			return $this->post_meta->get( $key );
		}

		return null;
	}

	public function get_author_url() {
		$url = esc_url_raw( get_author_posts_url( get_the_author_meta( 'ID',
			$this->get_post_info( 'post_author' ) ) ) );

		return $url;
	}

	public function get_author_social_info( $platform ) {
		if ( $this->page_type() == "Author" ) {
			$author = get_query_var( 'author' );
		} else {
			$author = get_the_author_meta( 'ID',
				$this->get_post_info( 'post_author' ) );
		}
		switch ( $platform ) {
			case 'facebook':
				$info['first_name'] = get_user_meta( $author, "first_name", true );
				$info['last_name']  = get_user_meta( $author, "last_name", true );
				$info['profile_id'] = get_user_meta( $author, "lti_facebook_id", true );
				break;
			case 'twitter':
				$info = get_user_meta( $author, "lti_twitter_username",true );
				break;
			case 'gplus':
				$info = get_user_meta( $author, "lti_gplus_url",true );
				break;
		}
		return $info;
	}

	public function page_type() {
		if ( is_null( $this->page_type ) ) {
			if ( is_front_page() ) {
				$this->page_type = "Frontpage";
			} elseif ( is_singular() ) {
				if ( is_attachment() ) {
					$this->page_type = 'Attachment';
				} else {
					$this->page_type = 'Singular';
					$this->post_id   = $this->get_post_info( 'ID' );
				}
			} elseif ( is_category() || is_tag() || is_tax() ) {
				$this->page_type = "Catagax";
			} elseif ( is_author() ) {
				$this->page_type = "Author";
			} elseif ( is_archive() ) {
				$this->page_type = 'Archive';
			} elseif ( is_search() ) {
				$this->page_type = 'Search';
			} elseif ( is_404() ) {
				$this->page_type = 'NotFound';
			}
		}

		return $this->page_type;
	}

	public function get_post_id() {
		return $this->post_id;
	}

	public function page_post_format() {
		$post_type = get_post_format();
		if ( $post_type !== false && $this->page_type() != "Frontpage" ) {
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

	public function get_home_url(){
		return home_url('/');
	}

	public function get_canonical_url() {
		if ( is_null( $this->current_url ) ) {
			$this->current_url = apply_filters( 'lti_seo_get_canonical_url', false );

			if ( $this->current_url === false ) {
				$link = "";
				if ( is_singular() ) {
					$link = get_permalink();
				} elseif ( is_search() ) {
					$link = get_search_link();
				} elseif ( is_front_page() ) {
					$link = home_url('/');
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
					$link = wp_get_attachment_url( $this->post_id );
				} else {
					$link = get_permalink();
				}
				$this->current_url = $link;
			}
		}
		return $this->current_url;
	}

	public function get_shortlink() {
		if ( is_null( $this->shortlink ) ) {
			$link = wp_get_shortlink();
			if ( ! is_null( $link ) && ! empty( $link ) ) {
				$this->shortlink = $link;
			} else {
				$this->shortlink = get_permalink();
			}
		}

		return $this->shortlink;
	}

	public function get_social_images( $number = - 1 ) {

		/**
		 * Allow filtering of the image size
		 *
		 * @api string $index The index as returned by get_intermediate_image_sizes()
		 * @see get_intermediate_image_sizes()
		 *
		 */
		$image_size = apply_filters( 'lti_seo_image_size_index', 'large' );

		$data = array();

		$image_data = null;
		if ( ! is_null( $this->post_id ) ) {
			$image_data = $this->get_img( get_post_thumbnail_id(), $image_size );
			if ( ! is_null( $image_data ) ) {
				$data[ get_post_thumbnail_id() ] = $image_data;
			}

			if ( $number == - 1 ) {
				$nb_img = $number;
			} else {
				$nb_img = $number - count( $data );
			}
			$img = array();
			if ( $this->page_post_format() == "Singular_Gallery" ) {
				$tmp = get_post_galleries( $this->post_id, false );

				if ( ! empty( $tmp ) && isset( $tmp[0]['ids'] ) ) {
					$tmp = explode( ',', $tmp[0]['ids'], 4 );

					foreach ( $tmp as $id ) {
						$data[ $id ] = $this->get_img( $id );
					}
				}
			} else {
				$img = $this->get_attached_post_images( $nb_img );
			}
			if ( ! empty( $img ) ) {
				$data = array_merge( $data, $img );
			}
		}

		$img_id     = $this->get_custom_social_image();
		$image_data = $this->get_img( $img_id, $image_size );
		if ( ! is_null( $image_data ) ) {
			$data[ $img_id ] = $image_data;
		}

		if ( ! empty( $data ) ) {
			return $data;
		}

		return null;
	}

	private function get_custom_social_image() {
		$img_id = null;
		if ( $this->page_type() == "Frontpage" ) {
			$img_id = $this->settings->get( 'frontpage_social_img_id' );
		} else {
			$img_id = $this->get_post_meta_key( 'social_img_id' );
		}

		return $img_id;
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
				$output[ $image->ID ] = $this->get_img( $image->ID );
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

	public function get_keywords( $post_id = null ) {
		$keywords = array();
		if ( ! is_null( $post_id ) ) {
			$post_id = $this->post_id;
		}
		if ( $this->page_type() == "Frontpage" ) {
			$keywords = explode( ",", $this->settings->get( 'frontpage_keyword_text' ) );
		} else {
			if ( $this->settings->get( 'keyword_support' ) == true ) {
				$keywords = str_replace( ', ', ',', $this->get_post_meta_key( 'keyword_text' ) );
				if ( empty( $keywords ) || is_null( $keywords ) ) {
					$keywords = array();
					if ( $this->settings->get( 'keyword_cat_based' ) === true ) {
						$keywords = array_unique( $this->get_categories() );
					}
					if ( $this->settings->get( 'keyword_tag_based' ) === true ) {
						$keywords = array_unique( array_merge( $keywords,
							$this->get_tags() ) );
					}
				}
			}
		}
		if ( is_array( $keywords ) ) {
			$keywords = implode( ',', array_map( 'strtolower', $keywords ) );
		}

		return $keywords;
	}

	public function get_categories() {
		return $this->extract_array_object_value( get_the_category( $this->post_id ),
			'cat_name' );
	}

	public function get_tags() {
		return $this->extract_array_object_value( get_the_tags( $this->post_id ),
			'name' );
	}

	public function extract_array_object_value( $values, $field ) {
		$vals = array();
		if ( is_array( $values ) ) {
			foreach ( $values as $value ) {
				$vals[] = $value->{$field};
			}
		}

		return $vals;

	}

	public function update_global_post_fields( $changed = array(), $reset = false ) {
		/**
		 * @var \wpdb $wpdb
		 */
		global $wpdb;
		$sql = 'SELECT ' . $wpdb->posts . '.ID,' . $wpdb->postmeta . '.meta_value  FROM ' . $wpdb->posts . '
				LEFT JOIN ' . $wpdb->postmeta . ' ON (' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id AND ' . $wpdb->postmeta . '.meta_key = "lti_seo")
				WHERE ' . $wpdb->posts . '.post_type = "post" AND ' . $wpdb->posts . '.post_status!="auto-draft"';

		$results = $wpdb->get_results( $sql );

		if ( is_array( $results ) ) {
			foreach ( $results as $result ) {
				$postbox_values = $result->meta_value;
				if ( ! is_null( $postbox_values ) && ! $reset ) {
					$postbox_values = unserialize( $postbox_values );
				} else {
					$postbox_values = new Postbox_Values( new \stdClass() );
				}

				foreach ( $changed as $changedKey => $changedValue ) {
					if ( isset( $postbox_values->{$changedKey} ) && $postbox_values->{$changedKey} instanceof Fields ) {
						$postbox_values->{$changedKey}->value = $changedValue;
					}
				}

				update_post_meta( $result->ID, 'lti_seo', $postbox_values );
			}
		}
	}

	public function get_description() {
		if ( is_null( $this->page_description ) ) {
			if ( $this->page_type() === "Frontpage" ) {
				if ( ! is_null( $this->settings->get( 'frontpage_description_text' ) ) ) {
					$this->page_description = $this->settings->get( 'frontpage_description_text' );
				} else {
					$this->page_description = get_bloginfo( 'description' );
				}
			} else {
				if ( $this->settings->get( 'description_support' ) == true ) {
					$this->page_description = $this->get_post_meta_key( 'description' );
				}
			}
		}

		return esc_attr( $this->page_description );
	}

	public function get_social_urls()
	{
		$social_profiles = array(
			'account_facebook',
			'account_twitter',
			'account_gplus',
			'account_instagram',
			'account_youtube',
			'account_linkedin',
			'account_myspace'
		);
		$profiles = array();
		foreach ($social_profiles as $profile) {
			$tmp = $this->get($profile);
			if (!is_null($tmp) && !empty($tmp)) {
				$profiles[] = $tmp;
			}
		}
		return $profiles;
	}

	public function get_thing_name()
	{
		return $this->get('jsonld_type_name');
	}

	public function get_thing_logo()
	{
		return $this->get('jsonld_type_logo_url');
	}

	public function get_thing_alternate_name()
	{
		return $this->get('alternate_name');
	}

	public function get_current_url(){
		return $this->current_url;
	}

	public function get_search_action_type(){
		return 'Lti\Seo\Generators\WordpressSearchAction';
	}

}
