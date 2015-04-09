<?php namespace Lti\Seo;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */
class Frontend {

	private $plugin_name;

	private $version;

	private $is_home_posts_page;

	private $is_home_static_page;

	private $is_posts_page;

	private $is_front_page;


	public function __construct( $plugin_name, $version, Settings $settings ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->settings = $settings;
	}


	public function rel_canonical() {
		if ( is_singular() ) {
			return;
		}
		$canonical = $this->generate_canonical_link();
		echo "<link rel='canonical' href='$canonical' />\n";
	}

	public function wp_head() {
		do_action( 'lti_seo_head' );
	}

	public function front_page_head(){
		if ( ! $this->is_front_page() ) {
			return;
		}
		new JSON_LD($this->settings);
		add_action( 'lti_seo_head', array( $this, 'frontpage_description' ), 10 );
	}

	/**
	 * Is this the front page?
	 * @return bool
	 */
	public function is_front_page() {
		if ( !is_null( $this->is_front_page ) ) {
			return $this->is_front_page;
		} else {
			return is_front_page();
		}
	}

	/**
	 * Is this the homepage AND does it show posts?
	 *
	 * @return bool
	 */
	public function is_home_posts_page() {
		if ( !is_null( $this->is_home_posts_page ) ) {
			return $this->is_home_posts_page;
		} else {
			return ( is_home() && 'posts' == get_option( 'show_on_front' ) );
		}
	}

	/**
	 * Is this a static page being used as the frontpage?
	 *
	 * @return bool
	 */
	public function is_home_static_page() {
		if ( !is_null( $this->is_home_static_page ) ) {
			return $this->is_home_static_page;
		} else {
			return ( is_front_page() && 'page' == get_option( 'show_on_front' ) && is_page( get_option( 'page_on_front' ) ) );
		}
	}

	/**
	 * Is this the show posts page?
	 *
	 * @return bool
	 */
	public function is_posts_page() {
		if ( !is_null( $this->is_posts_page ) ) {
			return $this->is_posts_page;
		} else {
			return ( is_home() && 'page' == get_option( 'show_on_front' ) );
		}
	}

	public function generate_canonical_link(){
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
		return $link;
	}

	public function frontpage_description(){
		if(!is_null($this->settings->value('frontpage_description'))&&!is_null($this->settings->value('frontpage_description_text'))){
			echo '<meta name="description" content="'.$this->settings->value('frontpage_description_text').'"/>'.PHP_EOL;
		}
	}


}
